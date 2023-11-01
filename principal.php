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

    <table border="1">
    <thead>
        <?php
        if ($_SESSION['tipo_usuario'] === 'Administrador') {
            echo '<caption>Frequência de Todos os Funcionários</caption>';
        } else {
            echo '<caption>Minha Frequência</caption>';
        }
        ?>
        <tr>
            <th>Funcionário</th>
            <th>Nome</th>
            <th>Data</th>
            <th>Hora</th>
            <th>Turno</th>
            <th>Presença</th>
            <th>Tipo</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $result = mysqli_query($conexao, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            $nomeFuncionario = $row['nome'];
            $dataPresenca = $row['dia'];

            // Verificar e rastrear o número de presenças para o mesmo funcionário e data
            if (!isset($presencasPorDia[$nomeFuncionario][$dataPresenca])) {
                $presencasPorDia[$nomeFuncionario][$dataPresenca] = 0;
            }

            echo "<tr>";
            echo "<td>" . $row['tipo_usuario'] . "</td>";
            echo "<td>" . $row['nome'] . "</td>";
            echo "<td>" . $row['dia'] . "</td>";
            echo "<td>" . $row['hora'] . "</td>";
            echo "<td>" . $row['turno'] . "</td>"; // Adicione a coluna de Turno
            echo "<td>" . $row['presenca'] . "</td>";
            echo "<td>";

            // Determinar se a presença é uma entrada ou saída com base no número de presenças
            if ($row['presenca'] == 'Presente') {
                if ($presencasPorDia[$nomeFuncionario][$dataPresenca] % 2 == 0) {
                    echo "Entrada";
                } else {
                    echo "Saída";
                }
                $presencasPorDia[$nomeFuncionario][$dataPresenca]++;
            } else {
                echo "N/A"; // Caso a presença não seja "Presente"
            }

            echo "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>



    <script type="text/javascript" src="js/funcoes.js"></script>
</body>

</html>