<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once 'DBconnect.php';

$pageRole = 'employer';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== $pageRole) {
    echo "<script>alert('You must log in first!'); window.location.href = 'index.php';</script>";
    exit;
}

$recruiter_id = $_SESSION['username'];

// Fetch jobs posted by recruiter
$jobs_query = $con->prepare("SELECT A_id, Name FROM applications WHERE R_id = ?");
$jobs_query->bind_param("s", $recruiter_id);
$jobs_query->execute();
$jobs_result = $jobs_query->get_result();

$filter_job_id = $_GET['job_id'] ?? 'all';

// Fetch candidates
if ($filter_job_id === 'all') {
    $candidates_query = $con->prepare("
        SELECT ss.S_id, ss.A_id, CONCAT(s.FName, ' ', s.LName) AS SeekerName, a.Name AS JobName,
               ss.Applied_Date, ss.Status,
               CASE WHEN EXISTS (
                   SELECT 1 FROM recruiter_shortlist rs 
                   WHERE rs.S_id = ss.S_id AND rs.A_id = ss.A_id AND rs.R_id = ?
               ) THEN 1 ELSE 0 END AS IsShortlisted
        FROM seeker_seeks ss
        JOIN seeker s ON ss.S_id = s.S_id
        JOIN applications a ON ss.A_id = a.A_id
        WHERE a.R_id = ?
    ");
    $candidates_query->bind_param("ss", $recruiter_id, $recruiter_id);
} else {
    $candidates_query = $con->prepare("
        SELECT ss.S_id, ss.A_id, CONCAT(s.FName, ' ', s.LName) AS SeekerName, a.Name AS JobName,
               ss.Applied_Date, ss.Status,
               CASE WHEN EXISTS (
                   SELECT 1 FROM recruiter_shortlist rs 
                   WHERE rs.S_id = ss.S_id AND rs.A_id = ss.A_id AND rs.R_id = ?
               ) THEN 1 ELSE 0 END AS IsShortlisted
        FROM seeker_seeks ss
        JOIN seeker s ON ss.S_id = s.S_id
        JOIN applications a ON ss.A_id = a.A_id
        WHERE a.R_id = ? AND ss.A_id = ?
    ");
    $candidates_query->bind_param("ssi", $recruiter_id, $recruiter_id, $filter_job_id);
}
$candidates_query->execute();
$candidates_result = $candidates_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <title>Applied Jobs</title>
</head>

<body>
    <?php include 'includes/e_navbar.php'; ?>
    <?php include 'includes/e_sidebar.php'; ?>

    <div class="dashboard_content">
        <h2>Applicants</h2>

        <!-- Filter Dropdown -->
        <form method="GET" action="e_applied.php" class="search-form">
            <div class="filter-container">
                <select id="jobFilter" name="job_id" class="search-select">
                    <option value="all" <?= $filter_job_id === 'all' ? 'selected' : '' ?>>All Jobs</option>
                    <?php while ($job = $jobs_result->fetch_assoc()) : ?>
                        <option value="<?= htmlspecialchars($job['A_id']) ?>"
                            <?= $filter_job_id == $job['A_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($job['Name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="filter-button">Filter</button>
        </form>

        <!-- Applicants Table -->
        <table class="shortlisted-candidates-list">
            <thead>
                <tr>
                    <th>Job Name</th>
                    <th>Candidate Name</th>
                    <th>Status</th>
                    <th>Shortlist</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($candidate = $candidates_result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($candidate['JobName']) ?></td>
                        <td><?= htmlspecialchars($candidate['SeekerName']) ?></td>
                        <td>
                            <?php
                            if ($candidate['Status'] == 0) {
                                echo 'Rejected';
                            }elseif ($candidate['Status'] == 1){
                                echo 'Accepted';
                            } else {
                                echo 'Shortlisted';
                                
                            }
                            ?>
                        </td>

                        <td>
                            <?php if ($candidate['IsShortlisted']): ?>
                                <button class="applied-button" disabled>Shortlisted</button>
                            <?php else: ?>
                                <a href="e_shortlist.php?A_id=<?= $candidate['A_id'] ?>&S_id=<?= $candidate['S_id'] ?>"
                                class="status accepted">Shortlist</a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($candidate['IsShortlisted']): ?>
                                <a href="e_applied_remove.php?A_id=<?= $candidate['A_id'] ?>&S_id=<?= $candidate['S_id'] ?>" 
                                class="status rejected">Remove</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<?php
$jobs_query->close();
$candidates_query->close();
$con->close();
?>
