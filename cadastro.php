<?php
session_start();
include 'conexao.php';

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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />

    <link rel="stylesheet" href="style.css">
    <title>Cadastrar Funcionário</title>
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
                            <a class="nav-link active" href="cadastro.php">Cadastrar Funcionário</a>
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
                            <a href="javascript:void(0);" onclick="confirmarSaida();" class="text-white text-decoration-none px-3 py-1 rounded-4 sair-btn">Sair</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

    </header>

    <div class="container">
        <div class="card p-2 mt-3">
            <div class="card-body">
                <form action="processar_cadastro.php" method="POST">
                    <h4 class="card-title mb-3 p-2 text-center">Cadastrar Funcionário</h4>
                    <div class="mb-3 input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user fa-1x"></i>
                        </span>
                        <label for="nome" class="form-label visually-hidden">Nome:</label>
                        <input type="text" name="nome" class="form-control" placeholder="Digite o nome" required>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope fa-1x"></i>
                        </span>
                        <label for="email" class="form-label visually-hidden">Email:</label>
                        <input type="email" name="email" class="form-control" placeholder="Digite o email" required>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock fa-1x"></i>
                        </span>
                        <label for="senha" class="form-label visually-hidden">Senha:</label>
                        <input type="text" name="senha" class="form-control" placeholder="Digite a senha" required>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text">
                            <i class="fas fa-id-card fa-1x"></i>
                        </span>
                        <label for="cpf" class="form-label visually-hidden">CPF:</label>
                        <input type="number" name="cpf" class="form-control" placeholder="Digite o cpf" required>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text">
                            <i class="fas fa-phone fa-1x"></i>
                        </span>
                        <label for="telefone" class="form-label visually-hidden">Telefone:</label>
                        <input type="text" name="telefone" class="form-control" placeholder="Digite o telefone" required>
                    </div>
                    <label for="tipo_usuario" class="form-label">Tipo de Usuário:</label>
                    <select name="tipo_usuario" class="form-select" required>
                        <option value="Professor">Professor</option>
                        <option value="Funcionário de suporte ao aluno">Funcionário de suporte ao aluno</option>
                        <option value="Funcionário de manutenção">Funcionário de manutenção</option>
                        <option value="Funcionário de segurança">Funcionário de segurança</option>
                        <option value="Estagiário">Estagiário</option>
                    </select>
                    <label for="turno" class="form-label">Turno:</label>
                    <select name="turno" class="form-select" required>
                        <option value="Manhã">Manhã</option>
                        <option value="Tarde">Tarde</option>
                        <option value="Noite">Noite</option>
                        <option value="Integral">Integral</option>
                    </select>
                    <div class="text-center">
                        <button type="submit" class="btn btn-custom-color px-5 py-2 mt-3" value="Cadastrar">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script type="text/javascript" src="js/funcoes.js"></script>
</body>

</html>