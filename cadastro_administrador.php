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
    <title>Cadastrar Administrador</title>
</head>

<body class="vh-100">

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <h1 class="navbar-brand no-hover-color">Frequência Tech<i class="fa fa-check-circle-o ms-1" aria-hidden="true"></i></h1>
        </div>
    </nav>

    <div class="container">
        <div class="card p-2 mt-3">
            <div class="card-body">
                <form action="processar_cadastro_admin.php" method="POST">
                    <h4 class="card-title mb-2 p-2 text-center">Cadastrar Administrador</h4>
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
                        <input type="tel" name="telefone" class="form-control" pattern="\([0-9]{2}\) [0-9]{4,5}-[0-9]{4}" placeholder="Digite o telefone" required>
                    </div>
                    <div id="telefoneExemplo" class="form-text">Exemplo: (00) 0000-0000</div>
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
                        <a href="index.php" class="btn btn-custom-color px-3 py-1 mt-2">Voltar</a>
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