<?php
    if(!isset($_SESSION['role']) || ($_SESSION['role'] !== 'user')) {
        header('Location: index.php');
    }
?> 
<div class="content">
    <h1>Welcome to Core City Fitness!</h1>
    <div class="image-container">
        <img src="images/gym-background1.png" alt="pictureorsomething">
    </div>
    
    
        
    </p>
    <p>At Core City Fitness, we are more than just a gym—we are a community committed to helping 
        you achieve your fitness goals, no matter your starting point. Whether you're just beginning your fitness journey or you're a seasoned athlete, 
        our mission is to provide you with the support, motivation, and tools you need to succeed.
    <br>
    <br>
        Please consider scheduling a class with one of our many talented trainers!
    </p>
</div>