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

    <script type="text/javascript" src="assets/js/script.js"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Processar Login</title>
</head>

<body class="vh-100">

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <h1 class="navbar-brand no-hover-color">Frequência Master<i class="fa fa-check-circle-o ms-1" aria-hidden="true"></i></h1>
            <div class="d-flex flex-column flex-lg-row justify-content-center align-items-center">
                <a href="cadastro_administrador.php" class="btn cadastro-btn px-3 py-2">Cadastrar Administrador</a>
            </div>
        </div>
    </nav>

    <?php
    session_start();
    include 'SQL/conexao.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $query = "SELECT * FROM usuario WHERE email = '$email' AND senha = '$senha'";
        $result = mysqli_query($conexao, $query);


        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            if ($row['status'] == 1) {
                $_SESSION['nome'] = $row['nome'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['cpf'] = $row['cpf'];
                $_SESSION['telefone'] = $row['telefone'];
                $_SESSION['tipo_usuario'] = $row['tipo_usuario'];
                $_SESSION['turno'] = $row['turno'];

                header("Location: principal.php");
                exit();
            } else {
                // Usuário desativado, exiba uma mensagem de erro
                $alert_message = "Sua conta está desativada. Entre em contato com o administrador.";
                // Exibe o alerta usando JavaScript com SweetAlert2
                echo "<script>
                Swal.fire({
                    title: 'Atenção',
                    text: '$alert_message',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (!result.isConfirmed) {
                        window.location.href = 'login.php';
                    } else {
                        setTimeout(function() {
                            window.location.href = 'login.php';
                        }, 100);
                    }
                });
            </script>";
                exit();
            }
        }
    }

    $alert_message = "Usuário não encontrado. Verifique o email e tente novamente.";
        echo "<script>
        Swal.fire({
            title: 'Atenção',
            text: '$alert_message',
            icon: 'warning',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (!result.isConfirmed) {
                window.location.href = 'login.php';
            } else {
                setTimeout(function() {
                    window.location.href = 'login.php';
                }, 100);
            }
        });
    </script>
    ";

    ?>

</body>

</html>