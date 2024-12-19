<?php
require_once './Control/MemberController.php';
$report_id = null;
$report = null;
$error_message = '';
$success_message = '';

$controller = new MemberController();

if(isset($_GET['id'])){
    $report_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    
    
    $report = $controller->viewIndividualReport($report_id);
    

    if($report) {
        $user_name = $report['fullname'];
        $message = $report['message'];
        $date = $report['created_at'];
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report_id = filter_var($_POST['report_id'], FILTER_SANITIZE_NUMBER_INT);

    if($controller->deleteReport($report_id)) {
        $success_message = "Report has been successfully deleted!";
        header("Location: admin_reports_list.php");
        exit();
    } else {
        $error_message = "Failed to delete Report";
    }
}




?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resolve This Report</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="images/barbell-icon-test.png" type="image/png">
</head>

<body class="bg-light"> 
    <div class="container my-5">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h1 class="card-title mb-0">Resolve Report</h1>
            </div>
            <div class="card-body">
                <?php if ($error_message): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php elseif ($success_message): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo htmlspecialchars($success_message); ?>
                    </div>
                <?php endif; ?>

                <?php if ($report): ?>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($report['fullname']); ?></p>
                    <p><strong>Message:</strong> <?php echo htmlspecialchars($report['message']); ?></p>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($report['created_at']); ?></p>
                    
                <?php endif; ?>
            </div>
            <div class="card-footer text-end">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="fas fa-trash-alt"></i> Resolve
                </button>
                <a href="admin_reports_list.php" class="btn btn-secondary">Back to Reports</a>
            </div>
        </div>
    </div>
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure this issue has been resolved?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="resolveReport.php" style="display: inline;">
                        <input type="hidden" name="report_id" value="<?php echo htmlspecialchars($report_id); ?>">
                        <button type="submit" class="btn btn-success">Resolve</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>