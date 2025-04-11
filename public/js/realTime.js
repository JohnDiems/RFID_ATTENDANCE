// DATE TIME YEAR CODE //
function updateDateTime() {
    const TimeElement = document.getElementById('time');
    const dateElement = document.getElementById('date');
    const now = new Date();
    const options = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    };

    const dateFormatted = now.toLocaleDateString(undefined, options);
    const timeOptions = {
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric',
        hour12: true
    };

    const timeFormatted = now.toLocaleTimeString(undefined, timeOptions);
    dateElement.textContent = `${dateFormatted}`;
    TimeElement.textContent = ` ${timeFormatted}`;
}

updateDateTime();

// Update date and time every second
setInterval(updateDateTime, 1000);
