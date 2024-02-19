<?php
session_start();
include 'conexao.php';


if ($_SESSION['tipo_usuario'] !== 'Administrador') {
    header("Location: principal.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && isset($_POST['status'])) {
        $usuarioId = $_POST['id'];
        $novoStatus = $_POST['status'];

        // Atualize o status do usuário no banco de dados
        $queryUpdateStatus = "UPDATE usuarios SET status = $novoStatus WHERE id = $usuarioId";
        if (mysqli_query($conexao, $queryUpdateStatus)) {
            header("Location: funcionarios.php");
            exit();
        } else {
            echo "Erro ao atualizar o status do usuário.";
        }
    }
}

$queryUsuarios = "SELECT * FROM usuarios WHERE tipo_usuario != 'Administrador'";
$resultUsuarios = mysqli_query($conexao, $queryUsuarios);
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
    <title>Funcionários</title>
</head>

<body>
    <header>
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
                                <a class="nav-link" href="principal.php" aria-current="page" href="principal.php">Home</a>
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
                            <a class="nav-link active" href="funcionarios.php">Funcionários</a>
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
    </header>

    <div class="container">
        <div class="p-1 mt-3">
            <div class="card-body">
                <form method="post" action="funcionarios.php" onsubmit="return validarFormulario();" class="row g-3">
                    <h3 class="card-title mb-1 text-center">Filtrar Funcionários</h3>
                    <div class="col-md-6">
                        <label for="filtrarTodos" class="form-label"></label>
                        <div class="text-center">
                            <button type="submit" name="filtrarTodos" value="Mostrar Todos" class="btn btn-custom-color px-5 py-2 mt-3">Filtrar Todos</button>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label for="filtrarTodos" class="form-label"></label>
                        <div class="mb-1">
                            <input type="number" name="cpf" id="cpf" class="form-control" placeholder="Digite o CPF">
                        </div>
                        <div class="text-center">
                            <button type="submit" name="filtrarPorCPF" value="Filtrar" class="btn btn-custom-color px-5 py-2 mt-3">Filtrar</button>
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
                        <h3>
                            <?php
                            if (isset($_POST['usuario']) || isset($_POST['cpf'])) {
                                if (isset($_POST['usuario'])) {
                                    $usuarioSelecionado = $_POST['usuario'];
                                    if ($usuarioSelecionado === "") {
                                        echo "Todos os Usuários";
                                    } else {
                                        echo $usuarioSelecionado;
                                    }
                                } elseif (isset($_POST['cpf'])) {
                                    $cpf = $_POST['cpf'];
                                    $queryNomeUsuario = "SELECT nome FROM usuarios WHERE cpf = '$cpf'";
                                    $resultNomeUsuario = mysqli_query($conexao, $queryNomeUsuario);

                                    if ($resultNomeUsuario && mysqli_num_rows($resultNomeUsuario) > 0) {
                                        $rowNomeUsuario = mysqli_fetch_assoc($resultNomeUsuario);
                                        $nomeUsuario = $rowNomeUsuario['nome'];
                                        echo "Usuário: $nomeUsuario";
                                    } else {
                                        echo "Todos os Usuários";
                                    }
                                }
                            } else {
                                echo "Todos os Usuários";
                            }
                            ?>
                        </h3>
                        <tr class="table-info">
                            <th scope="col">Imagem de Perfil</th>
                            <th scope="col">Função</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Telefone</th>
                            <th scope="col">Turno</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ((isset($_POST['usuario']) || isset($_POST['cpf'])) || isset($_POST['filtrarTodos']) && isset($_POST['filtrarPorCPF'])) {
                            $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
                            $cpf = isset($_POST['cpf']) ? $_POST['cpf'] : '';

                            // Construa a parte condicional da consulta SQL com base nos critérios
                            $condicao = "WHERE tipo_usuario != 'Administrador'"; // Adicione essa condição para excluir administradores

                            if (!empty($usuario) && !empty($cpf)) {
                                $usuario = mysqli_real_escape_string($conexao, $usuario);
                                $cpf = mysqli_real_escape_string($conexao, $cpf);
                                $condicao .= " AND nome = '$usuario' AND cpf = '$cpf'";
                            } elseif (!empty($usuario)) {
                                $usuario = mysqli_real_escape_string($conexao, $usuario);
                                $condicao .= " AND nome = '$usuario'";
                            } elseif (!empty($cpf)) {
                                $cpf = mysqli_real_escape_string($conexao, $cpf);
                                $condicao .= " AND cpf = '$cpf'";
                            }

                            // Consulta SQL para obter os funcionários com base nos critérios de filtro
                            $queryFuncionarios = "SELECT id, tipo_usuario, nome, telefone, turno, status FROM usuarios $condicao";
                            $resultFuncionarios = mysqli_query($conexao, $queryFuncionarios);

                            while ($rowFuncionario = mysqli_fetch_assoc($resultFuncionarios)) {
                                echo "<tr>";
                                // Obtenha o nome do arquivo da imagem de perfil
                                $nomeArquivoPerfil = $rowFuncionario['nome'] . '_perfil.jpg';

                                // Construa o caminho completo para a imagem de perfil
                                $caminhoImagemPerfil = 'imagens_perfil/' . $nomeArquivoPerfil;

                                // Adicione a tag <img> com o caminho da imagem de perfil
                                echo "<td><img src='$caminhoImagemPerfil' alt='Imagem de Perfil' style='width: 100px; height: 100px;'></td>";
                                echo "<td>" . $rowFuncionario['tipo_usuario'] . "</td>";
                                echo "<td>" . $rowFuncionario['nome'] . "</td>";
                                echo "<td>" . $rowFuncionario['telefone'] . "</td>";
                                echo "<td>" . $rowFuncionario['turno'] . "</td>";
                                echo "<td>";

                                if ($rowFuncionario['status'] == 1) {
                                    echo "<button class='btn btn-custom-color px-2 py-1 mt-2' onclick=\"alterarStatus(" . $rowFuncionario['id'] . ", 0)\">Desativar</button>";
                                } else {
                                    echo "<button class='btn btn-custom-color px-2 py-1 mt-2' onclick=\"alterarStatus(" . $rowFuncionario['id'] . ", 1)\">Ativar</button>";
                                }

                                echo "</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </div>
            </table>
        </section>
    </main>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="js/funcoes.js"></script>
</body>

</html>