<?php
session_start();
include 'SQL/conexao.php';

// Verifique se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php");
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Biblioteca icones -->
    <script src="https://kit.fontawesome.com/f2c34800e3.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="assets/css/style.css">
    <title>Início</title>
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
                            <a class="nav-link active" style="background-color: #8a50ff" aria-current="page" href="princiapl.php">Início</a>
                        </li>
                        <?php
                        if ($_SESSION['tipo_usuario'] !== 'Administrador') {
                            echo ' <li class="nav-item mx-1">
                            <a class="nav-link" href="frequencia/realizar_frequencia.php">Realizar Frequência</a>
                        </li>';
                            echo '<li class="nav-item mx-1">
                            <a class="nav-link" href="frequencia/minha_frequencia.php">Minha Frequência</a>
                        </li>';
                        } else {
                            echo '<li class="nav-item mx-1">
                            <a class="nav-link" href="cadastro/cadastro_funcionario.php">Cadastrar Funcionário</a>
                        </li>';
                            echo ' <li class="nav-item mx-1">
                            <a class="nav-link" href="usuario/funcionarios.php">Funcionários</a>
                        </li>';
                            echo '<li class="nav-item mx-1">
                            <a class="nav-link" href="frequencia/frequencia_funcionarios.php">Frequência dos Funcionários</a>
                        </li>';
                        }
                        ?>
                        <li class="nav-item mx-1">
                            <a class="nav-link" href="frequencia/calendario_frequencia.php">Calendário de Frequência</a>
                        </li>
                        <li class="nav-item mx-1">
                            <a class="nav-link" href="usuario/perfil.php">Perfil</a>
                        </li>
                    </ul>
                    <div class="d-flex flex-column flex-lg-row justify-content-center align-items-center gap-3">
                        <a href="javascript:void(0);" onclick="confirmarSaidaPrincipal();" class="text-white text-decoration-none px-3 py-1 rounded-4 sair-btn" title="Sair"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
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

                            // Determinar se a presença é uma entrada ou saída com base no número de presenças
                            if ($row['presenca'] == 'Presente') {
                                if ($presencasPorDia[$nomeFuncionario][$dataPresenca] % 2 == 0) {
                                    echo "<td style='background-color: #28a745; color: #000;'>Entrada</td>"; // Verde para entrada
                                } else {
                                    echo "<td style='background-color: #dc3545; color: #000;'>Saída</td>"; // Vermelho para saída
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script type="text/javascript" src="assets/js/script.js"></script>
</body>

</html>