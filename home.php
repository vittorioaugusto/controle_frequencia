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

    <link rel="stylesheet" href="assets/css/style.css">
    <title>Home</title>
</head>

<body class="d-flex flex-column vh-100">

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <h1 class="navbar-brand no-hover-color">Frequência Master<i class="fa fa-check-circle-o ms-1" aria-hidden="true"></i></h1>
            <div class="d-flex flex-column flex-lg-row justify-content-center align-items-center gap-2">
                <a href="login.php" class="btn botao_nav-btn px-3 py-2">Login</a>
                <a href="cadastro_administrador.php" class="btn botao_nav-btn px-3 py-2">Criar Conta</a>
            </div>
        </div>
    </nav>

    <section class="hero-section mt-5">
        <div class="container-fluid text-center">
        <h2>Bem-vindo ao Frequência Master<i class="fa fa-check-circle-o ms-1" aria-hidden="true"></i></h2>
            <p>Gerencie a frequência dos seus colaboradores de forma eficiente.</p>
        </div>
    </section>

    <section class="features-section mt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5 mb-4 me-4 d-flex justify-content-center p-3" style="background-color: #6E2CF3; color: #fff; border-radius: 20px;">
                    <div>
                        <h3 class="text-center">Registro de Frequência</h3>
                        <ul class="mt-3">
                            <li>Permite aos usuários registrarem sua presença de <strong>Entrada</strong> e <strong>Saída</strong>.</li>
                            <li>O Funcionário pode filtrar a sua frequência pela data em que realizou</li>
                            <li>Útil para controle de presença em instituições educacionais ou locais de trabalho.</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-5 mb-4 me-4 d-flex justify-content-center p-3" style="background-color: #6E2CF3; color: #fff; border-radius: 20px;">
                    <div>
                        <h3 class="text-center">Calendário de Frequência</h3>
                        <ul class="mt-3">
                            <li>O Calendário permite aos funcionários visualizarem suas frequências durante o ano.</li>
                            <li>O Administrador também possui acesso e vizualizam a frquência de todos os funcionários durante o ano.</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-5 mb-4 d-flex justify-content-center p-3" style="background-color: #6E2CF3; color: #fff; border-radius: 20px;">
                    <div>
                        <h3 class="text-center">Gerenciamento de Usuários</h3>
                        <ul class="mt-3">
                            <li>Os Administradores fazem o cadastro do usuário, com os dados: nome, email, senha, cpf, telefone, a função e o turno</li>
                            <li>Os Administradores podem filtrar todos os funcionários, com a funcionalidade de "Desativar" e "Ativar" o funcionário.</li>
                            <li>Os Administradores podem filtrar a frequência de todos os funcionários</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="mt-auto">
        <div class="container text-center">
            <p>Copyright© 2024 | vittorioaugusto - Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script type="text/javascript" src="assets/js/script.js"></script>
</body>

</html>
