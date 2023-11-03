<?php
session_start();
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];
    $tipo_usuario = $_POST['tipo_usuario'];
    $turno = $_POST['turno'];

    // Verificar se o CPF tem exatamente 11 números
    if (strlen($cpf) === 11) {
        // Verificar se o cadastro já existe no banco de dados
        $check_query = "SELECT * FROM usuarios WHERE cpf = '$cpf'";
        $check_result = mysqli_query($conexao, $check_query);

        if (mysqli_num_rows($check_result) == 0) {
            // O cadastro não existe, então podemos inserir os dados
            $insert_query = "INSERT INTO usuarios (nome, email, senha, cpf, telefone, tipo_usuario, turno) VALUES ('$nome', '$email', '$senha', '$cpf', '$telefone', '$tipo_usuario', '$turno')";

            if (mysqli_query($conexao, $insert_query)) {
                echo "<script>alert('Cadastro realizado com sucesso.'); window.location.href='login.php';</script>";
                exit();
            } else {
                echo "Erro ao cadastrar: " . mysqli_error($conexao);
            }
        } else {
            echo "Erro ao cadastrar: O CPF já está em uso.";
        }
    } else {
        echo "Erro ao cadastrar: CPF deve ter exatamente 11 números.";
    }
}
?>
