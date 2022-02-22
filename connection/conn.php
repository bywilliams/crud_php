<?php 

$host = 'localhost';
$user = 'root';
$db = 'company';
$psw = '';

# conexão MYSQLI
$conn = new mysqli($host, $user, $psw, $db);


if ($conn === false) {
    die("Error: não foi possível conectar. ". mysqli_error());
}