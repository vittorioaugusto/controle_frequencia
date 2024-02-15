<?php
session_start();
include 'conexao.php';

// Defina o fuso horário para o de São Paulo (ou o fuso horário apropriado)
date_default_timezone_set('America/Sao_Paulo');

if (!isset($_SESSION['nome']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: index.php");
    exit();
}

$tipo_usuario = $_SESSION['tipo_usuario'];
$nome = $_SESSION['nome'];

if ($tipo_usuario === 'Administrador') {
    header("Location: principal.php");
    exit();
}

// Consulta para recuperar a última frequência do usuário no mesmo dia
$query = "SELECT nome, tipo_usuario, dia, hora, turno, presenca 
          FROM frequencia 
          WHERE nome = '$nome' AND dia = CURDATE()
          ORDER BY hora DESC 
          LIMIT 1";

$result = mysqli_query($conexao, $query);

// Verifique se a consulta retornou resultados
if ($row = mysqli_fetch_assoc($result)) {
    $ultimaFrequenciaData = $row['dia'];
    $ultimaFrequenciaHora = $row['hora'];
    // Agora você pode exibir os dados da última frequência realizada pelo usuário
} else {
    // Não há registros de frequência para o usuário no mesmo dia
}

?>


<!DOCTYPE html>
<html>

<head>
    <title>Registro de Frequência</title>
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
                <p>Turno: <?php echo $_SESSION['turno']; ?></p>
            </div>
            <div class="botao_nav">
                <ul>
                    <a href="principal.php"> <button id="butao_selecionado">Início</button></a>
                    <a href="frequencia.php"><button>Realizar Frequência</button></a>
                    <a href="horas_acumuladas.php"><button>Horas Acumuladas</button></a>
                    <a href="calendario_frequencia.php"><button>Calendário Frequência</button></a>
                    <a href="perfil.php"><button>Perfil</button></a>
                    <a href="javascript:void(0);" onclick="confirmarSaida();"> <button class="butao">Sair</button></a>
                </ul>
            </div>
        </nav>
    </header>

    <?php

    // Verifique se o usuário já realizou uma frequência de entrada e uma de saída no mesmo dia
    $queryContagem = "SELECT COUNT(*) AS numFrequencias
                  FROM frequencia 
                  WHERE nome = '$nome' AND dia = CURDATE()";

    $resultContagem = mysqli_query($conexao, $queryContagem);

    if ($rowContagem = mysqli_fetch_assoc($resultContagem)) {
        $numFrequencias = $rowContagem['numFrequencias'];

        // Defina o limite de frequência (1 entrada e 1 saída)
        $limiteFrequencia = 2;

        if ($numFrequencias >= $limiteFrequencia) {
            echo "<p style='color: red;'>Você atingiu o limite de frequências para hoje.</p>";
        } else {
            // Permitir que o usuário registre uma nova frequência
            echo "<h2>Registre sua Frequência</h2>";
            echo "<form action='processar_frequencia.php' method='POST'>";
            echo "<button type='submit' value='Registrar Frequência' id='registrarFrequenciaBtn'>Registrar Frequência</button>";
            echo "</form>";
        }
    }

    ?>

    <?php if (isset($ultimaFrequenciaData) && isset($ultimaFrequenciaHora)) : ?>
        <h4>Última Frequência Realizada</h4>
        <p>Data: <?php echo $ultimaFrequenciaData; ?></p>
        <p>Hora: <?php echo $ultimaFrequenciaHora; ?></p>
    <?php endif; ?>


    <script type="text/javascript" src="js/funcoes.js"></script>
</body>

</html>