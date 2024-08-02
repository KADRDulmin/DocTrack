
(function() {
    const body = document.body;
    const modeToggle = document.querySelector('.dark-light');
    const currentMode = localStorage.getItem('mode');

    // Apply the saved mode immediately, without waiting for the DOM to fully load
    if (currentMode === 'dark-mode') {
        body.classList.add('dark');
    }

    // Function to toggle dark/light mode
    function toggleMode() {
        const isDarkMode = body.classList.toggle('dark');
        localStorage.setItem('mode', isDarkMode ? 'dark-mode' : 'light-mode');
    }

    // Ensure modeToggle exists to prevent errors on pages without the toggle button
    if (modeToggle) {
        modeToggle.addEventListener('click', toggleMode);
    }

    // Optional: Extend mode toggling to other parts of the page if needed
    // Here you could add logic to toggle classes on the footer or any other components
}());

