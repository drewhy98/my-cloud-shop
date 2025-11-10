<?php
// Database connection settings (same as register)
$serverName = "tcp:drewcardiffmet.database.windows.net,1433";
$connectionOptions = array(
    "Database" => "myDatabase",
    "Uid" => "myadmin",
    "PWD" => "Abcdefgh0!",
    "Encrypt" => 1,
    "TrustServerCertificate" => 0
);

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($email) || empty($password)) {
        header("Location: login.php?error=" . urlencode("Please fill in all fields."));
        exit();
    }

    // Connect to SQL Server
    $conn = sqlsrv_connect($serverName, $connectionOptions);

    if (!$conn) {
        $errors = sqlsrv_errors();
        $msg = "Database connection failed: ";
        if ($errors != null) {
            foreach ($errors as $error) {
                $msg .= $error['message'];
            }
        }
        header("Location: login.php?error=" . urlencode($msg));
        exit();
    }

    // Fetch user by email
    $sql = "SELECT * FROM shopusers WHERE email = ?";
    $params = array($email);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        $errors = sqlsrv_errors();
        $msg = "Query error: ";
        if ($errors != null) {
            foreach ($errors as $error) {
                $msg .= $error['message'];
            }
        }
        header("Location: login.php?error=" . urlencode($msg));
        exit();
    }

    $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($user) {
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Start session
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            // Redirect to home (or dashboard)
            header("Location: index.php");
            exit();
        } else {
            header("Location: login.php?error=" . urlencode("Incorrect password."));
            exit();
        }
    } else {
        header("Location: login.php?error=" . urlencode("No account found with that email."));
        exit();
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);

} else {
    // If accessed directly
    header("Location: login.php");
    exit();
}
?>
