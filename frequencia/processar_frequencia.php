<?php
session_start();
include '../SQL/conexao.php';

// Defina o fuso horário para o de São Paulo (ou o fuso horário apropriado)
date_default_timezone_set('America/Sao_Paulo');

// Verifique se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: ../login/login.php");
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

    $query_turno = "SELECT turno FROM usuario WHERE nome = '$nome'";
    $result_turno = mysqli_query($conexao, $query_turno);

    if ($result_turno && mysqli_num_rows($result_turno) > 0) {
        $row = mysqli_fetch_assoc($result_turno);
        $turno_usuario = $row['turno'];
    } else {
        echo "Erro ao recuperar o turno do usuário.";
        exit();
    }

    // Calcule o turno com base na hora
    if (strtotime($horaAtual) >= strtotime('06:00:00') && strtotime($horaAtual) < strtotime('12:59:59')) {
        $turno = 'Manhã';
    } elseif (strtotime($horaAtual) >= strtotime('13:00:00') && strtotime($horaAtual) < strtotime('17:59:59')) {
        $turno = 'Tarde';
    } elseif (strtotime($horaAtual) >= strtotime('18:00:00') && strtotime($horaAtual) <= strtotime('23:59:59')) {
        $turno = 'Noite';
    } elseif (strtotime($horaAtual) >= strtotime('00:00:00') && strtotime($horaAtual) < strtotime('05:59:59')) {
        $turno = 'Integral';
    } else {
        $turno = 'Outro';
    }

    // Se o usuário é do turno da manhã, mantenha o turno como manhã, independentemente da hora
    if ($turno_usuario === 'Manhã') {
        $turno = 'Manhã';
    } elseif ($turno_usuario === 'Tarde') {
        $turno = 'Tarde';
    } elseif ($turno_usuario === 'Noite') {
        $turno = 'Noite';
    } elseif ($turno_usuario === 'Integral') {
        $turno = 'Integral';
    }

    // Defina o valor de presença como 'Presente' independentemente do horário
    $presenca = 'Presente';

    // Insira os dados de frequência no banco de dados
    $query = "INSERT INTO frequencia (nome, tipo_usuario, dia, hora, turno, presenca) VALUES ('$nome', '$tipo_usuario', CURDATE(), '$horaAtual', '$turno', '$presenca')";

    if (mysqli_query($conexao, $query)) {
        // Verifique se já existe um registro na tabela registros_horas para o dia de hoje
        $query_verifica_registro = "SELECT COUNT(*) as num_registros FROM registro_hora WHERE nome = '$nome' AND data_registro = CURDATE()";
        $result_verifica_registro = mysqli_query($conexao, $query_verifica_registro);
        $row_verifica_registro = mysqli_fetch_assoc($result_verifica_registro);

        if ($row_verifica_registro['num_registros'] == 0) {
            // Adicione as horas acumuladas à tabela registros_horas apenas se ainda não houver um registro para o dia de hoje
            $query_horas_acumuladas = "INSERT INTO registro_hora (nome, horas_trabalhadas, data_registro) VALUES ('$nome', '$diferenca_formatada', CURDATE())";
            mysqli_query($conexao, $query_horas_acumuladas);
        }

        header("Location: ../principal.php");
        exit();

    } else {
        echo "Erro ao registrar a frequência: " . mysqli_error($conexao);
    }
} elseif ($num_entradas < 2) {
    // Defina o valor de presença com base no turno do usuário
    $presenca = ($turno === $turno_usuario || $turno_usuario === 'Integral') ? 'Presente' : 'Ausente';

    // Exiba o turno
    echo "Seu turno é: $turno_usuario<br>";

    // Insira os dados de frequência no banco de dados
    $query = "INSERT INTO frequencia (nome, tipo_usuario, dia, hora, turno, presenca) VALUES ('$nome', '$tipo_usuario', CURDATE(), '$horaAtual', '$turno', '$presenca')";

    if (mysqli_query($conexao, $query)) {
        // Redirecione de volta para a página principal
        header("Location: ../principal.php");
        exit();
    } else {
        echo "Erro ao registrar a frequência: " . mysqli_error($conexao);
    }
} else {
    echo "Você já registrou duas entradas para o dia de hoje.";
}
?>
