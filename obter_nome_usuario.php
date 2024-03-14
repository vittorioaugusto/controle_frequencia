<?php
include 'SQL/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuarioId = $_POST['id'];

    $consultaNome = "SELECT nome FROM usuario WHERE id = $usuarioId";
    $resultadoNome = mysqli_query($conexao, $consultaNome);

    if ($resultadoNome && $row = mysqli_fetch_assoc($resultadoNome)) {
        $nomeUsuario = $row['nome'];
        echo $nomeUsuario;
    } else {
        echo "Erro ao obter o nome do usuário.";
    }
}
?>
