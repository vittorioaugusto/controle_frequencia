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

$tipo_usuario = $_SESSION['tipo_usuario'];
$nome = $_SESSION['nome'];

// Verifique se o usuário é um administrador
if ($tipo_usuario === 'Administrador') {
    header("Location: principal.php"); // Redirecione o administrador para a página principal
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
                <p>Nome: <?php echo $_SESSION['nome']; ?></p>
                <p>Tipo de Usuário: <?php echo $_SESSION['tipo_usuario']; ?></p>
            </div>
            <div class="botao_nav">
                <ul>
                    <a href="principal.php"> <button id="butao_selecionado">Início</button></a>
                    <a href="frequencia.php"><button>Realizar Frequência</button></a>
                    <a href="calendario_frequencia.php"><button>Calendário Frequência</button></a>
                    <a href="perfil.php"><button>Perfil</button></a>
                    <a href="javascript:void(0);" onclick="confirmarSaida();"> <button class="butao">Sair</button></a>
                </ul>
            </div>
        </nav>
    </header>

    <h2>Registre sua Frequência</h2>

    <form action="processar_frequencia.php" method="POST">
        <button type="submit" value="Registrar Frequência" id="registrarFrequenciaBtn">Registrar Frequência</button>
    </form>


    <?php if (isset($ultimaFrequenciaData) && isset($ultimaFrequenciaHora)) : ?>
        <h4>Última Frequência Realizada</h4>
        <p>Data: <?php echo $ultimaFrequenciaData; ?></p>
        <p>Hora: <?php echo $ultimaFrequenciaHora; ?></p>
    <?php endif; ?>


    <script type="text/javascript" src="js/funcoes.js"></script>
</body>

</html>