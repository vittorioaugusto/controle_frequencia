<?php
session_start();
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $query = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
    $result = mysqli_query($conexao, $query);

    if (mysqli_num_rows($result) == 1) {
        // Inicie a sessão para armazenar informações do usuário
        
        // Obtenha os dados do usuário
        $row = mysqli_fetch_assoc($result);
        
        // Armazene os dados do usuário na sessão
        $_SESSION['nome'] = $row['nome'];
        $_SESSION['tipo_usuario'] = $row['tipo_usuario'];

        // Redirecione para a página principal.php
        header("Location: principal.php");
        exit();
    } else {
        echo "Credenciais inválidas. Tente novamente.";
    }
}
?>
