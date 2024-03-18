<?php
session_start();
include '../SQL/conexao.php';

// Verifique se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: ../login/login.php");
    exit();
}

// Verifique se o usuário é um administrador
if ($_SESSION['tipo_usuario'] !== 'Administrador') {
    header("Location: ../principal.php");
    exit();
}

// Consulta SQL para obter todos os tipos de usuário, exceto administrador
$queryUsuarios = "SELECT DISTINCT tipo_usuario FROM usuario WHERE tipo_usuario != 'Administrador'";
$resultUsuarios = mysqli_query($conexao, $queryUsuarios);
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

    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Frequência dos Funcionários</title>
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
                            <a class="nav-link" href="../principal.php">Início</a>
                        </li>
                        <?php
                        if ($_SESSION['tipo_usuario'] !== 'Administrador') {
                            echo ' <li class="nav-item mx-1">
                            <a class="nav-link" href="../frequencia/realizar_frequencia.php">Realizar Frequência</a>
                        </li>';
                            echo '<li class="nav-item mx-1">
                            <a class="nav-link" href="../frequencia/minha_frequencia.php">Minha Frequência</a>
                        </li>';
                        } else {
                            echo '<li class="nav-item mx-1">
                            <a class="nav-link" href="../cadastro/cadastro_funcionario.php">Cadastrar Funcionário</a>
                        </li>';
                            echo ' <li class="nav-item mx-1">
                            <a class="nav-link" href="../usuario/funcionarios.php">Funcionários</a>
                        </li>';
                            echo '<li class="nav-item mx-1">
                            <a class="nav-link active" style="background-color: #8a50ff" aria-current="page" href="../frequencia/frequencia_funcionarios.php">Frequência dos Funcionários</a>
                        </li>';
                        }
                        ?>
                        <li class="nav-item mx-1">
                            <a class="nav-link" href="../frequencia/calendario_frequencia.php">Calendário de Frequência</a>
                        </li>
                        <li class="nav-item mx-1">
                            <a class="nav-link" href="../usuario/perfil.php">Perfil</a>
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
        <div class="p-1 mt-3">
            <div class="card-body">
                <form method="post" action="frequencia_funcionarios.php" class="row g-3">
                    <h3 class="card-title mb-1 text-center">Filtrar Frequência dos Funcionários:</h3>

                    <div class="col-md-4 text-center">
                        <label for="usuario" class="form-label">Selecione o usuário:</label>
                        <select name="usuario" id="usuario" class="form-select">
                            <option value="">Todos os Usuários</option>
                            <?php
                            // Consulta SQL para obter os nomes dos usuários
                            $queryNomesUsuarios = "SELECT DISTINCT f.nome FROM frequencia f";
                            $resultNomesUsuarios = mysqli_query($conexao, $queryNomesUsuarios);

                            while ($rowNomeUsuario = mysqli_fetch_assoc($resultNomesUsuarios)) {
                                $nomeUsuario = $rowNomeUsuario['nome'];
                                echo "<option value='$nomeUsuario'>$nomeUsuario</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4 text-center">
                        <label for="data" class="form-label">Data:</label>
                        <input type="date" name="data" id="data" class="form-control">
                    </div>
                    <div class="col-md-4 text-center">
                        <button type="submit" class="btn btn-custom-color px-5 py-2 mt-4">Filtrar</button>
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
                            if (isset($_POST['usuario'])) {
                                $usuarioSelecionado = $_POST['usuario'];
                                if ($usuarioSelecionado === "") {
                                    echo "Todos os Usuários";
                                } else {
                                    echo $usuarioSelecionado;
                                }
                            } else {
                                echo "Todos os Usuários";
                            }
                            ?>
                        </h3>
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
                        // Inicialize a variável $presencasPorDia
                        $presencasPorDia = array();
                        
                        // Inicialize as variáveis para contar a presença e a ausência
                        $presencasContador = array();
                        $ausenciasContador = array();

                        // Verificar se um usuário específico está selecionado para evitar a exibição quando todos os usuários estão filtrados
                        $usuarioSelecionado = isset($_POST['usuario']) ? $_POST['usuario'] : '';
                        $exibirContador = !empty($usuarioSelecionado);

                        // Verifique se os critérios de filtro foram enviados
                        if (isset($_POST['usuario']) || isset($_POST['data'])) {
                            $nomeUsuarioSelecionado = $_POST['usuario'];
                            $dataSelecionada = $_POST['data'];

                            // Construa a parte condicional da consulta SQL com base nos critérios
                            $condicao = '';

                            if (!empty($nomeUsuarioSelecionado) && !empty($dataSelecionada)) {
                                $condicao = "WHERE f.nome = '$nomeUsuarioSelecionado' AND f.dia = '$dataSelecionada'";
                            } elseif (!empty($nomeUsuarioSelecionado)) {
                                $condicao = "WHERE f.nome = '$nomeUsuarioSelecionado'";
                            } elseif (!empty($dataSelecionada)) {
                                $condicao = "WHERE f.dia = '$dataSelecionada'";
                            }

                            // Consulta SQL para obter os funcionários com base nos critérios de filtro
                            $queryFuncionarios = "SELECT f.nome, f.dia, f.hora, f.turno, f.presenca, u.tipo_usuario FROM frequencia f JOIN usuario u ON f.nome = u.nome $condicao";
                            $resultFuncionarios = mysqli_query($conexao, $queryFuncionarios);

                            while ($rowFuncionario = mysqli_fetch_assoc($resultFuncionarios)) {
                                echo "<tr>";
                                echo "<td>" . $rowFuncionario['tipo_usuario'] . "</td>";
                                echo "<td>" . $rowFuncionario['nome'] . "</td>";
                                echo "<td>" . $rowFuncionario['dia'] . "</td>";
                                echo "<td>" . $rowFuncionario['hora'] . "</td>";
                                echo "<td>" . $rowFuncionario['turno'] . "</td>";
                                echo "<td>" . $rowFuncionario['presenca'] . "</td>";
                                echo "<td>";

                                // Determinar se a presença é uma entrada ou saída com base no número de presenças
                                if ($rowFuncionario['presenca'] == 'Presente') {
                                    if (!isset($presencasPorDia[$rowFuncionario['nome']][$rowFuncionario['dia']])) {
                                        $presencasPorDia[$rowFuncionario['nome']][$rowFuncionario['dia']] = 0;
                                    }

                                    // Contar apenas se a frequência for realizada duas vezes no mesmo dia
                                    if ($presencasPorDia[$rowFuncionario['nome']][$rowFuncionario['dia']] == 1) {
                                        // Incrementar o contador de presença
                                        if (!isset($presencasContador[$rowFuncionario['nome']])) {
                                            $presencasContador[$rowFuncionario['nome']] = 0;
                                        }
                                        $presencasContador[$rowFuncionario['nome']]++;
                                    }

                                    $tipo = '';
                                    if ($presencasPorDia[$rowFuncionario['nome']][$rowFuncionario['dia']] % 2 == 0) {
                                        $tipo = "Entrada";
                                    } else {
                                        $tipo = "Saída";
                                    }

                                    echo $tipo;

                                    $presencasPorDia[$rowFuncionario['nome']][$rowFuncionario['dia']]++;
                                } else {
                                    echo "Faltou"; // Caso a presença não seja "Presente"
                                    // Incrementar o contador de ausência
                                    if (!isset($ausenciasContador[$rowFuncionario['nome']])) {
                                        $ausenciasContador[$rowFuncionario['nome']] = 0;
                                    }
                                    $ausenciasContador[$rowFuncionario['nome']]++;
                                }

                                echo "</td>";
                                echo "</tr>";
                            }
                        }

                        // Loop sobre ambos os contadores
                        foreach (array('presencasContador', 'ausenciasContador') as $contador) {
                            foreach ($$contador as $usuario => $quantidade) {
                                // Verificar se deve exibir o contador
                                if ($exibirContador) {
                                    $status = ($contador == 'presencasContador') ? 'presente' : 'ausente';
                                    echo "<p>$usuario esteve $status $quantidade vezes.</p>";
                                }
                            }
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
    <script type="text/javascript" src="../assets/js/script.js"></script>
</body>

</html>