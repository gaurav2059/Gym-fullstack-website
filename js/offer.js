document.addEventListener('DOMContentLoaded', function() {
    // Select all elements with class 'overlay' (assuming you want to add countdown to each offer)
    const overlays = document.querySelectorAll('.overlay');

    // Function to update countdown timer
    function updateCountdown() {
        overlays.forEach(function(overlay) {
            // Get the end date from the DOM element
            const endDateString = overlay.querySelector('.title2').textContent;
            const endDate = new Date(endDateString).getTime(); // Parse end date
            const now = new Date().getTime();
            const timeRemaining = endDate - now;

            const daysElement = overlay.querySelector('.days');
            const hoursElement = overlay.querySelector('.hours');
            const minutesElement = overlay.querySelector('.minutes');
            const secondsElement = overlay.querySelector('.seconds');

            if (timeRemaining < 0) {
                // Handle case when countdown has expired
                daysElement.value = '0';
                hoursElement.value = '0';
                minutesElement.value = '0';
                secondsElement.value = '0';
                daysElement.nextElementSibling.textContent = 'EXPIRED';
                return;
            }

            // Calculate time remaining
            const days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

            // Update the DOM elements with the calculated values
            daysElement.value = days;
            hoursElement.value = hours;
            minutesElement.value = minutes;
            secondsElement.value = seconds;
        });
    }

    // Initial call to update countdown immediately
    updateCountdown();

    // Update countdown every second (1000 milliseconds)
    setInterval(updateCountdown, 1000);
});
