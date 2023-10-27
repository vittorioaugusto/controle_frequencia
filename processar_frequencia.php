<?php
session_start();
include 'conexao.php';

// Verifique se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dia = $_POST['dia'];
    $hora = $_POST['hora'];

    // Defina o valor de presenca com base na lógica do seu aplicativo
    // Suponhamos que você queira definir "Presença" se a hora atual for menor que 12:00 e "Ausente" caso contrário.
    $presenca = (strtotime($hora) < strtotime("18:00:00")) ? 'Presente' : 'Ausente';

    $nome = $_SESSION['nome'];
    $tipo_usuario = $_SESSION['tipo_usuario'];

    // Insira os dados de frequência no banco de dados
    $query = "INSERT INTO frequencia (nome, tipo_usuario, dia, hora, presenca) VALUES ('$nome', '$tipo_usuario', '$dia', '$hora', '$presenca')";

    if (mysqli_query($conexao, $query)) {
        // Redirecione de volta para a página principal
        header("Location: principal.php");
        exit();
    } else {
        echo "Erro ao registrar a frequência: " . mysqli_error($conexao);
    }
}
?>
