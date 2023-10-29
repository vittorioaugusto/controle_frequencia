<?php
session_start();
include 'conexao.php';

// Verifique se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php");
    exit();
}

$tipo_usuario = $_SESSION['tipo_usuario'];

// Verifique se o usuário é um administrador
if ($tipo_usuario === 'Administrador') {
    // Recupere os dados de frequência de todos os usuários
    $query = "SELECT nome, tipo_usuario, dia, hora, turno, presenca FROM frequencia";
} else {
    // Recupere apenas os dados de frequência do usuário logado
    $nome = $_SESSION['nome'];
    $query = "SELECT nome, tipo_usuario, dia, hora, turno, presenca FROM frequencia WHERE nome = '$nome'";
}

// Verifique se o usuário é um administrador
if ($tipo_usuario === 'Administrador') {
    header("Location: principal.php"); // Redirecione o administrador para a página principal
    exit();
}

$result = mysqli_query($conexao, $query);

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
            </div>
            <div class="botao_nav">
                <ul>
                    <a href="principal.php"> <button id="butao_selecionado">Início</button></a>
                    <a href="frequencia.php"><button>Realizar Frequência</button></a>
                    <a href="perfil.php"><button>Perfil</button></a>
                    <a href="javascript:void(0);" onclick="confirmarSaida();"> <button class="butao">Sair</button></a>
                </ul>
            </div>
        </nav>
    </header>
    <h2>Registro de Frequência</h2>
    <p>Nome: <?php echo $_SESSION['nome']; ?></p>
    <p>Funcionário: <?php echo $_SESSION['tipo_usuario']; ?></p>
    <form action="processar_frequencia.php" method="POST">
    <label for="data_hora">Data e Hora:</label>
    <input type="datetime-local" name="data_hora" required><br>
    <br>
    <button type="submit" value="Registrar Frequência">Registrar Frequência</button>
</form>


    <script type="text/javascript" src="js/funcoes.js"></script>
</body>

</html>