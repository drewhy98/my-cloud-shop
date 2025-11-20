<?php
session_start();

// Database connection settings
$serverName = "tcp:drewcardiffmet.database.windows.net,1433";
$connectionOptions = array(
    "Database" => "myDatabase",
    "Uid" => "myadmin",
    "PWD" => "Abcdefgh0!",
    "Encrypt" => 1,
    "TrustServerCertificate" => 0
);

// Form submitted?
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        header("Location: admin_login.php?error=" . urlencode("Please fill in all fields."));
        exit();
    }

    // Connect
    $conn = sqlsrv_connect($serverName, $connectionOptions);

    if (!$conn) {
        header("Location: admin_login.php?error=" . urlencode("Database connection error."));
        exit();
    }

    // Fetch admin by email
    $sql = "SELECT * FROM adminusers WHERE email = ?";
    $stmt = sqlsrv_query($conn, $sql, array($email));

    if ($stmt === false) {
        header("Location: admin_login.php?error=" . urlencode("Query failure."));
        exit();
    }

    $admin = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($admin) {

        if (password_verify($password, $admin['password'])) {

            // Create admin session
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            $_SESSION['admin_email'] = $admin['email'];

            // Redirect to admin index
            header("Location: admin_index.php");
            exit();

        } else {
            header("Location: admin_login.php?error=" . urlencode("Incorrect password."));
            exit();
        }

    } else {
        header("Location: admin_login.php?error=" . urlencode("Admin not found."));
        exit();
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);

} else {
    header("Location: admin_login.php");
    exit();
}
?>
