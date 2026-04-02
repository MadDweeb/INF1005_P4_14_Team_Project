document.addEventListener('DOMContentLoaded', () => {
    const faqButtons = document.querySelectorAll('.faq-item button');

    faqButtons.forEach(button => {
        button.addEventListener('click', () => {
            const isExpanded = button.getAttribute('aria-expanded') === 'true';
            const answerId = button.getAttribute('aria-controls');
            const answer = document.getElementById(answerId);

            const item = button.closest('.faq-item');
            const newExpandedState = !isExpanded;

            button.setAttribute('aria-expanded', newExpandedState);
            item.classList.toggle('active', newExpandedState);
        });

    });

    if (window.location.hash) {
        const targetButton = document.querySelector(window.location.hash);
        if (targetButton) {
            targetButton.click();
            targetButton.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
});
