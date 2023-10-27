<?php
session_start(); // Inicie a sessão para acessar as informações do usuário
include 'conexao.php';

// Verifique se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php"); // Redirecione para a página de login se não estiver logado
    exit();
}

// Defina a consulta SQL com base no tipo de usuário
if ($_SESSION['tipo_usuario'] === 'Administrador') {
    $query = "SELECT nome, tipo_usuario, dia, hora, presenca FROM frequencia";
} else {
    $query = "SELECT nome, tipo_usuario, dia, hora, presenca FROM frequencia WHERE nome = '{$_SESSION['nome']}'";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Página Principal</title>
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
                <?php
                // Verifique se o usuário não é um administrador
                if ($_SESSION['tipo_usuario'] !== 'Administrador') {
                    echo '<a href="frequencia.php"><button>Realizar Frequência</button></a>';
                } else {
                    echo '<a href="funcionarios.php"><button>Funcionários</button></a>';
                    echo '<a href="frequencia_funcionarios.php"><button>Frequência dos funcionários</button></a>';
                }
                ?>
                <a href="javascript:void(0);" onclick="confirmarSaida();"> <button class="butao">Sair</button></a>
            </ul>
        </div>
    </nav>
</header>

<table border="1">
    <thead>
    <?php
    // Verifique se o usuário é um administrador
    if ($_SESSION['tipo_usuario'] === 'Administrador') {
        echo '<caption>Frequência de todos os Funcionários</caption>';
    } else {
        echo '<caption>Minha Frequência</caption>';
    }
    ?>
        <tr>
            <th>Nome</th>
            <th>Funcionário</th>
            <th>Data</th>
            <th>Hora</th>
            <th>Presença</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Recupere os dados da tabela "frequencia" do banco de dados
        $result = mysqli_query($conexao, $query);

        // Exiba os dados na tabela
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['nome'] . "</td>";
            echo "<td>" . $row['tipo_usuario'] . "</td>";
            echo "<td>" . $row['dia'] . "</td>";
            echo "<td>" . $row['hora'] . "</td>";
            echo "<td>" . $row['presenca'] . "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<script type="text/javascript" src="js/funcoes.js"></script>
</body>

</html>
