<?php
session_start();
include 'conexao.php';

// Defina o fuso horário para o de São Paulo (ou o fuso horário apropriado)
date_default_timezone_set('America/Sao_Paulo');

// Verifique se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: index.php");
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

    // Verifique se o usuário chegou antes do horário de trabalho e acumule o tempo
    if (strtotime($horaAtual) < strtotime('07:00:00') || strtotime($horaAtual) < strtotime('14:00:00') || strtotime($horaAtual) < strtotime('19:00:00')) {
        // Calcular a diferença em segundos entre a hora atual e o início do expediente correspondente
        if (strtotime($horaAtual) < strtotime('07:00:00')) {
            $expedienteInicio = '07:00:00';
        } elseif (strtotime($horaAtual) < strtotime('14:00:00')) {
            $expedienteInicio = '14:00:00';
        } else {
            $expedienteInicio = '19:00:00';
        }

        $diferenca_segundos = strtotime($expedienteInicio) - strtotime($horaAtual);

        // Converter a diferença para o formato HH:MM:SS
        $diferenca_formatada = gmdate('H:i:s', $diferenca_segundos);

        // Exiba a mensagem e adicione a entrada antecipada no banco de dados
        echo "Você chegou antes do horário de trabalho às $expedienteInicio! Ganhou: $diferenca_formatada\n";

        // Defina o valor de presença com base no turno do usuário
        $presenca = ($turno === $turno_usuario || $turno_usuario === 'Integral') ? 'Presente' : 'Ausente';

        // Insira os dados de frequência no banco de dados
        $query = "INSERT INTO frequencia (nome, tipo_usuario, dia, hora, turno, presenca) VALUES ('$nome', '$tipo_usuario', CURDATE(), '$horaAtual', '$turno', '$presenca')";

        if (mysqli_query($conexao, $query)) {
            // Verifique se já existe um registro na tabela registros_horas para o dia de hoje
            $query_verifica_registro = "SELECT COUNT(*) as num_registros FROM registros_horas WHERE nome = '$nome' AND data_registro = CURDATE()";
            $result_verifica_registro = mysqli_query($conexao, $query_verifica_registro);
            $row_verifica_registro = mysqli_fetch_assoc($result_verifica_registro);

            if ($row_verifica_registro['num_registros'] == 0) {
                // Adicione as horas acumuladas à tabela registros_horas apenas se ainda não houver um registro para o dia de hoje
                $query_horas_acumuladas = "INSERT INTO registros_horas (nome, horas_trabalhadas, data_registro) VALUES ('$nome', '$diferenca_formatada', CURDATE())";
                mysqli_query($conexao, $query_horas_acumuladas);
            }

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
