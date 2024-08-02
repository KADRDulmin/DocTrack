
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ===== CSS ===== -->
    <link rel="stylesheet" href="styles/header.css">

    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>

    <title>DocTrack</title>
</head>

<body>
    <nav>
        <div class="nav-bar">
            <i class='bx bx-menu sidebarOpen'></i>
            <span class="logo navLogo"><a href="index.php">DocTrack</a></span>

            <div class="menu">
                <div class="logo-toggle">
                    <span class="logo"><a href="#"><img src="imgs/Logo.png" style="width: 50%; height: auto; display: flex; align-items: center;"></a></span>
                    <i class='bx bx-x siderbarClose'></i>
                </div>

                <ul class="nav-links">
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="patientlist.php">Patients</a></li>
                    <li><a href="MyProfile.php">My Profile</a></li>
                    <li><a href="doctors_profiles.php">Doctors</a></li>

                </ul>
            </div>

            <div class="darkLight-searchBox">
                <div class="dark-light">
                    <i class='bx bx-moon moon'></i>
                    <i class='bx bx-sun sun'></i>
                </div>

                <div class="searchBox">
                    <div class="searchToggle">
                        <i class='bx bx-x cancel'></i>
                        <i class='bx bx-search search'></i>
                    </div>

                    <div class="search-field">
                        <input type="text" placeholder="Search...">
                        <i class='bx bx-search'></i>
                    </div>
                    
                </div>
                <ul class="suggestions" style="margin-top: -20px;"></ul>
            </div>
        </div>
    </nav>
    
    <script src="js/script.js"></script>

    <script>
        const body = document.querySelector("body"),
            nav = document.querySelector("nav"),
            modeToggle = document.querySelector(".dark-light"),
            searchToggle = document.querySelector(".searchToggle"),
            sidebarOpen = document.querySelector(".sidebarOpen"),
            siderbarClose = document.querySelector(".siderbarClose");
        footer = document.querySelector("footer");

        let getMode = localStorage.getItem("mode");
        if (getMode && getMode === "dark-mode") {
            body.classList.add("dark");
        }

        // js code to toggle dark and light mode
        modeToggle.addEventListener("click", () => {
            modeToggle.classList.toggle("active");
            body.classList.toggle("dark");
            footer.classList.toggle("dark");

            // js code to keep user selected mode even page refresh or file reopen
            if (!body.classList.contains("dark")) {
                localStorage.setItem("mode", "light-mode");
            } else {
                localStorage.setItem("mode", "dark-mode");
            }
            if (!footer.classList.contains("dark")) {
                localStorage.setItem("mode", "light-mode");
            } else {
                localStorage.setItem("mode", "dark-mode");
            }
        });

        // js code to toggle search box
        searchToggle.addEventListener("click", () => {
            searchToggle.classList.toggle("active");
        });


        //   js code to toggle sidebar
        sidebarOpen.addEventListener("click", () => {
            nav.classList.add("active");
        });

        body.addEventListener("click", e => {
            let clickedElm = e.target;

            if (!clickedElm.classList.contains("sidebarOpen") && !clickedElm.classList.contains("menu")) {
                nav.classList.remove("active");
            }
        });
    </script>

    <script>
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
    </script>

</body>

</html>