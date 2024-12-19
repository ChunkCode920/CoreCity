<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Faculty+Glyphic&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Faculty+Glyphic&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Faculty+Glyphic&family=Oswald:wght@200..700&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="lineicons.css" />
    <link rel="icon" href="images/barbell-icon-test.png" type="image/png">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div id="wrapper">
        <?php include 'user_sidenav.php';?>

        <div id="main-content">
            <?php include 'user_aside.php';?>

            <?php
            // Determine the page based on the URL query parameter 'page'
            $page = isset($_GET['page']) ? $_GET['page'] : ''; // Default to empty string if no page is set

            // If no 'page' is set, include 'user_main.php' (the default main content)
            if ($page === '') {
                include 'user_main.php'; // Default content if no page is specified
            } else {
                // Construct the filename for the specific page (e.g., 'user_main_info.php')
                $mainFile = 'user_main_' . $page . '.php'; // e.g., 'user_main_info.php'

                // Check if the requested file exists before including it
                if (file_exists($mainFile)) {
                    include $mainFile;
                } else {
                    // Fallback to default 'user_main.php' if the requested page doesn't exist
                    include 'user_main.php';
                }
            }
            ?>
        </div>
    </div>

    <?php include 'footer.php';?>

</body>
</html>
