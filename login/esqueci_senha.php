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
    <title>Redefinir Senha</title>
</head>

<body class="vh-100">

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <h1 class="navbar-brand no-hover-color">Frequência Master<i class="fa fa-check-circle-o ms-1" aria-hidden="true"></i></h1>
        </div>
    </nav>

    <div class="container d-flex justify-content-center align-items-center border-dark">
        <div class="card p-4 mt-5 border-dark">
            <div class="card-body">
                <form action="processar_redefinir_senha.php" method="POST">
                    <h3 class="card-title mb-4 text-center">Redefinir Senha</h3>
                    <div class="mb-3 input-group border-dark">
                        <span class="input-group-text border-dark">
                            <i class="fas fa-envelope fa-1x"></i>
                        </span>
                        <label for="email" class="form-label visually-hidden">Email:</label>
                        <input type="text" name="email" class="form-control border-dark" placeholder="Digite seu email" required>
                    </div>
                    <div class="mb-3 input-group border-dark">
                        <span class="input-group-text border-dark">
                            <i class="fas fa-lock fa-1x"></i>
                        </span>
                        <label for="senha" class="form-label visually-hidden">Nova Senha:</label>
                        <input type="text" name="nova_senha" id="senha" class="form-control border-dark" placeholder="Digite sua Nova Senha" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-custom-color px-4 py-2">Atualizar senha</button>
                    </div>
                    <div class="text-center">
                        <a href="login.php" class="btn btn-custom-color px-3 py-1 mt-2">Voltar</a>
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