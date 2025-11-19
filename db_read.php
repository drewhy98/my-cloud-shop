<?php
// Secondary Read-Only Replica
$serverName = "tcp:drewcardiffmet-replica.database.windows.net,1433";
$connectionOptions = [
    "Database" => "myDatabase",
    "Uid" => "myadmin",
    "PWD" => "Abcdefgh0!",
    "Encrypt" => 1,
    "TrustServerCertificate" => 0
];

$conn_read = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn_read) {
    die("Read DB connection failed: " . print_r(sqlsrv_errors(), true));
}
?>
