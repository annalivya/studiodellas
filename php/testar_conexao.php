<?php
$conexao = new mysqli('localhost', 'root', '', 'studio_dellas');

if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
} else {
    echo "Conexão com o banco de dados bem-sucedida!";
}
?>
