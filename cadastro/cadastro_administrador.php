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
    <title>Cadastrar Administrador</title>
</head>

<body class="vh-100">

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a href="../home.php" class="text-decoration-none">
                <h1 class="navbar-brand no-hover-color">Frequência Master<i class="fa fa-check-circle-o ms-1" aria-hidden="true"></i></h1>
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="card p-3 mt-3">
            <div class="card-body">
                <form action="processar_cadastro_admin.php" method="POST">
                    <h4 class="card-title mb-2 p-2 text-center">Cadastro Administrador</h4>
                    <div class="mb-2 input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user fa-1x"></i>
                        </span>
                        <label for="nome" class="form-label visually-hidden">Nome:</label>
                        <input type="text" name="nome" class="form-control" placeholder="Digite o nome" required>
                    </div>
                    <div class="mb-2 input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope fa-1x"></i>
                        </span>
                        <label for="email" class="form-label visually-hidden">Email:</label>
                        <input type="email" name="email" class="form-control" placeholder="Digite o email" required>
                    </div>
                    <div class="mb-2 input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock fa-1x"></i>
                        </span>
                        <label for="senha" class="form-label visually-hidden">Senha:</label>
                        <input type="text" name="senha" class="form-control" placeholder="Digite a senha" required>
                    </div>
                    <div class="mb-2 input-group">
                        <span class="input-group-text">
                            <i class="fas fa-id-card fa-1x"></i>
                        </span>
                        <label for="cpf" class="form-label visually-hidden">CPF:</label>
                        <input type="number" name="cpf" class="form-control" placeholder="Digite o cpf" required>
                    </div>
                    <div class="mb-2 input-group">
                        <span class="input-group-text">
                            <i class="fas fa-phone fa-1x"></i>
                        </span>
                        <label for="telefone" class="form-label visually-hidden">Telefone:</label>
                        <input type="tel" name="telefone" class="form-control" pattern="\([0-9]{2}\) [0-9]{4,5}-[0-9]{4}" placeholder="(00) 0000-0000" required>
                    </div>
                    <label for="turno" class="form-label custom-label">Turno:</label>
                    <select name="turno" class="form-select" required>
                        <option value="Manhã">Manhã</option>
                        <option value="Tarde">Tarde</option>
                        <option value="Noite">Noite</option>
                        <option value="Integral">Integral</option>
                    </select>
                    <div class="text-center">
                        <input type="hidden" name="tipo_usuario" value="Administrador">
                        <button type="submit" class="btn btn-custom-color px-5 py-2 mt-4" value="Cadastrar">Cadastrar</button>
                    </div>
                    <div class="text-center">
                        <a href="../login/login.php" class="btn btn-custom-color px-3 py-1 mt-2">Voltar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <!-- jQuery link -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script type="text/javascript" src="../assets/js/script.js"></script>
</body>

</html>