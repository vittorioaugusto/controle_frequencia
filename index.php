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
    <title>Login</title>
</head>

<body class="vh-100">

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <h1 class="navbar-brand no-hover-color">Frequência Tech<i class="fa fa-check-circle-o ms-1" aria-hidden="true"></i></h1>
        </div>
    </nav>

    <div class="container">
        <div class="card p-4 mt-5">
            <div class="card-body">
                <form action="processar_login.php" method="POST">
                    <h2 class="card-title mb-4 text-center">Login</h2>
                    <div class="mb-3 input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope fa-1x"></i>
                        </span>
                        <label for="email" class="form-label visually-hidden">Email:</label>
                        <input type="text" name="email" class="form-control" placeholder="Digite seu email" required>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock fa-1x"></i>
                        </span>
                        <label for="senha" class="form-label visually-hidden">Senha:</label>
                        <input type="password" name="senha" id="senha" class="form-control" placeholder="Digite sua senha" required>
                    </div>
                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="mostrarSenha" onclick="mostrarOcultarSenha()">
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/funcoes.js"></script>
</body>

</html>