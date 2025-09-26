<?php

// Inclui o arquivo de conexão
include 'conexao.php';

// Comando SQL para buscar todas as quadras
$sql = "SELECT nome_quadra, descricao, link_foto FROM quadras";
$result = $conn->query($sql);

$quadras = array();

// Itera sobre os resultados e armazena em um array
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $quadras[] = $row;
    }
}

// Envia a resposta em formato JSON
header('Content-Type: application/json');
echo json_encode($quadras);

// Fecha a conexão
$conn->close();

?>