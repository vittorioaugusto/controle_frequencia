<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
</head>

<body>
    <h2>Cadastro do Usuário</h2>
    <form action="processar_cadastro.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" required><br>
        <label for="email">Email:</label>
        <input type="text" name="email" required><br>
        <label for="senha">Senha:</label>
        <input type="password" name="senha" required><br>
        <label for="telefone">Telefone:</label>
        <input type="text" name="telefone"><br>
        <label for="tipo_usuario">Tipo de Usuário:</label>
        <select name="tipo_usuario" required>
            <option value="Professor">Professor</option>
            <option value="Funcionário de suporte ao aluno">Funcionário de suporte ao aluno</option>
            <option value="Funcionário de manutenção">Funcionário de manutenção</option>
            <option value="Funcionário de segurança">Funcionário de segurança</option>
            <option value="Estagiário">Estagiário</option>
        </select><br>
        <!-- <label for="turno">Turno:</label>
        <select name="turno" required>
            <option value="Manhã">Manhã</option>
            <option value="Tarde">Tarde</option>
            <option value="Noite">Noite</option>
            <option value="Integral">Integral</option>
        </select><br> -->
        <button type="submit" value="Cadastrar">Cadastrar</button>
        <div class="cadastro">
            Já tem uma conta?
            <a href="login.php">
                Logar
            </a>
        </div>
    </form>
</body>

</html>