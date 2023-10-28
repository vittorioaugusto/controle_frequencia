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
    $turno = $_POST['turno'];

    // Defina o valor de presença com base no turno escolhido
    switch ($turno) {
        case 'Manhã':
            $limitePresencaInicio = '07:00:00';
            $limitePresencaFim = '12:00:00';
            break;
        case 'Tarde':
            $limitePresencaInicio = '13:00:00';
            $limitePresencaFim = '17:00:00';
            break;
        case 'Noite':
            $limitePresencaInicio = '18:00:00';
            $limitePresencaFim = '21:00:00';
            break;
        case 'Integral':
            $limitePresencaInicio = '07:00:00';
            $limitePresencaFim = '00:00:00';
            break;
        default:
            $limitePresencaInicio = '00:00:00';
            $limitePresencaFim = '00:00:00';
    }

    // Verifique se a hora da presença está dentro dos limites
    $horaPresenca = strtotime($hora);
    $limiteInicio = strtotime($limitePresencaInicio);
    $limiteFim = strtotime($limitePresencaFim);

    if ($horaPresenca >= $limiteInicio && $horaPresenca <= $limiteFim) {
        $presenca = 'Presente';
    } else {
        $presenca = 'Ausente';
    }

    $nome = $_SESSION['nome'];
    $tipo_usuario = $_SESSION['tipo_usuario'];

    // Insira os dados de frequência no banco de dados
    $query = "INSERT INTO frequencia (nome, tipo_usuario, dia, hora, turno, presenca) VALUES ('$nome', '$tipo_usuario', '$dia', '$hora', '$turno','$presenca')";

    if (mysqli_query($conexao, $query)) {
        // Redirecione de volta para a página principal
        header("Location: principal.php");
        exit();
    } else {
        echo "Erro ao registrar a frequência: " . mysqli_error($conexao);
    }
}
?>
