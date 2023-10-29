<?php
session_start();
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $telefone = $_POST['telefone'];
    $tipo_usuario = $_POST['tipo_usuario'];
    $turno = $_POST['turno'];

    $query = "INSERT INTO usuarios (nome, email, senha, telefone, tipo_usuario, turno) VALUES ('$nome', '$email', '$senha', '$telefone', '$tipo_usuario', '$turno')";

    if (mysqli_query($conexao, $query)) {
        echo "<script>alert('Cadastro realizado com sucesso.'); window.location.href='login.php';</script>";
        exit();
    } else {
        echo "Erro ao cadastrar: " . mysqli_error($conexao);
    }
}
?>