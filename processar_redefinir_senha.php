<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $nova_senha = $_POST['nova_senha'];

    // Verifique se o email existe no banco de dados
    $query = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = mysqli_query($conexao, $query);

    if (mysqli_num_rows($result) == 1) {
        // Atualize a senha no banco de dados
        $query = "UPDATE usuarios SET senha = '$nova_senha' WHERE email = '$email'";
        
        if (mysqli_query($conexao, $query)) {
            echo "<script>alert('Senha redefinida com sucesso!'); window.location.href='login.php';</script>";
            exit();
        } else {
            echo "Erro ao redefinir a senha: " . mysqli_error($conexao);
        }
    } else {
        echo "Email não encontrado. Verifique o email fornecido.";
    }
}
?>
