<?php
session_start();
include 'conexao.php';

// Verifique se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php");
    exit();
}

// Verifique se o usuário é um administrador
if ($_SESSION['tipo_usuario'] !== 'Administrador') {
    header("Location: principal.php");
    exit();
}

// Consulta SQL para obter todos os tipos de usuário, exceto administrador
$queryUsuarios = "SELECT DISTINCT tipo_usuario FROM usuarios WHERE tipo_usuario != 'Administrador'";
$resultUsuarios = mysqli_query($conexao, $queryUsuarios);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Frequência dos Funcionários</title>
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

    <!-- Adicione um formulário de filtro para nome do usuário e data -->
    <form method="post" action="frequencia_funcionarios.php">
        <h3>Filtrar Frequência dos Funcionários:</h3>
        <label for="usuario">Selecione o usuário:</label>
        <select name="usuario" id="usuario">
            <option value="">Todos os Usuários</option>
            <?php
            // Consulta SQL para obter os nomes dos usuários
            $queryNomesUsuarios = "SELECT DISTINCT f.nome FROM frequencia f";
            $resultNomesUsuarios = mysqli_query($conexao, $queryNomesUsuarios);

            while ($rowNomeUsuario = mysqli_fetch_assoc($resultNomesUsuarios)) {
                $nomeUsuario = $rowNomeUsuario['nome'];
                echo "<option value='$nomeUsuario'>$nomeUsuario</option>";
            }
            ?>
        </select>

        <label for="data">Data:</label>
        <input type="date" name="data" id="data">
        <input type="submit" value="Filtrar">
    </form>

    <table border="1">
        <thead>
            <caption>
                <h3>Frequência de
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
                <th>Dia</th>
                <th>Hora</th>
                <th>Turno</th>
                <th>Presença</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Verifique se os critérios de filtro foram enviados
            if (isset($_POST['usuario']) || isset($_POST['data'])) {
                $nomeUsuarioSelecionado = $_POST['usuario'];
                $dataSelecionada = $_POST['data'];

                // Construa a parte condicional da consulta SQL com base nos critérios
                $condicao = '';

                if (!empty($nomeUsuarioSelecionado) && !empty($dataSelecionada)) {
                    $condicao = "WHERE f.nome = '$nomeUsuarioSelecionado' AND f.dia = '$dataSelecionada'";
                } elseif (!empty($nomeUsuarioSelecionado)) {
                    $condicao = "WHERE f.nome = '$nomeUsuarioSelecionado'";
                } elseif (!empty($dataSelecionada)) {
                    $condicao = "WHERE f.dia = '$dataSelecionada'";
                }

                // Consulta SQL para obter os funcionários com base nos critérios de filtro
                $queryFuncionarios = "SELECT f.nome, f.dia, f.hora, f.turno, f.presenca, u.tipo_usuario FROM frequencia f JOIN usuarios u ON f.nome = u.nome $condicao";
                $resultFuncionarios = mysqli_query($conexao, $queryFuncionarios);

                while ($rowFuncionario = mysqli_fetch_assoc($resultFuncionarios)) {
                    echo "<tr>";
                    echo "<td>" . $rowFuncionario['tipo_usuario'] . "</td>";
                    echo "<td>" . $rowFuncionario['nome'] . "</td>";
                    echo "<td>" . $rowFuncionario['dia'] . "</td>";
                    echo "<td>" . $rowFuncionario['hora'] . "</td>";
                    echo "<td>" . $rowFuncionario['turno'] . "</td>";
                    echo "<td>" . $rowFuncionario['presenca'] . "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>

    <script type="text/javascript" src="js/funcoes.js"></script>
</body>

</html>