<?php
session_start();
include 'SQL/conexao.php';

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
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Biblioteca icones -->
    <script src="https://kit.fontawesome.com/f2c34800e3.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />

    <link rel="stylesheet" href="assets/css/style.css">
    <title>Calendário Frequência</title>
</head>

<body class="vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">

            <h1 class="navbar-brand no-hover-color">Frequência Master<i class="fa fa-check-circle-o ms-1" aria-hidden="true"></i></h1>

            <button class="navbar-toggler shadow-none border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="sidebar offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header text-white border-bottom">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Frequência Tech</h5>
                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>

                <div class="offcanvas-body d-flex flex-column flex-lg-row p-3 p-lg-0">
                    <ul class="navbar-nav justify-content-center align-items-center fs-5 flex-grow-1 pe-3">
                        <li class="nav-item mx-1">
                            <a class="nav-link" href="principal.php">Início</a>
                        </li>
                        <?php
                        if ($_SESSION['tipo_usuario'] !== 'Administrador') {
                            echo ' <li class="nav-item mx-1">
                            <a class="nav-link" href="realizar_frequencia.php">Realizar Frequência</a>
                        </li>';
                            echo '<li class="nav-item mx-1">
                            <a class="nav-link" href="minha_frequencia.php">Minha Frequência</a>
                        </li>';
                        } else {
                            echo '<li class="nav-item mx-1">
                            <a class="nav-link" href="cadastro_funcionario.php">Cadastrar Funcionário</a>
                        </li>';
                            echo ' <li class="nav-item mx-1">
                            <a class="nav-link" href="funcionarios.php">Funcionários</a>
                        </li>';
                            echo '<li class="nav-item mx-1">
                            <a class="nav-link" href="frequencia_funcionarios.php">Frequência dos Funcionários</a>
                        </li>';
                        }
                        ?>
                        <li class="nav-item mx-1">
                            <a class="nav-link active" style="background-color: #8a50ff" aria-current="page" href="calendario_frequencia.php">Calendário de Frequência</a>
                        </li>
                        <li class="nav-item mx-1">
                            <a class="nav-link" href="perfil.php">Perfil</a>
                        </li>
                    </ul>
                    <div class="d-flex flex-column flex-lg-row justify-content-center align-items-center gap-3">
                        <a href="javascript:void(0);" onclick="confirmarSaida();" class="text-white text-decoration-none px-3 py-1 rounded-4 sair-btn" title="Sair"> <i class="fa fa-sign-out" aria-hidden="true"></i> </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="p-2 mt-3">
            <div class="card-body">
                <h2>
                    <?php
                    if ($_SESSION['tipo_usuario'] === 'Administrador' && isset($_GET['usuario'])) {
                        echo "Calendário de Frequência de " . $usuarioSelecionado;
                    } else {
                        echo "Calendário de Frequência";
                    }
                    ?>
                </h2>

                <?php if ($_SESSION['tipo_usuario'] === 'Administrador') {
                    // Se for administrador, exiba o formulário de seleção do usuário e o restante do código para o administrador
                    echo '<form action="calendario_frequencia.php" method="POST" class="row g-3">
                            <div class="col-md-2">
                                <label for="usuario" class="form-label">Selecione o usuário:</label>
                                <select name="usuario" id="usuario" class="form-select">';

                    // Consulta SQL para obter a lista de nomes de usuário (exceto o administrador)
                    $queryUsuarios = "SELECT nome FROM usuario WHERE tipo_usuario != 'Administrador'";
                    $resultUsuarios = mysqli_query($conexao, $queryUsuarios);

                    while ($rowUsuario = mysqli_fetch_assoc($resultUsuarios)) {
                        $usuarioNome = $rowUsuario['nome'];
                        echo "<option value='$usuarioNome'>$usuarioNome</option>";
                    }

                    echo '      </select>
                            </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-custom-color px-3 py-2 mt-4">Filtrar</button>
                        </div>
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
                        echo '<h3 class="mt-3">Estatísticas de Presença de ' . $usuarioSelecionado . '</h3>';
                        echo "<p>Presente: $quantidadePresente</p>";
                        echo "<p>Ausente: $quantidadeAusente</p>";
                    }

                ?>
            </div>
        </div>
    </div>

    <main class="container-fluid">

    <?php if (isset($_POST['usuario'])) {
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
                        echo "<table class='table table-bordered border-dark'>";
                        echo "<tr class='table-info border-dark'><th>Dom</th><th>Seg</th><th>Ter</th><th>Qua</th><th>Qui</th><th>Sex</th><th>Sáb</th></tr>";
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

                            if ($countPresente >= 2) {
                                echo "<td style='background-color: #28a745; color: #000;'>$dia</td>"; // Verde para presente
                            } elseif ($countPresente == 1) {
                                echo "<td style='background-color: #dc3545; color: #000;'>$dia</td>"; // Vermelho para ausente
                            } else {
                                echo "<td>$dia</td>"; // Padrão sem cor
                            }

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
                }

    ?>

    <?php if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'Administrador') : ?>
        <div class="row">
            <div class="col-md-2">
                <form action="calendario_frequencia.php" method="POST" class="mt-2">
                    <label for="mes">Selecione o mês:</label>
                    <select name="mes" id="mes" class="form-select">
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
                    <label for="ano" class="mt-2">Selecione o ano:</label>
                    <select name="ano" id="ano" class="form-select">
                        <?php
                        $anoAtual = date("Y");
                        for ($ano = $anoAtual; $ano >= ($anoAtual - 5); $ano--) {
                            echo "<option value='$ano'>$ano</option>";
                        }
                        ?>
                    </select>
                    <input type="submit" value="Filtrar" class="btn btn-primary mt-3">
                </form>
            </div>
            <div class="col-md-3">
                <h3 class="mt-4">Estatísticas de Presença</h3>
                <?php
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
                echo "<p>Presente: $quantidadePresente</p>";
                echo "<p>Ausente: $quantidadeAusente</p>";
                ?>
            </div>
        </div>
    <?php endif; ?>


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

        if (!$anoSelecionado || !$mesSelecionado) {
            $mesSelecionado = date('m');
            $anoSelecionado = date('Y');
        }

        $numeroDias = cal_days_in_month(CAL_GREGORIAN, $mesSelecionado, $anoSelecionado);
        echo "<h3 class='mt-3'>" . date('F', strtotime("$anoSelecionado-$mesSelecionado-01")) . " $anoSelecionado</h3>";
        echo "<table class='table table-bordered border-dark'>";
        echo "<tr class='table-info border-dark'><th>Dom</th><th>Seg</th><th>Ter</th><th>Qua</th><th>Qui</th><th>Sex</th><th>Sáb</th></tr>";
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

            if ($countPresente >= 2) {
                echo "<td style='background-color: #28a745; color: #000;'>$dia</td>"; // Verde para presente
            } elseif ($countPresente == 1) {
                echo "<td style='background-color: #dc3545; color: #000;'>$dia</td>"; // Vermelho para ausente
            } else {
                echo "<td>$dia</td>"; // Padrão sem cor
            }

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


    <h3 class="mt-4">Datas e Horas da Frequência:</h3>
    <table class="table table-bordered border-dark">
        <thead>
            <tr class='table-info border-dark'>
                <th>Data</th>
                <th>Hora</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($frequencia_dias_horas as $frequencia) {
                echo "<tr border-dark'>";
                echo "<td>" . $frequencia['dia'] . "</td>";
                echo "<td>" . $frequencia['hora'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script type="text/javascript" src="assets/js/script.js"></script>
</body>

</html>