/*
 * public/js/orders.js
 * Smooth order details toggle
 */

function toggleOrderDetails(orderId) {
    const detailsDiv = document.getElementById('details-' + orderId);
    const button = event.currentTarget;
    const buttonText = button.querySelector('.expand-text');
    const orderBox = detailsDiv.closest('.order-box');
    
    // Check if already expanded
    const isExpanded = detailsDiv.classList.contains('expanded');
    
    if (isExpanded) {
        // Collapse
        detailsDiv.classList.remove('expanded');
        button.classList.remove('active');
        buttonText.textContent = 'View Details';
        
        // Smooth scroll back to order card top
        setTimeout(() => {
            orderBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }, 100);
    } else {
        // Expand
        detailsDiv.classList.add('expanded');
        button.classList.add('active');
        buttonText.textContent = 'Hide Details';
        
        // Smooth scroll to show expanded content
        setTimeout(() => {
            detailsDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }, 300);
    }
}

// Add smooth entrance animation on page load
document.addEventListener('DOMContentLoaded', function() {
    const orderBoxes = document.querySelectorAll('.order-box');
    
    orderBoxes.forEach((box, index) => {
        box.style.opacity = '0';
        box.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            box.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            box.style.opacity = '1';
            box.style.transform = 'translateY(0)';
        }, index * 150);
    });
});