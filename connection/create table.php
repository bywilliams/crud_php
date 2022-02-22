<?php
include_once 'conn.php';


# arquivo para criação da tabela Employees ou outras
$sql = $conn->prepare("CREATE TABLE employees(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    salary INT(10) NOT NULL
) ");


// $sql = $conn->prepare("DROP TABLE employees");

$sql->execute();

$conn->close();
# fim da conexão para criação da tabela


?>