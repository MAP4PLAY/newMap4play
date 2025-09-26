<?php

// Configurações do banco de dados
$servername = "localhost";
$username = "root";
$password = ""; // A sua senha, se você tiver definido uma
$dbname = "projeto_inclusao";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

?>