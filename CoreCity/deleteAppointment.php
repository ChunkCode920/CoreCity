<?php
require_once './Control/MemberController.php';
$error_message = '';
$success_message = '';
$name = $price = $description = '';
$appointment_id = null;
$appointment = null;

$controller = new MemberController();


if (isset($_GET['id'])) {
    $appointment_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    
    
    $appointment = $controller->staffViewAppointment($appointment_id);
    
    
    
    
    if($appointment) {
        $user_name = $appointment['user_name'];
        $appointment_date = $appointment['appointment_date'];
        $status = htmlspecialchars($appointment['status']);
    }
    
} else if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = filter_var($_POST['appointment_id'], FILTER_SANITIZE_NUMBER_INT);

    if($controller->deleteAppointment($appointment_id)) {
        $success_message = "Appointment has been successfully deleted!";
        header("Location: staff_appointments_list.php");
        exit();
    } else {
        $error_message = "Failed to delete appointment";
    }

}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete This Appointment</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="images/barbell-icon-test.png" type="image/png">
</head>

<body class="bg-light"> 
    <div class="container my-5">
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white">
                <h1 class="card-title mb-0">Appointment Deletion</h1>
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

                <?php if ($appointment): ?>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($appointment['user_name']); ?></p>
                    <p><strong>Appointment Date:</strong> <?php echo htmlspecialchars($appointment['appointment_date']); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($appointment['status']); ?></p>
                    
                <?php endif; ?>
            </div>
            <div class="card-footer text-end">
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="fas fa-trash-alt"></i> Delete
                </button>
                <a href="staff_appointments_list.php" class="btn btn-secondary">Back to Appointments</a>
            </div>
        </div>
    </div>
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this appointment?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="deleteAppointment.php" style="display: inline;">
                        <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appointment_id); ?>">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>