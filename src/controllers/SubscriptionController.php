<?php
/**
 * src/controllers/SubscriptionController.php
 *
 * Handles the footer newsletter subscription form.
 *
 * Routed from public/index.php:
 *   POST /subscribe -> subscribe()
 */

declare(strict_types=1);

require_once __DIR__ . '/../helpers/csrf.php';
require_once __DIR__ . '/../helpers/sanitize.php';

class SubscriptionController
{
    public function __construct(?PDO $pdo)
    {
        // Newsletter submission does not require DB access.
    }

    public function subscribe(): void
    {
        verifyCsrf();

        $subscriberEmail = sanitizeEmail((string) ($_POST['email'] ?? ''));
        if (!filter_var($subscriberEmail, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_error'] = 'Please enter a valid email address.';
            $this->redirectBack();
        }

        $recipientEmail = trim((string) ($_ENV['NEWSLETTER_TO_EMAIL'] ?? $_ENV['CONTACT_TO_EMAIL'] ?? ''));
        if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_error'] = 'Subscription is not configured yet. Please set NEWSLETTER_TO_EMAIL (or CONTACT_TO_EMAIL) in .env.';
            $this->redirectBack();
        }

        $subject = 'New newsletter subscription - KeyForge';
        $body = "New newsletter subscription received.\n\n"
            . "Subscriber email: {$subscriberEmail}\n"
            . 'Submitted at: ' . date('Y-m-d H:i:s') . "\n";

        $sent = $this->sendSubscriptionEmail($recipientEmail, $subject, $body);
        if (!$sent) {
            error_log('Newsletter subscription email send failed.');
        }

        $_SESSION['flash_success'] = 'Subscribed successfully!';
        $this->redirectBack();
    }

    private function sendSubscriptionEmail(string $to, string $subject, string $body): bool
    {
        $mailgunApiKey = trim((string) ($_ENV['MAILGUN_API_KEY'] ?? ''));
        $mailgunDomain = trim((string) ($_ENV['MAILGUN_DOMAIN'] ?? ''));

        if ($mailgunApiKey !== '' && $mailgunDomain !== '') {
            return $this->sendViaMailgun($mailgunDomain, $mailgunApiKey, $to, $subject, $body);
        }

        $headers = [
            'From: KeyForge Newsletter <no-reply@' . $this->getFallbackDomain() . '>',
            'Content-Type: text/plain; charset=UTF-8',
            'X-Mailer: PHP/' . phpversion(),
        ];

        return mail($to, $subject, $body, implode("\r\n", $headers));
    }

    private function sendViaMailgun(
        string $domain,
        string $apiKey,
        string $to,
        string $subject,
        string $body
    ): bool {
        $endpoint = 'https://api.mailgun.net/v3/' . rawurlencode($domain) . '/messages';
        $payload = [
            'from' => 'KeyForge Newsletter <no-reply@' . $domain . '>',
            'to' => $to,
            'subject' => $subject,
            'text' => $body,
        ];

        $response = $this->postFormEncoded($endpoint, $payload, 'api', $apiKey);
        return in_array($response['status'], [200, 201, 202], true);
    }

    /**
     * @return array{status:int,body:string}
     */
    private function postFormEncoded(string $url, array $fields, string $username, string $password): array
    {
        $body = http_build_query($fields, '', '&');

        if (!function_exists('curl_init') && !filter_var((string) ini_get('allow_url_fopen'), FILTER_VALIDATE_BOOL)) {
            return [
                'status' => 0,
                'body' => 'No HTTP transport available: enable php-curl or allow_url_fopen.',
            ];
        }

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
            $statusCode = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

            if ($responseBody === false) {
                $responseBody = 'cURL error: ' . curl_error($ch);
            }

            curl_close($ch);

            return [
                'status' => $statusCode,
                'body' => is_string($responseBody) ? $responseBody : '',
            ];
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => implode("\r\n", [
                    'Content-Type: application/x-www-form-urlencoded',
                    'Authorization: Basic ' . base64_encode($username . ':' . $password),
                    'Content-Length: ' . strlen($body),
                ]),
                'content' => $body,
                'timeout' => 20,
                'ignore_errors' => true,
            ],
        ]);

        $responseHeaders = [];
        $statusCode = 0;

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
            $lastError = error_get_last();
            if (!empty($lastError['message'])) {
                $responseBody = 'stream error: ' . $lastError['message'];
                if (preg_match('/HTTP\/\S+\s+(\d{3})\b/', $lastError['message'], $matches)) {
                    $statusCode = (int) $matches[1];
                }
            }
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
            'body' => is_string($responseBody) ? $responseBody : '',
        ];
    }

    private function getFallbackDomain(): string
    {
        $appUrl = trim((string) ($_ENV['APP_URL'] ?? 'keyforge.example'));
        $host = parse_url($appUrl, PHP_URL_HOST);
        if (is_string($host) && $host !== '') {
            return $host;
        }

        return 'keyforge.example';
    }

    private function redirectBack(): void
    {
        $target = '/';
        $referer = (string) ($_SERVER['HTTP_REFERER'] ?? '');
        if ($referer !== '') {
            $path = parse_url($referer, PHP_URL_PATH);
            $query = parse_url($referer, PHP_URL_QUERY);
            if (is_string($path) && str_starts_with($path, '/')) {
                $target = $path;
                if (is_string($query) && $query !== '') {
                    $target .= '?' . $query;
                }
            }
        }

        header('Location: ' . $target);
        exit;
    }
}
