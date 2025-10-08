<?php

/**
 * Conexão com PostgreSQL para o  MAP4PLAY
 */
include 'config.php'; // inclui as configurações

// String de conexao
$conn_string = "host={$db_config['host']} port={$db_config['port']} dbname={$db_config['dbname']} user={$db_config['user']} password={$db_config['password']}";

// tenta conectar ao banco
$conn = pg_connect($conn_string);

// verifica se a conexão foi bem-sucedida
if (!$conn) {
    die("Erro na conexão com o banco de dados: " . pg_last_error());
}

// define o encoding para utf-8
pg_set_client_encoding($conn, "UTF8");
?>