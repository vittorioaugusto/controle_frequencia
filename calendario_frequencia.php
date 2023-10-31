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

$frequencia_dias_horas = array(); // Defina uma matriz vazia como padrão

// Verifique se o formulário de filtro foi enviado e processa o mês selecionado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verifique se as chaves "mes" e "ano" estão definidas no envio do formulário
    $mesSelecionado = isset($_POST['mes']) ? $_POST['mes'] : date('m');
    $anoSelecionado = isset($_POST['ano']) ? $_POST['ano'] : date('Y');

    // Consulta SQL para obter a lista de dias e horas em que o usuário selecionado realizou a frequência no mês/ano selecionados
    $query_frequencia_usuario = "SELECT presenca, COUNT(*) as total FROM frequencia WHERE nome = '$usuarioSelecionado' AND MONTH(dia) = '$mesSelecionado' AND YEAR(dia) = '$anoSelecionado' GROUP BY presenca";
    $result_frequencia_usuario = mysqli_query($conexao, $query_frequencia_usuario);

    // Inicialize as estatísticas
    $presenca_stats = array(
        'Presente' => 0,
        'Ausente' => 0
    );

    while ($row_frequencia = mysqli_fetch_assoc($result_frequencia_usuario)) {
        $presenca = $row_frequencia['presenca'];
        $total = $row_frequencia['total'];

        if ($presenca === 'Presente') {
            $presenca_stats['Presente'] = $total;
        } elseif ($presenca === 'Ausente') {
            $presenca_stats['Ausente'] = $total;
        }
    }

    // Consulta SQL para obter os detalhes da frequência no mês/ano selecionados
    $query_detalhes_frequencia = "SELECT dia, hora, presenca FROM frequencia WHERE nome = '$usuarioSelecionado' AND MONTH(dia) = '$mesSelecionado' AND YEAR(dia) = '$anoSelecionado'";
    $result_detalhes_frequencia = mysqli_query($conexao, $query_detalhes_frequencia);

    while ($row_frequencia = mysqli_fetch_assoc($result_detalhes_frequencia)) {
        $frequencia_dias_horas[] = [
            'dia' => $row_frequencia['dia'],
            'hora' => $row_frequencia['hora'],
            'presenca' => $row_frequencia['presenca']
        ];
    }
}

$meses_em_portugues = array(
    1 => 'Janeiro',
    2 => 'Fevereiro',
    3 => 'Março',
    4 => 'Abril',
    5 => 'Maio',
    6 => 'Junho',
    7 => 'Julho',
    8 => 'Agosto',
    9 => 'Setembro',
    10 => 'Outubro',
    11 => 'Novembro',
    12 => 'Dezembro'
);

$mesSelecionado = isset($_POST['mes']) ? $_POST['mes'] : date('m');
$anoSelecionado = isset($_POST['ano']) ? $_POST['ano'] : date("Y");
$nome_mes = isset($meses_em_portugues[intval($mesSelecionado)]) ? $meses_em_portugues[intval($mesSelecionado)] : 'Mês Inválido';

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
    // Verifique se o usuário está logado como administrador
    if ($_SESSION['tipo_usuario'] === 'Administrador') {
        // Se for administrador, exiba o formulário de seleção do usuário e o restante do código para o administrador
        echo '<form action="calendario_frequencia.php" method="POST">
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

        echo '</select><input type="submit" value="Filtrar">
    </form>';

        if (isset($_POST['usuario'])) {
            $usuarioSelecionado = $_POST['usuario'];

            // Exemplo de consulta SQL (substitua com a sua própria consulta):
            $queryFrequencia = "SELECT * FROM frequencia WHERE nome = '$usuarioSelecionado'";
            $resultFrequencia = mysqli_query($conexao, $queryFrequencia);
            $frequencia_dias_horas = array(); // Inicialize com os dados do usuário

            while ($rowFrequencia = mysqli_fetch_assoc($resultFrequencia)) {
                $frequencia_dias_horas[] = $rowFrequencia;
            }

            // Inicialize as variáveis de estatísticas para o usuário selecionado
            $quantidadePresente = 0;
            $quantidadeAusente = 0;

            // Calcule as estatísticas com base nos dados de frequência do usuário selecionado
            foreach ($frequencia_dias_horas as $frequencia) {
                if ($frequencia['presenca'] == 'Presente') {
                    $quantidadePresente++;
                } elseif ($frequencia['presenca'] == 'Ausente') {
                    $quantidadeAusente++;
                }
            }


            // Exibir as estatísticas para o usuário selecionado
            echo '<h3>Estatísticas de Presença de ' . $usuarioSelecionado . ':</h3>';
            echo "<p>Presente: $quantidadePresente</p>";
            echo "<p>Ausente: $quantidadeAusente</p>";
        }


        if (isset($_POST['usuario'])) {
            $usuarioSelecionado = $_POST['usuario'];

            // Exemplo de consulta SQL (substitua com a sua própria consulta):
            $queryFrequencia = "SELECT * FROM frequencia WHERE nome = '$usuarioSelecionado'";
            $resultFrequencia = mysqli_query($conexao, $queryFrequencia);
            $frequencia_dias_horas = array(); // Inicialize com os dados do usuário

            while ($rowFrequencia = mysqli_fetch_assoc($resultFrequencia)) {
                $frequencia_dias_horas[] = $rowFrequencia;
            }
        }

        // Iterar por todos os meses do ano
        for ($mes = 1; $mes <= 12; $mes++) {
            $ano = date('Y');
            $numeroDias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
            $nomeMes = date('F', strtotime("$ano-$mes-01"));

            echo "<h4>$nomeMes</h4>";
            echo "<table>";
            echo "<tr><th>Dom</th><th>Seg</th><th>Ter</th><th>Qua</th><th>Qui</th><th>Sex</th><th>Sáb</th></tr>";
            $primeiroDia = date('w', strtotime("$ano-$mes-01"));

            echo "<tr>";
            for ($i = 0; $i < $primeiroDia; $i++) {
                echo "<td></td>";
            }

            for ($dia = 1; $dia <= $numeroDias; $dia++) {
                $dataVerificar = "$ano-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-" . str_pad($dia, 2, '0', STR_PAD_LEFT);
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

                echo "<td class='$classeCSS'>$dia</td>";

                if (($dia + $primeiroDia) % 7 == 0) {
                    echo "</tr>";
                    if ($dia < $numeroDias) {
                        echo "<tr>";
                    }
                }
            }

            // Preencha os espaços em branco no final do mês
            for ($i = ($numeroDias + $primeiroDia) % 7; $i > 0; $i--) {
                echo "<td></td>";
            }

            echo "</table>";
        }

        echo '</div>';
    }

    ?>


    <?php
    // Verifique se o usuário está logado como administrador
    if ($_SESSION['tipo_usuario'] !== 'Administrador') {
        // Se o usuário não for administrador, exiba o formulário de seleção do mês e ano
        echo '<form action="calendario_frequencia.php" method="POST">
        <label for="mes">Selecione o mês:</label>
        <select name="mes" id="mes">
            <option value="01">Janeiro</option>
            <option value="02">Fevereiro</option>
            <option value="03">Março</option>
            <option value="04">Abril</option>
            <option value="05">Maio</option>
            <option value="06">Junho</option>
            <option value="07">Julho</option>
            <option value="08">Agosto</option>
            <option value="09">Setembro</option>
            <option value="10">Outubro</option>
            <option value="11">Novembro</option>
            <option value="12">Dezembro</option>
        </select>
        <label for="ano">Selecione o ano:</label>
        <select name="ano" id="ano">';
        $anoAtual = date("Y");
        for ($ano = $anoAtual; $ano >= ($anoAtual - 5); $ano--) {
            echo "<option value='$ano'>$ano</option>";
        }
        echo '</select>
        <input type="submit" value="Filtrar">
    </form>';
    }
    ?>


    <?php
    // Verifique o tipo de usuário
    if ($_SESSION['tipo_usuario'] !== 'Administrador') {
        // Inicialize as variáveis de estatísticas
        $quantidadePresente = 0;
        $quantidadeAusente = 0;

        // Calcule as estatísticas com base nos dados de frequência
        foreach ($frequencia_dias_horas as $frequencia) {
            if ($frequencia['presenca'] == 'Presente') {
                $quantidadePresente++;
            } elseif ($frequencia['presenca'] == 'Ausente') {
                $quantidadeAusente++;
            }
        }

        // Exibir as estatísticas apenas para os não administradores
        echo '<h3>Estatísticas de Presença:</h3>';
        echo "<p>Presente: $quantidadePresente</p>";
        echo "<p>Ausente: $quantidadeAusente</p>";


        if (!$anoSelecionado || !$mesSelecionado) {
            $mesSelecionado = date('m');
            $anoSelecionado = date('Y');
        }

        $numeroDias = cal_days_in_month(CAL_GREGORIAN, $mesSelecionado, $anoSelecionado);
        echo "<h2>" . date('F', strtotime("$anoSelecionado-$mesSelecionado-01")) . " $anoSelecionado</h2>";
        echo "<table>";
        echo "<tr><th>Dom</th><th>Seg</th><th>Ter</th><th>Qua</th><th>Qui</th><th>Sex</th><th>Sáb</th></tr>";
        $primeiroDia = date('w', strtotime("$anoSelecionado-$mesSelecionado-01"));

        echo "<tr>";
        for ($i = 0; $i < $primeiroDia; $i++) {
            echo "<td></td>";
        }

        for ($dia = 1; $dia <= $numeroDias; $dia++) {
            $dataVerificar = "$anoSelecionado-" . str_pad($mesSelecionado, 2, '0', STR_PAD_LEFT) . "-" . str_pad($dia, 2, '0', STR_PAD_LEFT);
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

            echo "<td class='$classeCSS'>$dia</td>";

            if (($dia + $primeiroDia) % 7 == 0) {
                echo "</tr>";
                if ($dia < $numeroDias) {
                    echo "<tr>";
                }
            }
        }

        // Preencha os espaços em branco no final do mês
        for ($i = ($numeroDias + $primeiroDia) % 7; $i > 0; $i--) {
            echo "<td></td>";
        }

        echo "</table>";
    }
    ?>


    <h3>Dias e Horas da Frequência:</h3>
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