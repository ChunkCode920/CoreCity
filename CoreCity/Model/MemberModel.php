<?php

// Start the session at the very top of the file to access session variables
if (session_status() == PHP_SESSION_NONE) {
    session_start();  // Start session if it's not already started
}

// Include the database connection
require_once 'C:\xampp\htdocs\CoreCity\conn.php';

class MemberModel
{
    private $appointments_table = "appointments";
    private $users_table = "users"; // Table with staff members
    private $staff_table = "staff";
    private $admin_table = "admin";
    private $reports_table = 'reports';
    private $conn;

    // Constructor to initialize PDO connection
    public function __construct()
    {
        // Use global to access the $pdo object defined outside the class
        global $pdo;
        $this->conn = $pdo;  // Access the global $pdo variable from conn.php
    }

    // Method to get all users
    public function getAllUsers() {
        $query = "SELECT * FROM " . $this->users_table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllStaff() {
        $query = "SELECT * FROM " . $this->staff_table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to get appointments for the logged-in user
    public function getUserAppointments() {
        if (isset($_SESSION['user_id'])) {
            $role = strtolower(trim($_SESSION['role']));
            if($role === 'user') {
                $user_id = $_SESSION['user_id'];
    
            // Debugging: Check the user_id
            // Updated query to fetch staff full_name and other details
                $query = "SELECT a.appointment_id, a.appointment_date, a.status, s.fullname AS staff_name
                        FROM ". $this->appointments_table . " a
                        JOIN " . $this->staff_table ." s ON a.staff_id = s.staff_id  -- Join with the staff table to get the staff name
                        WHERE a.user_id = :user_id
                        AND (a.status = 'confirmed' OR a.status = 'cancelled')  -- Only get confirmed appointments
                        ORDER BY a.appointment_date ASC";
        
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();
        
                // Debugging: Output the query and number of rows
                
        
                // Fetch the appointments
                $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($appointments as &$appointment) {
                    // Create DateTime object and format as full date with 12-hour time
                    $dateTime = new DateTime($appointment['appointment_date']);
                    $appointment['appointment_date'] = $dateTime->format('F j, Y h:i A'); // Full date + 12-hour time format
                }
        
                return $appointments;
            }
            else if($role === 'staff') {
                $user_id = $_SESSION['user_id'];
    
            // Debugging: Check the user_id
            // Updated query to fetch staff full_name and other details
                $query = "SELECT a.appointment_id, a.appointment_date, a.status, u.fullname AS user_name
                      FROM " . $this->appointments_table ." a
                      JOIN ".$this->users_table ." u ON a.user_id = u.user_id
                      WHERE a.staff_id = :user_id
                      AND (a.status = 'confirmed' OR a.status = 'cancelled')
                      ORDER BY a.appointment_date ASC";
        
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();
        
                // Debugging: Output the query and number of rows
                
        
                // Fetch the appointments
                $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($appointments as &$appointment) {
                    // Create DateTime object and format as full date with 12-hour time
                    $dateTime = new DateTime($appointment['appointment_date']);
                    $appointment['appointment_date'] = $dateTime->format('F j, Y h:i A'); // Full date + 12-hour time format
                }
        
                // Debugging: Output the appointments data
                //var_dump($appointments);
        
                return $appointments;

            }
            
        } else {
            return [];
        }
    }

    public function creationDate($user_id) { // gets the date of account creation
        $role = strtolower(trim($_SESSION['role']));

        if($role === 'user') {
            $query = "SELECT joined_on FROM ". $this->users_table . " WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        else if ($role === 'staff') {
            $query = "SELECT joined_on FROM " . $this->staff_table ." WHERE staff_id = :staff_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':staff_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        else if ($role === 'admin') {
            $query = "SELECT joined_on FROM " . $this->admin_table ." WHERE admin_id = :admin_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':admin_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
       


    }

    public function getClosestAppointment($user_id) {
        $role = strtolower(trim($_SESSION['role']));

        if($role === 'user'){
            $query = "SELECT appointment_date
                  FROM ". $this->appointments_table ."
                  WHERE user_id = :user_id AND status = 'confirmed'
                  ORDER BY appointment_date ASC
                  LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            
            // Return the first appointment if found, otherwise null
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        else if($role === 'staff'){
            $query = "SELECT appointment_date
                     FROM ". $this->appointments_table . "
                     WHERE staff_id = :staff_id AND status = 'confirmed'
                     ORDER BY appointment_date ASC
                     LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':staff_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
    }
    
    public function getFullName($role) {
        // Trim and convert role to lowercase to handle any unwanted spaces or capitalization
        $role = strtolower(trim($_SESSION['role']));  // Ensure it's in lowercase
        // Check the role and fetch the corresponding user ID
        if ($role === 'user') {
            $user_id = $_SESSION['user_id'];
            
            
            // Query to fetch full name of user
            $query = 'SELECT fullname FROM '. $this->users_table . ' WHERE user_id = :user_id';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
    
            // Fetch the result
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            // Return the result
            return $result;
        } 
        elseif ($role === 'staff') {
            $staff_id = $_SESSION['user_id'];
            $query = 'SELECT fullname FROM '. $this->staff_table . ' WHERE staff_id = :staff_id';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':staff_id', $staff_id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
            // You can add the staff query here
        } 
        elseif ($role === 'admin') {
            $admin_id = $_SESSION['user_id'];
            
            $query = 'SELECT fullname FROM '. $this->admin_table . ' WHERE admin_id = :admin_id';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
            
            
        } 
        else {
            echo 'No valid role found or role mismatch'; // Debugging else case
        }
    }
    
    public function userAppointmentByID($id) {
        

        $query ="SELECT 
                a.appointment_id, 
                s.fullname AS staff_name, 
                a.appointment_date, 
                a.status 
                FROM 
                    ". $this->appointments_table ." a
                JOIN 
                    ". $this->staff_table . " s ON a.staff_id = s.staff_id
                WHERE 
                    a.appointment_id = :appointment_id";
        

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':appointment_id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function staffAppointmentByID($id){
        $query = "SELECT
                  a.appointment_id,
                  u.fullname AS user_name,
                  a.appointment_date,
                  a.status
                  FROM
                    ". $this->appointments_table . " a
                  JOIN
                    ". $this->users_table . " u ON a.user_id = u.user_id
                  WHERE
                    a.appointment_id = :appointment_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':appointment_id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // function to cancel appointment - User Use
    public function cancelAppointment($id) {
        $query = "UPDATE " . $this->appointments_table . " SET status = 'cancelled' WHERE appointment_id = :appointment_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':appointment_id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    //function to delete appointments - Staff Use
    public function deleteAppointment($id){
        $query = "DELETE FROM ". $this->appointments_table . " WHERE appointment_id = :appointment_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':appointment_id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }


    public function totalReports() {
        $query = "SELECT COUNT(*) AS total_reports FROM " . $this->reports_table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function viewReports() {
        $query = "SELECT r.report_id, r.user_id, r.created_at, r.message, u.fullname
                 FROM " . $this->reports_table . " AS r
                 JOIN users AS u ON r.user_id = u.user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function viewIndividualReport($id) {
        $query = "SELECT r.user_id, r.created_at, r.message, u.fullname
                 FROM " . $this->reports_table . " AS r
                 JOIN users AS u ON r.user_id = u.user_id
                 WHERE report_id = :report_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":report_id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteReport($id) {
        $query = "DELETE FROM " . $this->reports_table . " WHERE report_id = :report_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":report_id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function viewUser($id) {
        $query = "SELECT user_id, fullname, joined_on, email
                FROM " . $this->users_table . " WHERE user_id =:user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Debugging: Check if the result is empty
        if (!$user) {
            echo "No user found with ID: $id"; // This will show if no user is found
            return null;
        }

        // Optionally log the result for debuggin
        return $user;

    }

    public function addUser($fullname, $username, $email, $password, $pfp = null) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $joined_on = date('Y-m-d H:i:s');
        $role = 'user';

        $query = "INSERT INTO " . $this->users_table . "(fullname, username, email, password, joined_on, role, pfp)
                 VALUES (:fullname, :username, :email, :password, :joined_on, :role, :pfp)";
        
        try {
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':joined_on', $joined_on);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':pfp', $pfp);

            if($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }

    }

    public function deleteUser($id) {
        $query = "DELETE FROM " . $this->users_table . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam("user_id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    
    public function numberUsers() {
        try {
            // Prepare the SQL query to count the number of users
            $query = "SELECT COUNT(*) FROM ". $this->users_table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
    
            $result = $stmt->fetchColumn();
            return $result;

        } catch (PDOException $e) {
            // Handle any exceptions (errors) here
            echo "Error: " . $e->getMessage();
            return 0; // Return 0 if there is an error
        }
    }

    public function numberStaff() {
        try {
            // Prepare the SQL query to count the number of users
            $query = "SELECT COUNT(*) FROM " . $this->staff_table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
    
            $result = $stmt->fetchColumn();
            return $result;

        } catch (PDOException $e) {
            // Handle any exceptions (errors) here
            echo "Error: " . $e->getMessage();
            return 0; // Return 0 if there is an error
        }
    }

    public function viewStaff($id) {
        $query = "SELECT staff_id, fullname, joined_on, email
                FROM " . $this->staff_table . " WHERE staff_id =:staff_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":staff_id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $staff = $stmt->fetch(PDO::FETCH_ASSOC);

        // Debugging: Check if the result is empty
        if (!$staff) {
            echo "No user found with ID: $id"; // This will show if no user is found
            return null;
        }

        // Optionally log the result for debuggin
        return $staff;
    }

    public function deleteStaff($id) {
        $query = "DELETE FROM " . $this->staff_table . " WHERE staff_id = :staff_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":staff_id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function addStaff($fullname, $username, $email, $password, $pfp = null) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $joined_on = date('Y-m-d H:i:s');
        $role = 'staff';

        $query = "INSERT INTO " . $this->staff_table . "(fullname, username, email, password, joined_on, role, pfp)
                 VALUES (:fullname, :username, :email, :password, :joined_on, :role, :pfp)";
        
        try {
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':joined_on', $joined_on);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':pfp', $pfp);

            if($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function modUser($user_id, $fullname, $username, $email, $password, $pfpPath) {
        // Prepare the SQL query
        $query = "UPDATE ". $this->users_table ." SET fullname = :fullname, username = :username, email = :email, pfp = :pfp";
        
        // Only include the password in the update if it's not empty (i.e., if it's provided)
        if (!empty($password)) {
            $query .= ", password = :password";
        }

        $query .= " WHERE user_id = :user_id"; // Apply the update to the specific user

        try {
            // Prepare the statement
            $stmt = $this->conn->prepare($query);
            
            // Bind the values to the SQL query
            $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':pfp', $pfpPath, PDO::PARAM_STR);  // Use the profile picture path (can be null)

            // If the password is provided, hash it and bind it
            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password
                $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            }

            // Bind the user_id (to ensure we're updating the right user)
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            
            // Execute the statement and return true on success
            return $stmt->execute();
        } catch (PDOException $e) {
            // Handle any errors (for example, a failed connection or query)
            error_log($e->getMessage());
            return false;
        }
    }

    public function modStaff($staff_id, $fullname, $username, $email, $password, $pfpPath) {
        $query = "UPDATE ". $this->staff_table . " SET fullname = :fullname, username = :username, email = :email, pfp = :pfp";
        
        // Only include the password in the update if it's not empty (i.e., if it's provided)
        if (!empty($password)) {
            $query .= ", password = :password";
        }

        $query .= " WHERE staff_id = :staff_id"; // Apply the update to the specific user

        try {
            // Prepare the statement
            $stmt = $this->conn->prepare($query);
            
            // Bind the values to the SQL query
            $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':pfp', $pfpPath, PDO::PARAM_STR);  // Use the profile picture path (can be null)

            // If the password is provided, hash it and bind it
            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password
                $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            }

            // Bind the user_id (to ensure we're updating the right user)
            $stmt->bindParam(':staff_id', $staff_id, PDO::PARAM_INT);
            
            // Execute the statement and return true on success
            return $stmt->execute();
        } catch (PDOException $e) {
            // Handle any errors (for example, a failed connection or query)
            error_log($e->getMessage());
            return false;
        }
    }

    public function modAdmin($admin_id, $fullname, $username, $email, $password, $pfpPath) {
        $query = "UPDATE ". $this->admin_table . " SET fullname = :fullname, username = :username, email = :email, pfp = :pfp";
        
        // Only include the password in the update if it's not empty (i.e., if it's provided)
        if (!empty($password)) {
            $query .= ", password = :password";
        }
    
        $query .= " WHERE admin_id = :admin_id"; // Apply the update to the specific admin
    
        try {
            // Prepare the statement
            $stmt = $this->conn->prepare($query);
            
            // Bind the values to the SQL query
            $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':pfp', $pfpPath, PDO::PARAM_STR);  // Use the profile picture path (can be null)
    
            // If the password is provided, hash it and bind it
            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password
                $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            }
    
            // Bind the admin_id (to ensure we're updating the right admin)
            $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
            
            // Execute the statement and return true on success
            return $stmt->execute();
        } catch (PDOException $e) {
            // Handle any errors (for example, a failed connection or query)
            error_log($e->getMessage());
            return false;
        }
    }

    public function totalAppointments() {
        $query = "SELECT COUNT(*) AS total_appointments FROM appointments";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_appointments'];
    }
    

}
