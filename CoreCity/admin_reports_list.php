<?php
require_once './Control/MemberController.php';

$controller = new MemberController();
$reports = $controller->viewReports(); // Fetch the reports
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaints</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link rel="icon" href="images/barbell-icon-test.png" type="image/png">

</head>

<body>
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Complaints</h1>
            <a href="admin_dashboard.php" class="btn btn-dark ms-auto">
                <i class="fas fa-home"></i> Back to Home
            </a>
        </div>
        <div class="table-responsive mt-3">
            <table id="appointmentTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Message</th>
                        <th>Report Creation Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reports as $report): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($report['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($report['message']); ?></td>
                        <td><?php echo htmlspecialchars($report['created_at']); ?></td>
                        <td>
                            <a href="resolveReport.php?id=<?php echo $report['report_id']; ?>" class="btn btn-success btn-sm">
                                <i class="fas fa-trash"></i> Resolve
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- jQuery, Bootstrap JS, and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#appointmentTable').DataTable({
            responsive: true,
            autoWidth: false,
            paging: true,
            searching: true
        });
    });
    </script>
</body>

</html>
