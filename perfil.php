<?php
session_start();
include 'SQL/conexao.php';

// Verifique se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: index.php"); // Redirecione para a página de login se não estiver logado
    exit();
}

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="assets/css/style.css">
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
                        echo '<a href="horas_acumuladas.php"><button>Horas Acumuladas</button></a>';
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

    <?php
    // Defina as variáveis $diretorio_destino e $nome_arquivo
    $diretorio_destino = 'assets/imagens_perfil/';
    $nome_arquivo = $_SESSION['nome'] . '_perfil.jpg';

    if (file_exists($diretorio_destino . $nome_arquivo)) {
        echo '<img class="imagem_perfil" src="' . $diretorio_destino . $nome_arquivo . '" alt="Imagem de Perfil">';
    } else {
        echo '<p>Imagem de perfil não encontrada.</p>';
    }
    ?>

    <p>Nome: <?php echo $nome; ?></p>
    <p>Email: <?php echo $email; ?></p>
    <p>Telefone: <?php echo $telefone; ?></p>
    <p>Tipo de Usuário: <?php echo $tipo_usuario; ?></p>
    <p>Turno: <?php echo $turno; ?></p>

    <!-- Adicione este código dentro do formulário existente -->
    <form action="salvar_imagem.php" method="post" enctype="multipart/form-data">
        <label for="imagem">Escolha uma imagem para o perfil:</label>
        <input type="file" name="imagem" id="imagem" accept="image/*">
        <input type="submit" value="Upload Imagem" name="submit">
    </form>

    <script type="text/javascript" src="assets/js/script.js"></script>
</body>

</html>