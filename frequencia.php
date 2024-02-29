<?php
session_start();
include 'conexao.php';

// Defina o fuso horário para o de São Paulo (ou o fuso horário apropriado)
date_default_timezone_set('America/Sao_Paulo');

if (!isset($_SESSION['nome']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: index.php");
    exit();
}

$tipo_usuario = $_SESSION['tipo_usuario'];
$nome = $_SESSION['nome'];

if ($tipo_usuario === 'Administrador') {
    header("Location: principal.php");
    exit();
}

// Consulta para recuperar a última frequência do usuário no mesmo dia
$query = "SELECT nome, tipo_usuario, dia, hora, turno, presenca 
          FROM frequencia 
          WHERE nome = '$nome' AND dia = CURDATE()
          ORDER BY hora DESC 
          LIMIT 1";

$result = mysqli_query($conexao, $query);

// Verifique se a consulta retornou resultados
if ($row = mysqli_fetch_assoc($result)) {
    $ultimaFrequenciaData = $row['dia'];
    $ultimaFrequenciaHora = $row['hora'];
    // Agora você pode exibir os dados da última frequência realizada pelo usuário
} else {
    // Não há registros de frequência para o usuário no mesmo dia
}

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

    <link rel="stylesheet" href="style.css">
    <title>Realizar Frequência</title>
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
                    <h3 class="offcanvas-title" id="offcanvasNavbarLabel">Frequência Master<i class="fa fa-check-circle-o ms-1" aria-hidden="true"></i></h3>
                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>

                <div class="offcanvas-body d-flex flex-column flex-lg-row p-3 p-lg-0">
                    <ul class="navbar-nav justify-content-center align-items-center fs-5 flex-grow-1 pe-3">
                        <li class="nav-item mx-1">
                            <a class="nav-link" href="principal.php">Home</a>
                        </li>
                        <?php
                        if ($_SESSION['tipo_usuario'] !== 'Administrador') {
                            echo ' <li class="nav-item mx-1">
                            <a class="nav-link active" style="background-color: #8a50ff" aria-current="page" href="frequencia.php">Realizar Frequência</a>
                        </li>';
                            echo '<li class="nav-item mx-1">
                            <a class="nav-link" href="minha_frequencia.php">Minha Frequência</a>
                        </li>';
                        } else {
                            echo '<li class="nav-item mx-1">
                            <a class="nav-link" href="cadastro.php">Cadastrar Funcionário</a>
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
                            <a class="nav-link" href="calendario_frequencia.php">Calendário de Frequência</a>
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


    <?php
    // Verifique se o usuário já realizou uma frequência de entrada e uma de saída no mesmo dia
    $queryContagem = "SELECT COUNT(*) AS numFrequencias FROM frequencia WHERE nome = '$nome' AND dia = CURDATE()";
    $resultContagem = mysqli_query($conexao, $queryContagem);

    if ($rowContagem = mysqli_fetch_assoc($resultContagem)) {
        $numFrequencias = $rowContagem['numFrequencias'];

        // Defina o limite de frequência (1 entrada e 1 saída)
        $limiteFrequencia = 2;

        if ($numFrequencias >= $limiteFrequencia) {
            echo " <div class='container mt-3'>";
            echo "<p class='alert alert-danger'>Você atingiu o limite de frequências para hoje.</p>";
            echo "</div>";
        } else {
    ?>
            <div class="container mt-3">
                <div class="p-1">
                    <div class="card-body text-center">
                        <form action="processar_frequencia.php" method="POST">
                            <h3 class="card-title mb-1 text-center">Registre sua Frequência</h3>
                            <button type="submit" value="Registrar Frequência" id="registrarFrequenciaBtn" class="btn btn-primary px-5 py-2 mt-3">Registrar Frequência</button>
                        </form>
                    </div>
                </div>
            </div>
    <?php
        }
    }
    ?>

    <div class="container">
        <div class="card-body text-center">
        <?php if (isset($ultimaFrequenciaData) && isset($ultimaFrequenciaHora)) : ?>
            <h4>Última Frequência Realizada</h4>
            <p>Data: <?php echo $ultimaFrequenciaData; ?></p>
            <p>Hora: <?php echo $ultimaFrequenciaHora; ?></p>
        <?php endif; ?>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="js/funcoes.js"></script>
</body>

</html>