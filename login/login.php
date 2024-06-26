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

    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Login</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a href="../home.php" class="text-decoration-none">
                <h1 class="navbar-brand no-hover-color">Frequência Master<i class="fa fa-check-circle-o ms-1" aria-hidden="true"></i></h1>
            </a>
            <div class="d-flex flex-column flex-lg-row justify-content-center align-items-center gap-2">
                <a href="../home.php" class="btn botao_nav-btn px-3 py-2">Home</a>
                <a href="../cadastro/cadastro_administrador.php" class="btn botao_nav-btn px-3 py-2">Cadastrar Administrador</a>
            </div>
        </div>
    </nav>

    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="card p-4 border-dark">
            <div class="card-body">
                <form action="processar_login.php" method="POST">
                    <h2 class="card-title mb-4 text-center">Login</h2>
                    <div class="mb-3 input-group">
                        <span class="input-group-text border-dark">
                            <i class="fas fa-envelope fa-1x"></i>
                        </span>
                        <label for="email" class="form-label visually-hidden">Email:</label>
                        <input type="text" name="email" class="form-control border-dark" placeholder="Digite seu email" required>
                    </div>
                    <div class="mb-2 input-group">
                        <span class="input-group-text border-dark">
                            <i class="fas fa-lock fa-1x"></i>
                        </span>
                        <label for="senha" class="form-label visually-hidden">Senha:</label>
                        <input type="password" name="senha" id="senha" class="form-control border-dark" placeholder="Digite sua senha" required>
                    </div>
                    <div class="ms-1 mb-4 form-check">
                        <input type="checkbox" class="form-check-input border-dark" id="mostrarSenha" onclick="mostrarOcultarSenha()">
                        <label class="form-check-label" for="mostrarSenha">Mostrar Senha</label>
                    </div>
                    <div class="text-center">
                        <a id="esqueci_senha" href="esqueci_senha.php" class="d-block mb-3">Esqueceu a senha?</a>
                        <button type="submit" class="btn btn-custom-color px-5 py-2">Entrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Bootstrap JS link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script type="text/javascript" src="../assets/js/script.js"></script>
</body>

</html>