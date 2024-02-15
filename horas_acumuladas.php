<?php
session_start();
include 'conexao.php';

// Verifique se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: index.php");
    exit();
}

$nome = $_SESSION['nome'];

// Consulta para recuperar as horas acumuladas do usuário
$query_horas_acumuladas = "SELECT IFNULL(SUM(TIME_TO_SEC(horas_trabalhadas)), 0) AS total_horas
                           FROM registros_horas
                           WHERE nome = '$nome'
                           GROUP BY nome";

$result_horas_acumuladas = mysqli_query($conexao, $query_horas_acumuladas);

if ($result_horas_acumuladas && mysqli_num_rows($result_horas_acumuladas) > 0) {
    $row = mysqli_fetch_assoc($result_horas_acumuladas);
    $total_horas_acumuladas = $row['total_horas'];

    // Converter os segundos acumulados para o formato HH:MM:SS
    $total_horas_acumuladas_formatado = gmdate('H:i:s', $total_horas_acumuladas);
} else {
    $total_horas_acumuladas_formatado = '00:00:00';
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Horas Acumuladas</title>
</head>

<body>
    <header>
        <nav>
            <div class="logo">
                <div class="coin"></div>
                <h1 id="titulo">Sistema de Frequência</h1>
            </div>
            <div class="bem_vindo_nome">
                <p>Tipo de Usuário: <?php echo $_SESSION['tipo_usuario']; ?></p>
            </div>
            <div class="botao_nav">
                <ul>
                    <a href="principal.php"><button>Início</button></a>
                    <a href="frequencia.php"><button>Realizar Frequência</button></a>
                    <a href="horas_acumuladas.php"><button id="butao_selecionado">Horas Acumuladas</button></a>
                    <a href="calendario_frequencia.php"><button>Calendário Frequência</button></a>
                    <a href="perfil.php"><button>Perfil</button></a>
                    <a href="javascript:void(0);" onclick="confirmarSaida();"><button class="butao">Sair</button></a>
                </ul>
            </div>
        </nav>
    </header>

    <h2>Horas Acumuladas</h2>

    <div>
        <p>Total de Horas Acumuladas: <?php echo $total_horas_acumuladas_formatado; ?></p>
    </div>

    <script type="text/javascript" src="js/funcoes.js"></script>
</body>

</html>