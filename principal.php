<?php
session_start();
include 'conexao.php';

// Verifique se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: index.php");
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
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Biblioteca icones -->
    <script src="https://kit.fontawesome.com/f2c34800e3.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="style.css">
    <title>Início</title>
</head>

<body class="vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">

            <h1 class="navbar-brand no-hover-color">Frequência Tech<i class="fa fa-check-circle-o ms-1" aria-hidden="true"></i></h1>

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
                            <a class="nav-link active" href="principal.php" aria-current="page" href="principal.php">Home</a>
                        </li>
                        <?php
                        if ($_SESSION['tipo_usuario'] !== 'Administrador') {
                            echo ' <li class="nav-item mx-1">
                            <a class="nav-link" href="frequencia.php">Realizar Frequência</a>
                        </li>';
                            echo '<li class="nav-item mx-1">
                            <a class="nav-link" href="horas_acumuladas.php">Horas Acumuladas</a>
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
                        <a href="javascript:void(0);" onclick="confirmarSaida();" class="text-white text-decoration-none px-3 py-1 rounded-4" style="background-color: #f10000">Sair</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main>
        <section class="w-100 d-flex flex-column 
        justify-content-center align-items-center text-dark fs-5">

            <div class="inicio_text p-3">
                <h2>Bem vindo (a): <?php echo $_SESSION['nome']; ?></h2>
                <div class="d-flex flex-column p-lg-0 justify-content-center align-items-center">
                    <p>Tipo de Usuário: <?php echo $_SESSION['tipo_usuario']; ?></p>
                    <p>Turno: <?php echo $_SESSION['turno']; ?></p>
                </div>
            </div>

            <table class="table w-50 border">
                <div class="card-body">
                    <thead>
                        <tr class="table-info">
                            <?php
                            if ($_SESSION['tipo_usuario'] === 'Administrador') {
                                echo '<h3>Frequência de Todos os Funcionários</h3>';
                            } else {
                                echo '<h3>Minha Frequência</h3>';
                            }
                            ?>
                            <th scope="col">Função</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Data</th>
                            <th scope="col">Hora</th>
                            <th scope="col">Presença</th>
                            <th scope="col">Tipo</th>
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
                            echo "<td>" . $row['presenca'] . "</td>";
                            echo "<td>";

                            // Determinar se a presença é uma entrada ou saída com base no número de presenças
                            if ($row['presenca'] == 'Presente') {
                                if ($presencasPorDia[$nomeFuncionario][$dataPresenca] % 2 == 0) {
                                    echo "<span class='bg-success'>Entrada</span>";
                                } else {
                                    echo "<span class='bg-saida'>Saída</span>";
                                }
                                $presencasPorDia[$nomeFuncionario][$dataPresenca]++;
                            } else {
                                echo "<span class='bg-faltou'>Faltou</span>";
                            }

                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </div>
            </table>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/funcoes.js"></script>
</body>

</html>