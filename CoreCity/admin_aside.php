<?php
    require_once './Control/MemberController.php';
    $controller = new MemberController();

    $totalAppointments = $controller->totalAppointments();
?>

<aside class="aside">
    <h2>MORE INFO</h2>
    <ul>
        <li>Total Appointments:
            <div class="stat"><?php echo $totalAppointments;?></div>
        </li>
    </ul>
</aside>
