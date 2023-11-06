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

    // Obtenha a hora atual e a data atual
    $dataAtual = date('Y-m-d'); // Data atual
    $horaAtual = date('H:i:s'); // Hora atual

    // Verifique se o usuário já registrou uma entrada para o data atual
    $query_check = "SELECT COUNT(*) as num_entradas, MAX(hora) as max_hora FROM frequencia WHERE nome = '$nome' AND dia = '$dataAtual'";
    $result_check = mysqli_query($conexao, $query_check);

    if ($result_check && mysqli_num_rows($result_check) > 0) {
        $row_check = mysqli_fetch_assoc($result_check);
        $num_entradas = $row_check['num_entradas'];
        $max_hora = $row_check['max_hora'];

        // Verifique se o usuário está tentando registrar horários fora de ordem
        if ($num_entradas % 2 === 1 && strtotime($horaAtual) < strtotime($max_hora)) {
            // A última entrada foi de entrada, portanto, registre uma saída
            $presenca = 'Saída';
        } else {
            // A última entrada foi de saída, portanto, registre uma entrada
            $presenca = 'Entrada';
        }

        // Insira os dados de frequência no banco de dados
        $query = "INSERT INTO frequencia (nome, tipo_usuario, dia, hora, turno, presenca) VALUES ('$nome', '$tipo_usuario', '$dataAtual', '$horaAtual', '', '$presenca')";

        if (mysqli_query($conexao, $query)) {
            // Redirecione de volta para a página principal
            header("Location: principal.php");
            exit();
        } else {
            echo "Erro ao registrar a frequência: " . mysqli_error($conexao);
        }
    } else {
        // O usuário não possui nenhum registro para o dia atual, registre uma entrada
        $presenca = 'Entrada';

        // Insira os dados de frequência no banco de dados
        $query = "INSERT INTO frequencia (nome, tipo_usuario, dia, hora, turno, presenca) VALUES ('$nome', '$tipo_usuario', '$dataAtual', '$horaAtual', '', '$presenca')";

        if (mysqli_query($conexao, $query)) {
            // Redirecione de volta para a página principal
            header("Location: principal.php");
            exit();
        } else {
            echo "Erro ao registrar a frequência: " . mysqli_error($conexao);
        }
    }
}

?>
