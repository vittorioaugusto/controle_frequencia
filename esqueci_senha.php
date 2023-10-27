<!DOCTYPE html>
<html>
<head>
    <title>Redefinir Senha</title>
</head>
<body>
    <h2>Redefinir Senha</h2>
    <form action="processar_redefinir_senha.php" method="POST">
        <label for="email">Email:</label>
        <input type="text" name="email" required><br>
        <label for="nova_senha">Nova Senha:</label>
        <input type="password" name="nova_senha" id="senha" required><br>
        <span>Mostrar Senha:<input type="checkbox" onclick="mostrarOcultarSenha()"></span><br>
        <button type="submit" value="Redefinir Senha">Redefinir Senha</button>
    </form>
    
    <a href="login.php"><button>Voltar</button></a>
    
    <script type="text/javascript" src="js/funcoes.js"></script>
</body>
</html>
