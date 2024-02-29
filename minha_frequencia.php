<?php
session_start();
include 'conexao.php';

// Verifique se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: index.php");
    exit();
}

// Consulta SQL para obter a frequência do usuário logado
$usuarioLogado = $_SESSION['nome'];

// Inicialize o filtro de dia com um valor padrão (hoje)
$filtroDia = date('Y-m-d');

// Verificar se o formulário de filtro foi enviado
if (isset($_POST['filtro_dia'])) {
    $filtroDia = $_POST['filtro_dia'];
}

$queryFrequenciaUsuario = "SELECT f.nome, f.dia, f.hora, f.turno, f.presenca, u.tipo_usuario 
                           FROM frequencia f 
                           JOIN usuarios u ON f.nome = u.nome 
                           WHERE f.nome = '$usuarioLogado' AND f.dia = '$filtroDia'";

$resultFrequenciaUsuario = mysqli_query($conexao, $queryFrequenciaUsuario);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Horas Acumuladas</title>
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
                    <a href="principal.php"><button>Início</button></a>
                    <a href="frequencia.php"><button>Realizar Frequência</button></a>
                    <a href="horas_acumuladas.php"><button id="butao_selecionado">Horas Acumuladas</button></a>
                    <a href="calendario_frequencia.php"><button>Calendário Frequência</button></a>
                    <a href="perfil.php"><button>Perfil</button></a>
                    <a href="javascript:void(0);" onclick="confirmarSaida();"><button class="butao">Sair</button></a>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <section class="w-100 d-flex flex-column 
        justify-content-center align-items-center text-dark fs-5">

            <div class="container">
                <form method="post" action="frequencia_funcionarios.php" class="row g-3">
                    <div class="col-md-4 text-center">
                        <label for="filtro_dia" class="form-label">Filtrar por Dia:</label>
                        <input type="date" name="filtro_dia" id="filtro_dia" class="form-control" value="<?php echo $filtroDia; ?>">
                    </div>
                    <div class="col-md-4 text-center">
                        <button type="submit" class="btn btn-custom-color px-5 py-2 mt-4">Filtrar</button>
                    </div>
                </form>
            </div>

            <table class="table w-75 table-bordered">
                <div class="card-body">
                    <thead>
                        <h3>Frequência de <?php echo $usuarioLogado; ?> no Dia <?php echo $filtroDia; ?></h3>
                        <tr class="table-info">
                            <th scope="col">Função</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Dia</th>
                            <th scope="col">Hora</th>
                            <th scope="col">Turno</th>
                            <th scope="col">Presença</th>
                            <th scope="col">Tipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($rowFuncionario = mysqli_fetch_assoc($resultFrequenciaUsuario)) {
                            echo "<tr>";
                            echo "<td>" . $rowFuncionario['tipo_usuario'] . "</td>";
                            echo "<td>" . $rowFuncionario['nome'] . "</td>";
                            echo "<td>" . $rowFuncionario['dia'] . "</td>";
                            echo "<td>" . $rowFuncionario['hora'] . "</td>";
                            echo "<td>" . $rowFuncionario['turno'] . "</td>";
                            echo "<td>" . $rowFuncionario['presenca'] . "</td>";
                            echo "<td>" . (($rowFuncionario['presenca'] == 'Presente') ? 'Entrada' : 'Faltou') . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </div>
            </table>
        </section>
    </main>

    <script type="text/javascript" src="js/funcoes.js"></script>
</body>

</html>