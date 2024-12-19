<?php 
     require_once './Control/MemberController.php';
     $controller = new MemberController();
     
     // Get the closest appointment for the logged-in user
     //$creation_date = $controller->getCreationDate($_SESSION['user_id']);
     $creation_date = $controller->getCreationDate($_SESSION['user_id']);
     $numberUsers = $controller->numberUsers();
     $numberStaff = $controller->numberStaff();
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
            <h3>Number of Staff</h3>
            <p class="card-value">
                <?php
                    if ($numberStaff) {
                        // If an appointment exists, display it
                        echo "" . $numberStaff;
                    } else {
                        // If no appointment is found, display a message
                        echo "No Staff";
                    }
                ?>
            </p>
        </div>
        <div class="card">
            <h3>Number of Users</h3>
            <p class="card-value"> 
                <?php
                    if ($numberUsers) {
                        // If an appointment exists, display it
                        echo "" . $numberUsers;
                    } else {
                        // If no appointment is found, display a message
                        echo "No Users";
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