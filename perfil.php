<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php"); // Redirecione para a página de login se não estiver logado
    exit();
}

include 'conexao.php';

// Recupere os dados do usuário da sessão
$nome = $_SESSION['nome'];
$email = $_SESSION['email'];
$telefone = $_SESSION['telefone'];
$turno = $_SESSION['turno'];
$tipo_usuario = $_SESSION['tipo_usuario'];

?>

<!DOCTYPE html>
<html>

<head>
    <title>Perfil</title>
</head>

<body>
    <header>
        <nav>
            <div class="logo">
                <div class="coin"></div>
                <h1 id="titulo">Sistema de Frequência</h1>
            </div>
            <div class="bem_vindo_nome">
                <!-- <p>Tipo de Usuário: <?php echo $tipo_usuario; ?></p> -->
            </div>
            <div class="botao_nav">
                <ul>
                    <a href="principal.php"> <button>Início</button></a>
                    <?php
                    // Verifique se o usuário não é um administrador
                    if ($_SESSION['tipo_usuario'] !== 'Administrador') {
                        echo '<a href="frequencia.php"><button>Realizar Frequência</button></a>';
                    } else {
                        echo '<a href="cadastro.php"><button>Cadastrar Funcionário</button></a>';
                        echo '<a href="funcionarios.php"><button>Funcionários</button></a>';
                        echo '<a href="frequencia_funcionarios.php"><button>Frequência dos funcionários</button></a>';
                    }
                    ?>
                    <a href="calendario_frequencia.php"><button>Calendário de Frequência</button></a>
                    <a href="perfil.php"><button>Perfil</button></a>
                    <a href="javascript:void(0);" onclick="confirmarSaida();"> <button class="butao">Sair</button></a>
                </ul>
            </div>
        </nav>
    </header>

    <h2>Meu Perfil</h2>
    <p>Nome: <?php echo $nome; ?></p>
    <p>Email: <?php echo $email; ?></p>
    <p>Telefone: <?php echo $telefone; ?></p>
    <p>Tipo de Usuário: <?php echo $tipo_usuario; ?></p>
    <p>Turno: <?php echo $turno; ?></p>
    


    <script type="text/javascript" src="js/funcoes.js"></script>
</body>

</html>