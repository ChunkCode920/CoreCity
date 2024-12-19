<?php
// Include the database connection file
include('conn.php');

// Step 2: Fetch the users data from the database
$sql = "SELECT user_id, username, fullname, email, joined_on FROM Users";
$stmt = $pdo->prepare($sql);
$stmt->execute();

?>

<div class="content">
    <h1>USERS LIST</h1>
    <div class="card-container">
        <table border="1" class="users-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Account Creation</th>
                    <th>Actions</th> <!-- Added column for CRUD buttons -->
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through each user and display their data in table rows
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['fullname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['joined_on']) . "</td>";
                    echo "<td>
                        <a href='edit.php?user_id=" . $row['user_id'] . "'>Edit</a> | 
                        <a href='delete.php?user_id=" . $row['user_id'] . "' onclick='return confirm(\"Are you sure you want to delete this user?\");'>Delete</a>
                    </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
// Close the database connection after the script ends
$pdo = null;
?>
