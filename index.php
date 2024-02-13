<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <h2>Login</h2>
    <form action="processar_login.php" method="POST">
        <label for="email">Email:</label>
        <input type="text" name="email" required><br>
        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required><br>
        <span>Mostrar Senha:<input type="checkbox" onclick="mostrarOcultarSenha()"></span><br>
        <a id="esqueci_senha" href="esqueci_senha.php">Esqueceu a senha?</a><br>
        <button type="submit" value="Entrar">Entrar</button>
    </form>

    <script type="text/javascript" src="js/funcoes.js"></script>
</body>

</html>
