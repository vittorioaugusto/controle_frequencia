<?php

$host = 'localhost:3307';
$usuario = 'root';
$senha = '';
$database = "controle_frequencia";

$conexao = mysqli_connect($host, $usuario, $senha, $database);

if (!$conexao) {
    die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
}
?>

