<?php
session_start(); // Inicie a sessão para acessar as informações do usuário
include 'conexao.php';

// Verifique se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php"); // Redirecione para a página de login se não estiver logado
    exit();
}

// Defina a consulta SQL com base no tipo de usuário e ordene por "dia"
if ($_SESSION['tipo_usuario'] === 'Administrador') {
    $query = "SELECT nome, tipo_usuario, dia, hora, turno, presenca FROM frequencia ORDER BY dia";
} else {
    $query = "SELECT nome, tipo_usuario, dia, hora, turno, presenca FROM frequencia WHERE nome = '{$_SESSION['nome']}' ORDER BY dia";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Funcionário</title>
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
                    <?php
                    // Verifique se o usuário não é um administrador
                    if ($_SESSION['tipo_usuario'] !== 'Administrador') {
                        echo '<a href="frequencia.php"><button>Realizar Frequência</button></a>';
                    } else {
                        echo '<a href="cadastro.php"><button>Cadastrar Funcionário</button></a>';
                        echo '<a href="funcionarios.php"><button>Funcionários</button></a>';
                        echo '<a href="frequencia_funcionarios.php"><button>Frequência dos Funcionários</button></a>';
                    }
                    ?>
                    <a href="calendario_frequencia.php"><button>Calendário de Frequência</button></a>
                    <a href="perfil.php"><button>Perfil</button></a>
                    <a href="javascript:void(0);" onclick="confirmarSaida();"> <button>Sair</button></a>
                </ul>
            </div>
        </nav>
    </header>
    <h2>Cadastrar Funcionário</h2>
    <form action="processar_cadastro.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" required><br>
        <label for="email">Email:</label>
        <input type="text" name="email" required><br>
        <label for="senha">Senha:</label>
        <input type="password" name="senha" required><br>
        <label for="cpf">CPF:</label>
        <input type="number" name="cpf" required><br>
        <label for="telefone">Telefone:</label>
        <input type="text" name="telefone"><br>
        <label for="tipo_usuario">Tipo de Usuário:</label>
        <select name="tipo_usuario" required>
            <option value="Professor">Professor</option>
            <option value="Funcionário de suporte ao aluno">Funcionário de suporte ao aluno</option>
            <option value="Funcionário de manutenção">Funcionário de manutenção</option>
            <option value="Funcionário de segurança">Funcionário de segurança</option>
            <option value="Estagiário">Estagiário</option>
        </select><br>
        <label for="turno">Turno:</label>
        <select name="turno" required>
            <option value="Manhã">Manhã</option>
            <option value="Tarde">Tarde</option>
            <option value="Noite">Noite</option>
            <option value="Integral">Integral</option>
        </select><br>
        <button type="submit" value="Cadastrar">Cadastrar</button>
    </form>

    <script type="text/javascript" src="js/funcoes.js"></script>
</body>

</html>