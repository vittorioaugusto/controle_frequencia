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
    if (strtotime($horaAtual) >= strtotime('06:00:00') && strtotime($horaAtual) < strtotime('12:59:59')) {
        $turno = 'Manhã';
    } elseif (strtotime($horaAtual) >= strtotime('13:00:00') && strtotime($horaAtual) < strtotime('17:59:59')) {
        $turno = 'Tarde';
    } elseif (strtotime($horaAtual) >= strtotime('18:00:00') && strtotime($horaAtual) <= strtotime('23:59:59')) {
        $turno = 'Noite';
    } elseif (strtotime($horaAtual) >= strtotime('06:00:00') && strtotime($horaAtual) < strtotime('05:59:59')) {
        $turno = 'Integral';
    } else {
        $turno = 'Outro';
    }

    // Calcular a diferença em segundos entre a hora atual e o início do expediente Manhã (07:00:00)
    $diferenca_segundos_manha = strtotime('07:00:00') - strtotime($horaAtual);

    // Calcular a diferença em segundos entre a hora atual e o início do expediente Tarde (14:00:00)
    $diferenca_segundos_tarde = strtotime('14:00:00') - strtotime($horaAtual);

    // Calcular a diferença em segundos entre a hora atual e o início do expediente Noite (19:00:00)
    $diferenca_segundos_noite = strtotime('19:00:00') - strtotime($horaAtual);

    // Verifique se o usuário chegou antes do horário de trabalho e acumule o tempo
    if (strtotime($horaAtual) < strtotime('07:00:00') && !(($turno == 'Tarde' && strtotime($horaAtual) < strtotime('14:00:00')) || ($turno == 'Noite' && strtotime($horaAtual) < strtotime('19:00:00')) || ($turno == 'Integral' && strtotime($horaAtual) < strtotime('07:00:00')))) {
        // Converter a diferença para o formato HH:MM:SS
        $diferenca_formatada_manha = gmdate('H:i:s', $diferenca_segundos_manha);
        $diferenca_formatada_tarde = gmdate('H:i:s', $diferenca_segundos_tarde);
        $diferenca_formatada_noite = gmdate('H:i:s', $diferenca_segundos_noite);

        // Exiba a mensagem e adicione a entrada antecipada no banco de dados
        echo "Você chegou antes do horário de trabalho! Ganhou para o turno da Manhã: $diferenca_formatada_manha\n";
        // Exiba a mensagem e adicione a entrada antecipada no banco de dados
        echo "Você chegou antes do horário de trabalho! Ganhou para o turno da Manhã: $diferenca_formatada_tarde\n";
        // Exiba a mensagem e adicione a entrada antecipada no banco de dados
        echo "Você chegou antes do horário de trabalho! Ganhou para o turno da Tarde: $diferenca_formatada_noite\n";

        // Defina o valor de presença com base no turno do usuário
        $presenca = ($turno === $turno_usuario || $turno_usuario === 'Integral') ? 'Presente' : 'Ausente';

        // Insira os dados de frequência no banco de dados
        $query = "INSERT INTO frequencia (nome, tipo_usuario, dia, hora, turno, presenca) VALUES ('$nome', '$tipo_usuario', CURDATE(), '$horaAtual', '$turno', '$presenca')";

        if (mysqli_query($conexao, $query)) {
            // Adicione as horas acumuladas à tabela registros_horas para o turno da Manhã
            $query_horas_acumuladas_manha = "INSERT INTO registros_horas (nome, horas_trabalhadas, data_registro) VALUES ('$nome', '$diferenca_formatada_manha', CURDATE())";
            mysqli_query($conexao, $query_horas_acumuladas_manha);
            // Adicione as horas acumuladas à tabela registros_horas para o turno da Tarde
            $query_horas_acumuladas_tarde = "INSERT INTO registros_horas (nome, horas_trabalhadas, data_registro) VALUES ('$nome', '$diferenca_formatada_tarde', CURDATE())";
            mysqli_query($conexao, $query_horas_acumuladas_tarde);
            // Adicione as horas acumuladas à tabela registros_horas para o turno da Noite
            $query_horas_acumuladas_noite = "INSERT INTO registros_horas (nome, horas_trabalhadas, data_registro) VALUES ('$nome', '$diferenca_formatada_noite', CURDATE())";
            mysqli_query($conexao, $query_horas_acumuladas_noite);

            // Redirecione de volta para a página principal
            header("Location: principal.php");
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
            header("Location: principal.php");
            exit();
        } else {
            echo "Erro ao registrar a frequência: " . mysqli_error($conexao);
        }
    } else {
        echo "Você já registrou duas entradas para o dia de hoje.";
    }
}
