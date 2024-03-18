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

    <title>Processar Cadastro Funcionários</title>
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
                        <li class="nav-item mx-1">
                            <a class="nav-link active" style="background-color: #8a50ff" aria-current="page" href="frequencia.php">Cadastrar Funcionário</a>
                        </li>
                        <li class="nav-item mx-1">
                            <a class="nav-link" href="frequencia.php">Funcionários</a>
                        </li>
                        <li class="nav-item mx-1">
                            <a class="nav-link" href="frequencia.php">Frequência dos Funcionários</a>
                        </li>
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

    <?php
    session_start();
    include '../SQL/conexao.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        $cpf = $_POST['cpf'];
        $telefone = $_POST['telefone'];
        $tipo_usuario = $_POST['tipo_usuario'];
        $turno = $_POST['turno'];

        // Verificar se o CPF tem exatamente 11 números
        if (strlen($cpf) === 11) {
            // Verificar se o cadastro já existe no banco de dados
            $check_query = "SELECT * FROM usuario WHERE cpf = '$cpf'";
            $check_result = mysqli_query($conexao, $check_query);

            if (mysqli_num_rows($check_result) == 0) {
                // O cadastro não existe, então podemos inserir os dados
                $insert_query = "INSERT INTO usuario (nome, email, senha, cpf, telefone, tipo_usuario, turno) VALUES ('$nome', '$email', '$senha', '$cpf', '$telefone', '$tipo_usuario', '$turno')";

                if (mysqli_query($conexao, $insert_query)) {
                    // Cadastro realizado com sucesso
                    echo "
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Cadastro realizado com sucesso!',
                    showConfirmButton: false,
                    timer: 2000
                }).then(function() {
                    window.location.href = '../usuario/funcionarios.php';
                });
            </script>";
                    exit();
                } else {
                    echo "Erro ao cadastrar: " . mysqli_error($conexao);
                }
            } else {
                echo "Erro ao cadastrar: O CPF já está em uso.";
            }
        } else {
            echo "Erro ao cadastrar: CPF deve ter exatamente 11 números.";
        }
    }
    ?>

</body>

</html>