<?php 
     require_once './Control/MemberController.php';
     $controller = new MemberController();
     
     // Get the closest appointment for the logged-in user
     //$creation_date = $controller->getCreationDate($_SESSION['user_id']);
     $creation_date = $controller->getCreationDate($_SESSION['user_id']);
     $appointment_date = $controller->getClosestUserAppointment($_SESSION['user_id']);
     $name = $controller->getFullName($_SESSION['role']);
     $reports = $controller->totalReports();
 ?>



<div class="content">
    <h1>Welcome
        <?php
            echo $name['fullname'];
        ?>!
    </h1>
    <div class="card-container">
        <div class="card">
            <h3>Creation Date</h3>
            <p class="card-value">
                <?php
                    echo date('F j, Y \a\t g:i a', strtotime($creation_date['joined_on']));
                ?>
            </p>
        </div>
        <div class="card">
            <h3>Upcoming Appointment</h3>
            <p class="card-value"> 
                <?php
                    if ($appointment_date) {
                        // If an appointment exists, display it
                        echo date('F j, Y \a\t g:i a', strtotime($appointment_date['appointment_date']));
                    } else {
                        // If no appointment is found, display a message
                        echo "No Appointments Available";
                    }
                ?>
            </p>
        </div>
        <div class="card">
            <h3>Complaints</h3>
            <p class="card-value">
                <?php
                    echo "" . $reports["total_reports"];
                
                ?>
            </p>
        </div>
    </div>
</div>