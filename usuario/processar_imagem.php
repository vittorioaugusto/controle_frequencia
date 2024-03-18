<?php
session_start();
include '../SQL/conexao.php';

// Verifique se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: ../login/login.php");
    exit();
}

// Recupere os dados do usuário da sessão
$nome = $_SESSION['nome'];
$email = $_SESSION['email'];
$telefone = $_SESSION['telefone'];
$turno = $_SESSION['turno'];
$tipo_usuario = $_SESSION['tipo_usuario'];

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

    <!-- Bootstrap JS link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script type="text/javascript" src="../assets/js/script.js"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Processar Imagem de Perfil</title>
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
                            echo '<li class="nav-item mx-1">
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
                            <a class="nav-link" href="../frequencia/frequencia_funcionarios.php">Frequência dos Funcionários</a>
                        </li>';
                        }
                        ?>
                        <li class="nav-item mx-1">
                            <a class="nav-link" href="../frequencia/calendario_frequencia.php">Calendário de Frequência</a>
                        </li>
                        <li class="nav-item mx-1">
                            <a class="nav-link active" style="background-color: #8a50ff" aria-current="page" href="../usuario/perfil.php">Perfil</a>
                        </li>
                    </ul>
                    <div class="d-flex flex-column flex-lg-row justify-content-center align-items-center gap-3">
                        <a href="javascript:void(0);" onclick="confirmarSaida();" class="text-white text-decoration-none px-3 py-1 rounded-4 sair-btn" title="Sair"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <?php

    // Diretório onde as imagens serão salvas (altere para o caminho real no seu servidor)
    $diretorio_destino = '../usuario/imagens_perfil/';

    // Nome do arquivo
    $nome_arquivo = $_SESSION['nome'] . '_perfil.jpg';

    // Caminho completo do arquivo no servidor
    $caminho_completo = $diretorio_destino . $nome_arquivo;

    // Mensagem de alerta padrão
    $alert_message = "Nenhum arquivo enviado.";

    // Verifica se foi enviado um arquivo
    if (isset($_FILES['imagem'])) {
        // Garante que o diretório de destino exista
        if (!file_exists($diretorio_destino)) {
            mkdir($diretorio_destino, 0755, true);
        }

        // Move o arquivo para o diretório de destino
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_completo)) {
            $alert_message = "Upload da imagem realizado com sucesso.";
        } else {
            $alert_message = "Selecione uma imagem para realizar o upload.";
            echo "<script>
                Swal.fire({
                    title: 'Atenção',
                    text: '$alert_message',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../usuario/perfil.php';
                    }
                });
            </script>";
            exit();
        }
    }

    echo "<script>
        Swal.fire({
            title: 'Sucesso',
            text: '$alert_message',
            icon: 'success',
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            window.location.href = '../usuario/perfil.php';
        });
    </script>";

    ?>

</body>

</html>