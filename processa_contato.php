<?php

// Inclui o arquivo de conexão
include 'conexao.php';

// Obtém os dados do formulário usando o método POST
$nome = $_POST['Nome'];
$celular = $_POST['Celular'];
$email = $_POST['E-mail'];
$mensagem = $_POST['Mensagem'];

// Prepara e executa o comando SQL para inserir os dados
// A tabela 'comentarios' é usada para salvar as mensagens
$sql = "INSERT INTO comentarios (nome_usuario, texto_comentario, data_comentario) 
        VALUES ('$nome', '$mensagem', NOW())";

if ($conn->query($sql) === TRUE) {
    echo "Mensagem enviada com sucesso! Redirecionando...";
    // Redireciona o usuário para a página de contato
    header('Location: contact.html'); 
    exit();
} else {
    echo "Erro ao enviar mensagem: " . $conn->error;
}

// Fecha a conexão
$conn->close();

?>