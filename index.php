<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once 'DBconnect.php'; // use $con


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="index-style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <title>Employify</title>
</head>

<body>
    <!-- Header -->
    <header class="navbar">
        <div class="logo">
            <h1><a href="index.php"><img src="images/logo.png" alt="Employify Logo"></a></h1>
        </div>
        <nav class="navbar-links">
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">Contact</a></li>
                <?php if (isset($_SESSION['username']) && isset($_SESSION['role'])): ?>
                    <?php if ($_SESSION['role'] === 'job_seeker'): ?>
                        <li><a href="js_dashboard.php">Dashboard</a></li>
                    <?php elseif ($_SESSION['role'] === 'employer'): ?>
                        <li><a href="e_dashboard.php">Dashboard</a></li>
                    <?php elseif ($_SESSION['role'] === 'admin'): ?>
                        <li><a href="admin_panel.php">Admin Panel</a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li><a href="#loginModal">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>

    </header>

    <!-- Home Section -->
    <div class="index-container" id="home">
        <div class="home-bg">
            <img src="images/bg.jpg" alt="Home Background">
            <div class="body-section">
                <h1>DON'T MISS THE JOB OF YOUR DREAMS!</h1>
                <p>Your Next Step Starts Here—For Dream Jobs and Star Employees.<br><strong>Just one click
                        away!</strong></p>
                <div class="account-selection">
                    <a href="#registerJsPopup" class="option-button">Find a Job</a>
                    <a href="#registerEmployerPopup" class="option-button">Find a Candidate</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Popup -->
    <div id="loginModal" class="popup">
        <div class="popup-content">
            <a href="#" class="close-btn">&times;</a>
            <h4 style="margin-bottom: 10px;">Login</h4>
            <form action="login.php" method="POST">
                <input type="email" name="username" placeholder="Username" required title="Email">
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
                <div class="password-links">
                    <a href="#forgotPasswordPopup">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Forgot Password Popup -->
    <div id="forgotPasswordPopup" class="popup">
        <div class="popup-content">
            <a href="#" class="close-btn">&times;</a>
            <h4 style="margin-bottom: 10px;">Reset Password</h4>
            <form action="forgot_password.php" method="POST">
                <input type="email" name="email" placeholder="Enter your Email" required>
                <input type="password" name="new_password" placeholder="New Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <input type="date" name="dob" placeholder="Date of Birth" required>
                <button type="submit" name="reset_password">Reset Password</button>
            </form>
        </div>
    </div>

    <!-- Registration Popup Job Seeker -->
    <div id="registerJsPopup" class="popup">
        <div class="popup-content">
            <a href="#" class="close-btn">&times;</a>
            <h4 style="margin-bottom: 10px;">Register</h4>
            <form action="js_register.php" method="POST">
                <input type="text" name="s_id" placeholder="Username" required maxlength="12">
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name" required>
                <select name="gender" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="1">Male</option>
                    <option value="0">Female</option>
                </select>
                <input type="date" name="dob" placeholder="Date of Birth" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="text" name="contact_number" placeholder="Contact Number" required>
                <input type="text" name="experience" placeholder="Experience"
                    title="Please enter your experience in years">
                <input type="text" name="education" placeholder="Education"
                    title="Please enter all educational degrees earned e.g. SSC, HSC, Bachelors, Masters, PhD, etc.">
                <input type="text" name="skills" placeholder="Skills" title="Separate Skills using commas(,).">
                <button type="submit" name="register_js">Register</button>
            </form>
        </div>
    </div>
    <!-- Registration Popup Employer  -->
    <div id="registerEmployerPopup" class="popup">
        <div class="popup-content">
            <a href="#" class="close-btn">&times;</a>
            <form action="e_register.php" method="POST">
                <h4 style="margin-bottom: 10px;">Register</h4>
                <input type="text" name="r_id" placeholder="Username" required maxlength="12">
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name" required>
                <select name="gender" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="1">Male</option>
                    <option value="0">Female</option>
                </select>
                <input type="date" name="dob" placeholder="Date of Birth" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="text" name="contact_number" placeholder="Contact Number" required>
                <input type="text" name="company_name" placeholder="Company Name" required>
                <input type="text" name="company_description" placeholder="Company Description">
                <button type="submit" name="e_register">Register</button>
            </form>
        </div>
    </div>

    <script>
        // Close popups when clicking outside
        window.onclick = function(event) {
            const modals = ['loginModal', 'registerJsPopup', 'registerEmployerPopup', 'forgotPasswordPopup'];
            modals.forEach((id) => {
                const modal = document.getElementById(id);
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            });
        };

        // Function to open popups when links are clicked
        document.querySelectorAll('a[href^="#"]').forEach((link) => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const modal = document.getElementById(targetId);
                if (modal) {
                    modal.style.display = 'flex';
                }
            });
        });

        // Close popup when close button is clicked
        document.querySelectorAll('.close-btn').forEach((btn) => {
            btn.addEventListener('click', function(event) {
                event.preventDefault();
                const popup = this.closest('.popup');
                if (popup) {
                    popup.style.display = 'none';
                }
            });
        });
    </script>

    <h5>Explore</h5>
    <div class="feature-section-container" id="about">
        <div class="image-grid">
            <div class="image-item">
                <img src="images/about1.jpg" alt="Team Group">
            </div>
            <div class="image-item">
                <img src="images/about2.jpg" alt="Business Meeting">
            </div>
            <div class="image-item">
                <img src="images/about3.jpg" alt="Team Discussion">
            </div>
            <div class="image-item">
                <img src="images/about4.jpg" alt="Team Discussion">
            </div>
        </div>
        <div class="content" id="about">
            <h2>We Help To Get The Best Job And Find A Talent</h2>
            <p>Find your next opportunity with ease on <strong> Employify!</strong><br>
                Explore a wide range of job listings across various industries,
                connect with top employers, and apply in just a few clicks.
            </p>
            <ul id="services">
                <ul><i class="material-icons">check_circle</i> Search your preferred Jobs</ul>
                <ul><i class="material-icons">check_circle</i> Easy to Manage Jobs</ul>
                <ul><i class="material-icons">check_circle</i> Top Careers</ul>
                <ul><i class="material-icons">check_circle</i> Search Expert Candidates</ul>
            </ul>
        </div>
    </div>
    <div class="contact-sections" id="contact">
        <h5>Contact For Any Query</h5>
        <div class="contact-info">
            <div class="info-box">
                <span>📍</span>
                <p>Dhaka, Bangladesh</p>
            </div>
            <div class="info-box">
                <span>✉️</span>
                <p>info@employify.com</p>
            </div>
            <div class="info-box">
                <span>📞</span>
                <p>+012 345 6789</p>
            </div>
        </div>
        <div class="contact-container">
            <div class="map">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d233668.38770849848!2d90.27923786457569!3d23.780573254408353!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b854f05aa3a7%3A0x9e3794534b1dcfed!2sDhaka%2C%20Bangladesh!5e0!3m2!1sen!2sus!4v1625403282176!5m2!1sen!2sus"
                    width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>
            <div class="form-container">
                <form action="feedback.php" method="POST">
                    <div class="form-group">
                        <input type="text" placeholder="Your Name" required name="name">
                        <input type="email" placeholder="Your Email" required name="email">
                        <input type="text" placeholder="Subject" required name="subject">
                    </div>
                    <textarea placeholder="Message" rows="5" name="message"></textarea>
                    <button type="submit" class="btn-primary" name="feedback">Send Message</button>
                </form>
            </div>
        </div>
    </div>
    <footer>
        <p>&copy; 2025 Employify. All Rights Reserved.</p>
    </footer>

</body>

</html>