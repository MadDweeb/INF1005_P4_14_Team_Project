<?php
/**
 * src/controllers/ContactController.php
 *
 * Handles the public contact form submission.
 *
 * Routed from public/index.php:
 *   POST /contact → submit()
 *
 * form is public, does not require login.
 */

declare(strict_types=1);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../helpers/csrf.php';
require_once __DIR__ . '/../helpers/sanitize.php';

class ContactController
{
    public function __construct(?PDO $pdo)
    {
        // No database dependency is required for the contact form, but the
        // constructor matches the rest of the controller pattern.
    }

    // Handle POST /contact.
    public function submit(): void
    {
        verifyCsrf();

        $oldInput = [
            'name'    => sanitizeString((string) ($_POST['name'] ?? '')),
            'email'   => sanitizeEmail((string) ($_POST['email'] ?? '')),
            'subject' => sanitizeString((string) ($_POST['subject'] ?? '')),
            'message' => trim((string) ($_POST['message'] ?? '')),
        ];

        $errors = [];

        if ($this->textLength($oldInput['name']) < 2 || $this->textLength($oldInput['name']) > 100) {
            $errors['name'] = 'Please enter your name (2 to 100 characters).';
        }

        if (!filter_var($oldInput['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }

        if ($this->textLength($oldInput['subject']) < 3 || $this->textLength($oldInput['subject']) > 150) {
            $errors['subject'] = 'Please enter a subject between 3 and 150 characters.';
        }

        if ($this->textLength($oldInput['message']) < 10 || $this->textLength($oldInput['message']) > 5000) {
            $errors['message'] = 'Please enter a message between 10 and 5000 characters.';
        }

        if (!empty($errors)) {
            $this->renderContactPage($errors, $oldInput);
            return;
        }

        $contactEmail = 'hello@keyforge.example';
        $messageText   = $this->buildPlainTextMessage($oldInput);
        $messageHtml   = $this->buildHtmlMessage($oldInput);

        $mailgunApiKey = trim((string) ($_ENV['MAILGUN_API_KEY'] ?? ''));
        $mailgunDomain = trim((string) ($_ENV['MAILGUN_DOMAIN'] ?? ''));

        $sent = false;
        $transport = 'php-mail';

        if ($mailgunApiKey !== '' && $mailgunDomain !== '') {
            $transport = 'mailgun';
            $sent = $this->sendViaMailgun(
                $mailgunDomain,
                $mailgunApiKey,
                $contactEmail,
                $oldInput['name'],
                $oldInput['email'],
                $oldInput['subject'],
                $messageText,
                $messageHtml
            );
        } else {
            $sent = $this->sendViaPhpMail(
                $contactEmail,
                $oldInput['name'],
                $oldInput['email'],
                $oldInput['subject'],
                $messageText
            );
        }

        if (!$sent) {
            error_log('Contact form submission failed using ' . $transport . '.');
            $_SESSION['flash_error'] = 'Your message could not be sent right now. Please try again later.';
            $this->renderContactPage([], $oldInput);
            return;
        }

        $_SESSION['flash_success'] = 'Thanks for reaching out. We have received your message and will reply soon.';
        header('Location: /contact');
        exit;
    }

    // Render the contact page with validation errors.
    private function renderContactPage(array $errors = [], array $oldInput = []): void
    {
        $currentPage = 'contact';
        require __DIR__ . '/../../views/pages/contact.php';
    }


    //Return a safe string length without depending on mbstring being present.
    private function textLength(string $value): int
    {
        return function_exists('mb_strlen') ? mb_strlen($value) : strlen($value);
    }

    // Send the message through Mailgun's HTTP API.
    private function sendViaMailgun(
        string $domain,
        string $apiKey,
        string $to,
        string $senderName,
        string $senderEmail,
        string $subject,
        string $textBody,
        string $htmlBody
    ): bool {
        $endpoint = 'https://api.mailgun.net/v3/' . rawurlencode($domain) . '/messages';
        $safeSenderName  = $this->sanitizeHeaderValue($senderName);
        $safeSenderEmail = $this->sanitizeHeaderValue($senderEmail);

        $payload = [
            'from'    => 'KeyForge Contact Form <no-reply@' . $domain . '>',
            'to'      => $to,
            'subject' => $subject,
            'text'    => $textBody,
            'html'    => $htmlBody,
            'h:Reply-To' => $safeSenderName . ' <' . $safeSenderEmail . '>',
        ];

        $response = $this->postFormEncoded($endpoint, $payload, 'api', $apiKey);
        return in_array($response['status'], [200, 201, 202], true);
    }

    // Send the message through PHP's mail() function.
    private function sendViaPhpMail(
        string $to,
        string $senderName,
        string $senderEmail,
        string $subject,
        string $textBody
    ): bool {
        $safeSubject     = $this->sanitizeHeaderValue($subject);
        $safeSenderName  = $this->sanitizeHeaderValue($senderName);
        $safeSenderEmail = $this->sanitizeHeaderValue($senderEmail);
        $headers = [
            'From: KeyForge Contact Form <no-reply@' . $this->getFallbackDomain() . '>',
            'Reply-To: ' . $safeSenderName . ' <' . $safeSenderEmail . '>',
            'Content-Type: text/plain; charset=UTF-8',
            'X-Mailer: PHP/' . phpversion(),
        ];

        return mail($to, $safeSubject, $textBody, implode("\r\n", $headers));
    }

    //Post a form-encoded request using cURL when available, otherwise stream context.
    // @return array{status:int,body:string}
    private function postFormEncoded(string $url, array $fields, string $username, string $password): array
    {
        $body = http_build_query($fields, '', '&');

        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            if ($ch === false) {
                return ['status' => 0, 'body' => ''];
            }

            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $body,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: application/x-www-form-urlencoded',
                    'Content-Length: ' . strlen($body),
                ],
                CURLOPT_USERPWD        => $username . ':' . $password,
                CURLOPT_TIMEOUT        => 20,
                CURLOPT_CONNECTTIMEOUT => 10,
            ]);

            $responseBody = curl_exec($ch);
            $statusCode    = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
            curl_close($ch);

            return [
                'status' => $statusCode,
                'body'   => is_string($responseBody) ? $responseBody : '',
            ];
        }

        $context = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => implode("\r\n", [
                    'Content-Type: application/x-www-form-urlencoded',
                    'Authorization: Basic ' . base64_encode($username . ':' . $password),
                    'Content-Length: ' . strlen($body),
                ]),
                'content' => $body,
                'timeout' => 20,
            ],
        ]);

        $responseHeaders = [];
        $statusCode   = 0;

        $stream = @fopen($url, 'rb', false, $context);
        if ($stream !== false) {
            $meta = stream_get_meta_data($stream);
            $wrapperData = $meta['wrapper_data'] ?? [];
            if (is_array($wrapperData)) {
                $responseHeaders = $wrapperData;
            }

            $responseBody = stream_get_contents($stream);
            fclose($stream);
        } else {
            $responseBody = '';
        }

        if (!empty($responseHeaders)) {
            foreach ($responseHeaders as $headerLine) {
                if (preg_match('/^HTTP\/\S+\s+(\d{3})\b/', $headerLine, $matches)) {
                    $statusCode = (int) $matches[1];
                    break;
                }
            }
        }

        return [
            'status' => $statusCode,
            'body'   => is_string($responseBody) ? $responseBody : '',
        ];
    }

    // Build a plain-text email body for the recipient.
    private function buildPlainTextMessage(array $oldInput): string
    {
        return implode("\n", [
            'New contact form submission from KeyForge',
            '',
            'Name: ' . $oldInput['name'],
            'Email: ' . $oldInput['email'],
            'Subject: ' . $oldInput['subject'],
            '',
            'Message:',
            $oldInput['message'],
            '',
        ]);
    }

    // Build a safe HTML email body for Mailgun.
    private function buildHtmlMessage(array $oldInput): string
    {
        $name = escapeHtml($oldInput['name']);
        $email = escapeHtml($oldInput['email']);
        $subject = escapeHtml($oldInput['subject']);
        $message = nl2br(escapeHtml($oldInput['message']));

        return '<!doctype html><html><body>'
            . '<h2>New contact form submission from KeyForge</h2>'
            . '<p><strong>Name:</strong> ' . $name . '<br>'
            . '<strong>Email:</strong> ' . $email . '<br>'
            . '<strong>Subject:</strong> ' . $subject . '</p>'
            . '<h3>Message</h3>'
            . '<p>' . $message . '</p>'
            . '</body></html>';
    }

    // Avoid unsafe header characters in the PHP mail() fallback.
    private function sanitizeHeaderValue(string $value): string
    {
        return trim(preg_replace('/[\r\n]+/', ' ', $value) ?? $value);
    }

    // Provide a stable fallback domain for PHP mail headers when Mailgun is unavailable.
     
    private function getFallbackDomain(): string
    {
        $appUrl = trim((string) ($_ENV['APP_URL'] ?? 'keyforge.example'));
        $host = parse_url($appUrl, PHP_URL_HOST);
        if (is_string($host) && $host !== '') {
            return $host;
        }

        return 'keyforge.example';
    }
}
