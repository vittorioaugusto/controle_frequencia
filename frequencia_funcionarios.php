<?php
session_start(); // Inicie a sessão para acessar as informações do usuário
include 'conexao.php';

// Verifique se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php"); // Redirecione para a página de login se não estiver logado
    exit();
}

// Verifique se o usuário é um administrador
if ($_SESSION['tipo_usuario'] !== 'Administrador') {
    header("Location: principal.php"); // Redirecione para a página principal se não for um administrador
    exit();
}

// Consulta SQL para obter todos os tipos de usuário, exceto administrador
$queryUsuarios = "SELECT DISTINCT tipo_usuario FROM usuarios WHERE tipo_usuario != 'Administrador'";

// Execute a consulta SQL
$resultUsuarios = mysqli_query($conexao, $queryUsuarios);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Funcionários</title>
</head>

<body>

<header>
    <nav>
        <div class="logo">
            <div class="coin"></div>
            <h1 id="titulo">Sistema de Frequência</h1>
        </div>
        <div class="bem_vindo_nome">
            <h2>Bem vindo (a): <?php echo $_SESSION['nome']; ?></h2>
            <p>Tipo de Usuário: <?php echo $_SESSION['tipo_usuario']; ?></p>
        </div>
        <div class="botao_nav">
            <ul>
                <a href="principal.php"> <button id="butao_selecionado">Início</button></a>
                <a href="funcionarios.php"><button>Funcionários</button></a>
                <a href="frequencia_funcionarios.php"><button>Frequência dos funcionários</button></a>
                <a href="javascript:void(0);" onclick="confirmarSaida();"> <button class="butao">Sair</button></a>
            </ul>
        </div>
    </nav>
</header>

<?php
while ($rowUsuario = mysqli_fetch_assoc($resultUsuarios)) {
    $tipoUsuario = $rowUsuario['tipo_usuario'];

    // Consulta SQL para obter os funcionários de um tipo de usuário específico
    $queryFuncionarios = "SELECT nome, dia, hora, presenca FROM frequencia WHERE tipo_usuario = '$tipoUsuario'";
    $resultFuncionarios = mysqli_query($conexao, $queryFuncionarios);
    ?>

    <table border="1">
        <thead>
        <caption><h3><?php echo "Funcionário: $tipoUsuario"; ?></h3></caption>
            <tr>
                <th>Nome</th>
                <th>Dia</th>
                <th>Hora</th>
                <th>Presença</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Exiba os dados dos funcionários na tabela
            while ($rowFuncionario = mysqli_fetch_assoc($resultFuncionarios)) {
                echo "<tr>";
                echo "<td>" . $rowFuncionario['nome'] . "</td>";
                echo "<td>" . $rowFuncionario['dia'] . "</td>";
                echo "<td>" . $rowFuncionario['hora'] . "</td>";
                echo "<td>" . $rowFuncionario['presenca'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

<?php
}
?>

<script type="text/javascript" src="js/funcoes.js"></script>
</body>

</html>
