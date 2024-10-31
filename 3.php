<?php

$servername = "localhost";
$username = "root"; 
$password = "1112";
$dbname = "homework";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM data";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach ($data as $row) {
        foreach ($row as $key => $value) {
            echo $key . ": " . $value . "<br>";
        }
        echo "<hr>";
    }
} else {
    echo "No data found";
}

mysqli_close($conn);
?>