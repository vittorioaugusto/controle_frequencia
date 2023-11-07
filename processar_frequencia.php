<?php
session_start();
include 'conexao.php';

// Defina o fuso horário para o de São Paulo (ou o fuso horário apropriado)
date_default_timezone_set('America/Sao_Paulo');

// Verifique se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_SESSION['nome'];
    $tipo_usuario = $_SESSION['tipo_usuario'];

    // Obtenha o turno com base na hora atual
    $horaAtual = date('H:i:s'); // Hora atual
    $turno = '';

    // Verifique se o usuário já registrou uma entrada para o dia atual
    $query_check = "SELECT COUNT(*) as num_entradas, MAX(hora) as max_hora FROM frequencia WHERE nome = '$nome' AND dia = CURDATE()";
    $result_check = mysqli_query($conexao, $query_check);

    if ($result_check && mysqli_num_rows($result_check) > 0) {
        $row_check = mysqli_fetch_assoc($result_check);
        $num_entradas = $row_check['num_entradas'];
        $max_hora = $row_check['max_hora'];

        // Verifique se o usuário está tentando registrar horários fora de ordem
        if ($num_entradas % 2 === 1 && strtotime($horaAtual) < strtotime($max_hora)) {
            echo "Erro: Você está tentando registrar horários fora de ordem. A última entrada foi em $max_hora.";
            exit();
        }
    }

    if ($num_entradas < 2) {
        $query_turno = "SELECT turno FROM usuarios WHERE nome = '$nome'";
        $result_turno = mysqli_query($conexao, $query_turno);

        if ($result_turno && mysqli_num_rows($result_turno) > 0) {
            $row = mysqli_fetch_assoc($result_turno);
            $turno_usuario = $row['turno'];
        } else {
            echo "Erro ao recuperar o turno do usuário.";
            exit();
        }

        // Calcule o turno com base na hora
        if (strtotime($horaAtual) >= strtotime('06:00:00') && strtotime($horaAtual) < strtotime('12:59:00')) {
            $turno = 'Manhã';
        } elseif (strtotime($horaAtual) >= strtotime('13:00:00') && strtotime($horaAtual) < strtotime('17:59:00')) {
            $turno = 'Tarde';
        } elseif (strtotime($horaAtual) >= strtotime('18:00:00') && strtotime($horaAtual) <= strtotime('23:59:00')) {
            $turno = 'Noite';
        } elseif (strtotime($horaAtual) >= strtotime('00:00:00') && strtotime($horaAtual) < strtotime('06:00:00')) {
            $turno = 'Noite';
        } else {
            $turno = 'Outro';
        }

        // Defina o valor de presença com base no turno do usuário
        $presenca = ($turno === $turno_usuario) ? 'Presente' : 'Ausente';

        // Exiba o turno
        echo "Seu turno é: $turno_usuario<br>";

        // Insira os dados de frequência no banco de dados
        $query = "INSERT INTO frequencia (nome, tipo_usuario, dia, hora, turno, presenca) VALUES ('$nome', '$tipo_usuario', CURDATE(), '$horaAtual', '$turno', '$presenca')";

        if (mysqli_query($conexao, $query)) {
            // Redirecione de volta para a página principal
            header("Location: principal.php");
            exit();
        } else {
            echo "Erro ao registrar a frequência: " . mysqli_error($conexao);
        }
    } else {
        echo "Você já registrou duas entradas para o dia de hoje.";
    }
}
?>
