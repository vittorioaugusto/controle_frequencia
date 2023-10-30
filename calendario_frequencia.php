<?php
session_start();
include 'conexao.php';

// Verifique se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php");
    exit();
}

// Recupere o nome do usuário
$nome = $_SESSION['nome'];

// Verifique se o usuário é um administrador
if ($_SESSION['tipo_usuario'] === 'Administrador') {
    // Se o usuário for um administrador, verifique se foi selecionado um usuário diferente
    if (isset($_GET['usuario'])) {
        $usuarioSelecionado = $_GET['usuario'];
    } else {
        // Se nenhum usuário foi selecionado, use o nome do administrador
        $usuarioSelecionado = $nome;
    }
} else {
    // Se não for um administrador, use seu próprio nome
    $usuarioSelecionado = $nome;
}

$query_presenca = "SELECT presenca, COUNT(*) as total FROM frequencia WHERE nome = '$usuarioSelecionado' GROUP BY presenca";
$result_presenca = mysqli_query($conexao, $query_presenca);

$presenca_stats = array(
    'Presente' => 0,
    'Ausente' => 0
);

while ($row_presenca = mysqli_fetch_assoc($result_presenca)) {
    $presenca = $row_presenca['presenca'];
    $total = $row_presenca['total'];

    if ($presenca === 'Presente') {
        $presenca_stats['Presente'] = $total;
    } elseif ($presenca === 'Ausente') {
        $presenca_stats['Ausente'] = $total;
    }
}

// Consulta SQL para obter a lista de dias e horas em que o usuário selecionado realizou a frequência
$query_frequencia_usuario = "SELECT dia, hora, presenca FROM frequencia WHERE nome = '$usuarioSelecionado'";
$result_frequencia_usuario = mysqli_query($conexao, $query_frequencia_usuario);

$frequencia_dias_horas = array();

while ($row_frequencia = mysqli_fetch_assoc($result_frequencia_usuario)) {
    $frequencia_dias_horas[] = [
        'dia' => $row_frequencia['dia'],
        'hora' => $row_frequencia['hora'],
        'presenca' => $row_frequencia['presenca']
    ];
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Calendário de Frequência</title>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        .presente {
            background-color: lightgreen;
        }

        .ausente {
            background-color: tomato;
        }

        .sem-cor {
            background-color: transparent;
        }
    </style>
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
                        echo '<a href="funcionarios.php"><button>Funcionários</button></a>';
                        echo '<a href="frequencia_funcionarios.php"><button>Frequência dos Funcionários</button></a>';
                    }
                    ?>
                    <a href="calendario_frequencia.php"><button>Calendário de Frequência</button></a>
                    <a href="perfil.php"><button>Perfil</button></a>
                    <a href="javascript:void(0);" onclick="confirmarSaida();"> <button class="butao">Sair</button></a>
                </ul>
            </div>
        </nav>
    </header>

    <h2>
        <?php
        if ($_SESSION['tipo_usuario'] === 'Administrador' && isset($_GET['usuario'])) {
            echo "Calendário de Frequência de " . $usuarioSelecionado;
        } else {
            echo "Calendário de Frequência";
        }
        ?>
    </h2>

    <?php
    // Verifique se o usuário é um administrador
    if ($_SESSION['tipo_usuario'] === 'Administrador') {
        // Se for administrador, exiba o formulário de seleção do usuário
        echo '<form action="calendario_frequencia.php" method="GET">
        <label for="usuario">Selecione o usuário:</label>
        <select name="usuario" id="usuario">
            <!-- Listar os nomes de usuário disponíveis (você pode buscar isso do banco de dados) -->
            ';
        // Consulta SQL para obter a lista de nomes de usuário (exceto o administrador)
        $queryUsuarios = "SELECT nome FROM usuarios WHERE tipo_usuario != 'Administrador'";
        $resultUsuarios = mysqli_query($conexao, $queryUsuarios);

        while ($rowUsuario = mysqli_fetch_assoc($resultUsuarios)) {
            $usuarioNome = $rowUsuario['nome'];
            echo "<option value='$usuarioNome'>$usuarioNome</option>";
        }

        echo '
        </select>
        <input type="submit" value="Filtrar">
    </form>';
    }
    ?>

    <h3>Estatísticas de Presença:</h3>
    <p>Presente: <?php echo $presenca_stats['Presente']; ?></p>
    <p>Ausente: <?php echo $presenca_stats['Ausente']; ?></p>

    <table>
        <thead>
            <tr>
                <th>Domingo</th>
                <th>Segunda-feira</th>
                <th>Terça-feira</th>
                <th>Quarta-feira</th>
                <th>Quinta-feira</th>
                <th>Sexta-feira</th>
                <th>Sábado</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Defina o mês e o ano para o qual deseja exibir o calendário
            $mes = date('m');
            $ano = date('Y');

            // Inicialize o dia como 1
            $dia = 1;

            // Obtenha o primeiro dia da semana para o mês/ano atual
            $primeiroDia = date('N', strtotime("$ano-$mes-$dia"));

            // Determine o número de dias no mês
            $numeroDias = date('t', strtotime("$ano-$mes-$dia"));

            // Preencha os espaços em branco até o primeiro dia
            echo "<tr>";
            for ($i = 1; $i < $primeiroDia; $i++) {
                echo "<td></td>";
            }

            // Exiba os dias do mês
            for ($dia = 1; $dia <= $numeroDias; $dia++) {
                $dataVerificar = "$ano-$mes-$dia";

                // Inicialize a contagem de "Presente" para o dia atual
                $countPresente = 0;

                foreach ($frequencia_dias_horas as $frequencia) {
                    if ($frequencia['dia'] == $dataVerificar && $frequencia['presenca'] == 'Presente') {
                        $countPresente++;
                    }
                }

                $classeCSS = 'sem-cor'; // Padrão: Sem cor

                if ($countPresente >= 2) {
                    $classeCSS = 'presente';
                } elseif ($countPresente == 1) {
                    $classeCSS = 'ausente';
                }

                echo "<td class='$classeCSS'>$dia<br>";

                // Se for o último dia da semana (sábado), inicie uma nova linha
                if ($primeiroDia == 7) {
                    echo "</tr>";
                    $primeiroDia = 1;
                } else {
                    $primeiroDia++;
                }
            }

            // Preencha os espaços em branco no final do mês
            for ($i = $primeiroDia; $i <= 7; $i++) {
                echo "<td></td>";
            }
            ?>
        </tbody>
    </table>

    <h3>Dias e Horas de Frequência:</h3>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Hora</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($frequencia_dias_horas as $frequencia) {
                echo "<tr>";
                echo "<td>" . $frequencia['dia'] . "</td>";
                echo "<td>" . $frequencia['hora'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <script type="text/javascript" src="js/funcoes.js"></script>

</body>

</html>