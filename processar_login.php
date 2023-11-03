<?php
session_start();
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $query = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
    $result = mysqli_query($conexao, $query);


    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        if ($row['status'] == 1) {
                // A senha está correta, inicie a sessão para armazenar informações do usuário
                $_SESSION['nome'] = $row['nome'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['cpf'] = $row['cpf'];
                $_SESSION['telefone'] = $row['telefone'];
                $_SESSION['tipo_usuario'] = $row['tipo_usuario'];
                $_SESSION['turno'] = $row['turno'];

                // Redirecione para a página principal.php
                header("Location: principal.php");
                exit();
            }  else {
            // Usuário desativado, exiba uma mensagem de erro
            echo "Sua conta está desativada. Entre em contato com o administrador.";
        }
    } else {
        // Usuário não encontrado, exiba uma mensagem de erro
        echo "Usuário não encontrado. Verifique o email e tente novamente.";
    }

}

?>