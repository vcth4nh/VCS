<?php
//function db_conn()
//{
//    $servername = 'localhost';
//    $username = 'vcth4nh';
//    $password = 'vcth4nh';
//    $db = 'prog5';
//    $conn = new mysqli($servername, $username, $password, $db);
//    if ($conn->connect_error) {
//        die ("Cannot connect to database");
//    }
//    return $conn;
//}
//
//
//$username = $_POST['username'];
//$password = $_POST['password'];
//if (empty($username) or empty($password)) {
//    die("Need non-empty username and password<br>");
//};
//
//$conn = db_conn();
//$sql = "SELECT username, password, name, teacher FROM users WHERE username='$username' and password='$password'";
//$result = $conn->query($sql);
//
//if ($result->num_rows > 0) {
//    $result = $result->fetch_array(MYSQLI_ASSOC);
//    printf("Hello %s\n", $result["name"]);
//} else {
//    echo "No $username and $password<br>";
//}
//
//$conn->close();
