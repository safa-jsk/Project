<?php
session_start();

require_once 'DBconnect.php'; // use $con

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: search.php?message=" . urlencode("You need to log in to bookmark a job."));
    exit;
}

// Validate and fetch the job ID
if (isset($_GET['A_id']) && is_numeric($_GET['A_id'])) {
    $application_id = intval($_GET['A_id']);
    $username = $_SESSION['username'];

    // Prepared statement to check if the user has already applied
    $stmt = $con->prepare("SELECT 1 FROM seeker_bookmarks WHERE S_id = ? AND A_id = ?");
    $stmt->bind_param("si", $username, $application_id);
    $stmt->execute();
    $check_result = $stmt->get_result();

    // Check if rows are returned
    if ($check_result && $check_result->num_rows > 0) {
        $message = "You have already bookmarked this job.";
    } else {
        // Prepared statement to insert the application
        $stmt = $con->prepare("INSERT INTO seeker_bookmarks (S_id, A_id) VALUES (?, ?)");
        $stmt->bind_param("si", $username, $application_id);
        if ($stmt->execute()) {
            $message = "Application bookmarked successfully.";
        } else {
            $message = "Error bookmarking the job.";
        }
    }

    $stmt->close();
} else {
    $message = "Invalid job ID.";
}

mysqli_close($con);
header("Location: js_bookmarks.php");
exit;