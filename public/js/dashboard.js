// Dashboard — Progress ring animation
document.addEventListener('DOMContentLoaded', function() {
    var ring = document.getElementById('progressRing');
    var percentEl = document.getElementById('ringPercent');
    if (!ring || !percentEl) return;

    var percent = parseInt(ring.getAttribute('data-percent')) || 0;
    var circumference = 2 * Math.PI * 60; // r=60
    var offset = circumference - (percent / 100) * circumference;

    setTimeout(function() {
        ring.style.strokeDashoffset = offset;

        // Animate number
        var current = 0;
        var step = percent / 40;
        var counter = setInterval(function() {
            current += step;
            if (current >= percent) {
                current = percent;
                clearInterval(counter);
            }
            percentEl.textContent = Math.round(current) + '%';
        }, 25);
    }, 400);
});
