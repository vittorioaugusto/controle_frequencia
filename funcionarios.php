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
                <p>Tipo de Usuário: <?php echo $_SESSION['tipo_usuario']; ?></p>
            </div>
            <div class="botao_nav">
                <ul>
                    <a href="principal.php"> <button id="butao_selecionado">Início</button></a>
                    <a href="funcionarios.php"><button>Funcionários</button></a>
                    <a href="frequencia_funcionarios.php"><button>Frequência dos Funcionários</button></a>
                    <a href="calendario_frequencia.php"><button>Calendário de Frequência</button></a>
                    <a href="perfil.php"><button>Perfil</button></a>
                    <a href="javascript:void(0);" onclick="confirmarSaida();"> <button class="butao">Sair</button></a>
                </ul>
            </div>
        </nav>
    </header>

    <form method="post" action="funcionarios.php" onsubmit="return validarFormulario();">
        <h3>Filtrar Funcionários:</h3>
        <label for="usuario">Selecione o usuário:</label>
        <select name="usuario" id="usuario">
            <option value="">Todos os Usuários</option>
            <?php
            // Consulta SQL para obter os nomes dos usuários que não são administradores
            $queryNomesUsuarios = "SELECT DISTINCT f.nome FROM usuarios f WHERE f.tipo_usuario != 'Administrador'";
            $resultNomesUsuarios = mysqli_query($conexao, $queryNomesUsuarios);

            while ($rowNomeUsuario = mysqli_fetch_assoc($resultNomesUsuarios)) {
                $nomeUsuario = $rowNomeUsuario['nome'];
                echo "<option value='$nomeUsuario'>$nomeUsuario</option>";
            }
            ?>
        </select>

        <label for="cpf">CPF:</label>
        <input type="number" name="cpf" id="cpf">
        <input type="submit" value="Filtrar">
    </form>

    <table border="1">
        <thead>
            <caption>
                <h3>Funcionário
                    <?php
                    if (isset($_POST['usuario'])) {
                        $usuarioSelecionado = $_POST['usuario'];
                        if ($usuarioSelecionado === "") {
                            echo "Todos os Usuários";
                        } else {
                            echo $usuarioSelecionado;
                        }
                    } else {
                        echo "Todos os Usuários";
                    }
                    ?>
                </h3>
            </caption>
            <tr>
                <th>Funcionário</th>
                <th>Nome</th>
                <th>Número de Telefone</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($_POST['usuario']) || isset($_POST['cpf'])) {
                $usuario = $_POST['usuario'];
                $cpf = $_POST['cpf'];

                // Construa a parte condicional da consulta SQL com base nos critérios
                $condicao = '';
                if (!empty($usuario) && !empty($cpf)) {
                    $condicao = "WHERE nome = '$usuario' AND cpf = '$cpf'";
                } elseif (!empty($usuario)) {
                    $condicao = "WHERE nome = '$usuario'";
                } elseif (!empty($cpf)) {
                    $condicao = "WHERE cpf = '$cpf'";
                }

                // Consulta SQL para obter os funcionários com base nos critérios de filtro
                $queryFuncionarios = "SELECT tipo_usuario, nome, telefone FROM usuarios $condicao";
                $resultFuncionarios = mysqli_query($conexao, $queryFuncionarios);

                while ($rowFuncionario = mysqli_fetch_assoc($resultFuncionarios)) {
                    // Verifique se o tipo de usuário não é "Administrador" antes de exibi-lo
                    if ($rowFuncionario['tipo_usuario'] !== 'Administrador') {
                        echo "<tr>";
                        echo "<td>" . $rowFuncionario['tipo_usuario'] . "</td>";
                        echo "<td>" . $rowFuncionario['nome'] . "</td>";
                        echo "<td>" . $rowFuncionario['telefone'] . "</td>";
                        echo "</tr>";
                    }
                }
            }
            ?>
        </tbody>
    </table>


    <script type="text/javascript" src="js/funcoes.js"></script>
</body>

</html>