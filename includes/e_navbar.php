<?php
require_once 'DBconnect.php';

session_start();

$username = $_SESSION['username'];

?>

<header class="navbar">
    <div class="logo">
        <h1><a href="index.php"><img src="images/logo.png"></a></h1>
    </div>
    <nav class="nav-links">
        <a href="e_postjob.php">Post a Job</a>
        <div class="dropdown">
            <button class="dropbtn"><?php echo htmlspecialchars($username); ?> ▾</button>
            <div class="dropdown-content">
                <a href="e_account.php">Account Details</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav>
</header>