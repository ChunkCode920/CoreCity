<?php
// Check if session is already started, if not, then start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>

<div class="content">
    <h1>Report an Issue</h1>
    <div class="report-container">
        <p>Please provide the details of your issue, and we'll get back to you as soon as possible.</p>

        <!-- Display the form for submitting a new report -->
        <form id="report-form" action="submit_report.php" method="POST">
            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="5" required placeholder="Write your report here..."></textarea>
            <button type="submit">Send Report</button>
        </form>
    </div>
</div>

<!-- Trigger JavaScript alert if there is a success or error message -->
<?php if (isset($_SESSION['report_success'])): ?>
    <script>
        alert("<?php echo $_SESSION['report_success']; ?>");
    </script>
    <?php unset($_SESSION['report_success']); // Clear the session variable after showing the alert ?>
<?php elseif (isset($_SESSION['report_error'])): ?>
    <script>
        alert("<?php echo $_SESSION['report_error']; ?>");
    </script>
    <?php unset($_SESSION['report_error']); // Clear the session variable after showing the alert ?>
<?php endif; ?>

