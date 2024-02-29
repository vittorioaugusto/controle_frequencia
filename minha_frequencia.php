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
$queryFrequenciaUsuario = "SELECT f.nome, f.dia, f.hora, f.turno, f.presenca, u.tipo_usuario 
                           FROM frequencia f 
                           JOIN usuarios u ON f.nome = u.nome 
                           WHERE f.nome = '$usuarioLogado'";

// Verificar se o formulário de filtro foi enviado
if (isset($_POST['filtrarPorData'])) {
    $filtroDia = $_POST['date'];
    $queryFrequenciaUsuario .= " AND f.dia = '$filtroDia'";
}

$resultFrequenciaUsuario = mysqli_query($conexao, $queryFrequenciaUsuario);
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
    <title>Minha Frequência</title>
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
                            <a class="nav-link" href="realizar_frequencia.php">Realizar Frequência</a>
                        </li>';
                            echo '<li class="nav-item mx-1">
                            <a class="nav-link active" style="background-color: #8a50ff" aria-current="page" href="minha_frequencia.php">Minha Frequência</a>
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

    <div class="container">
        <div class="p-2 mt-3">
            <div class="card-body">
                <form method="POST" action="" class="row g-3" onsubmit="return validarFormularioData();">
                    <div class="col-md-5">
                        <label for="filtrarTodos" class="form-label"></label>
                        <div class="text-center">
                            <button type="submit" name="filtrarTodos" value="Mostrar Todos" class="btn btn-custom-color px-4 py-2 mt-3">Filtrar Todas as datas</button>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <label for="filtrarTodos" class="form-label">Selecione a data</label>
                        <div class="mb-1">
                            <input type="date" name="date" id="date" class="form-control">
                        </div>
                        <div class="text-center">
                            <button type="submit" name="filtrarPorData" class="btn btn-custom-color px-5 py-2 mt-4">Filtrar</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <main>
        <section class="w-100 d-flex flex-column 
        justify-content-center align-items-center text-dark fs-5">

            <table class="table w-75 table-bordered">
                <div class="card-body">
                    <thead>
                        <h3>Minha Frequência</h3>
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="js/funcoes.js"></script>
</body>

</html>