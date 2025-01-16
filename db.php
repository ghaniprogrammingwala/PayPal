<?php
$host = 'localhost';
$db = 'dbpbnqjaaiwhsm';
$user = 'ugyghcexa9kjp';
$password = 'j9tirljh68jr';

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>
