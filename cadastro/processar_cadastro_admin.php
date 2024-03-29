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
    
    <title>Processar Cadastro Administrador</title>
</head>

<body class="vh-100">

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <h1 class="navbar-brand no-hover-color">Frequência Master<i class="fa fa-check-circle-o ms-1" aria-hidden="true"></i></h1>
        </div>
    </nav>

    <?php
    session_start();
    include '../SQL/conexao.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST["nome"];
        $email = $_POST["email"];
        $senha = $_POST["senha"];
        $cpf = $_POST["cpf"];
        $telefone = $_POST["telefone"];
        $turno = $_POST["turno"];
        $tipo_usuario = $_POST["tipo_usuario"];

        // Verificar se o tipo de usuário é um administrador
        if ($tipo_usuario == "Administrador") {
            // Realizar o cadastro no banco de dados
            $query = "INSERT INTO usuario (nome, email, senha, cpf, telefone, tipo_usuario, turno) VALUES ('$nome', '$email', '$senha', '$cpf', '$telefone', '$tipo_usuario', '$turno')";
            $result = mysqli_query($conexao, $query);

            if ($result) {
                // Cadastro realizado com sucesso
                echo "
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Cadastro realizado com sucesso!',
                    showConfirmButton: false,
                    timer: 2000
                }).then(function() {
                    window.location.href = '../login/login.php';
                });
            </script>";
                exit();
            } else {
                // Erro no cadastro
                echo "Erro ao cadastrar. Por favor, tente novamente.";
            }
        } else {
            // Usuário não autorizado
            echo "Você não tem permissão para cadastrar um usuário com esse tipo.";
        }
    }
    ?>

</body>

</html>