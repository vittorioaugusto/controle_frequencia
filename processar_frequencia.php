<?php
session_start();
include 'conexao.php';

// Verifique se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data_hora = $_POST['data_hora'];
    $nome = $_SESSION['nome'];
    $tipo_usuario = $_SESSION['tipo_usuario'];

    // Obtenha a hora da data e hora selecionada
    $hora = date('H:i:s', strtotime($data_hora));
    $data = date('Y-m-d', strtotime($data_hora));

    // Verifique se o usuário já registrou uma entrada para o dia atual
    $query_check = "SELECT COUNT(*) as num_entradas FROM frequencia WHERE nome = '$nome' AND dia = '$data'";
    $result_check = mysqli_query($conexao, $query_check);

    if ($result_check && mysqli_num_rows($result_check) > 0) {
        $row_check = mysqli_fetch_assoc($result_check);
        $num_entradas = $row_check['num_entradas'];

        if ($num_entradas < 2) {
            // O usuário pode registrar uma entrada
            // Recupere o turno do usuário da tabela "usuarios"
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
            if (strtotime($hora) >= strtotime('07:00:00') && strtotime($hora) < strtotime('12:00:00')) {
                $turno = 'Manhã';
            } elseif (strtotime($hora) >= strtotime('13:00:00') && strtotime($hora) < strtotime('17:00:00')) {
                $turno = 'Tarde';
            } elseif (strtotime($hora) >= strtotime('18:00:00') && strtotime($hora) <= strtotime('21:00:00')) {
                $turno = 'Noite';
            } elseif (strtotime($hora) >= strtotime('07:00:00') && strtotime($hora) <= strtotime('21:00:00')) {
                $turno = 'Integral';
            } else {
                $turno = 'Outro';
            }

            // Defina o valor de presença com base no turno do usuário
            $presenca = ($turno === $turno_usuario) ? 'Presente' : 'Ausente';

            // Insira os dados de frequência no banco de dados
            $query = "INSERT INTO frequencia (nome, tipo_usuario, dia, hora, turno, presenca) VALUES ('$nome', '$tipo_usuario', '$data', '$hora', '$turno', '$presenca')";

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
}
?>
