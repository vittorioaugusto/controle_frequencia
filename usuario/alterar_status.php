<?php
include '../SQL/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuarioId = $_POST['id'];
    $novoStatus = $_POST['status'];

    // Atualize o campo 'ativo' na tabela de usuários com o novo status
    $query = "UPDATE usuario SET status = $novoStatus WHERE id = $usuarioId";
    $result = mysqli_query($conexao, $query);

    if ($result) {
        // A atualização foi bem-sucedida
        echo "Status do usuário atualizado com sucesso.";
    } else {
        // Erro na atualização
        echo "Erro ao atualizar o status do usuário.";
    }
}

?>
