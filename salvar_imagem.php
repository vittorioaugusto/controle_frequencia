<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php");
    exit();
}

// Diretório onde as imagens serão salvas (altere para o caminho real no seu servidor)
$diretorio_destino = 'assets/imagens_perfil/';

// Nome do arquivo
$nome_arquivo = $_SESSION['nome'] . '_perfil.jpg';

// Caminho completo do arquivo no servidor
$caminho_completo = $diretorio_destino . $nome_arquivo;

// Mensagem de alerta padrão
$alert_message = "Nenhum arquivo enviado.";

// Verifica se foi enviado um arquivo
if (isset($_FILES['imagem'])) {
    // Garante que o diretório de destino exista
    if (!file_exists($diretorio_destino)) {
        mkdir($diretorio_destino, 0755, true);
    }

    // Move o arquivo para o diretório de destino
    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_completo)) {
        $alert_message = "Upload da imagem realizado com sucesso.";
    } else {
        $alert_message = "Falha ao realizar o upload da imagem.";
    }
}

// Exibe o alerta usando JavaScript
echo "<script>alert('$alert_message'); window.location.href = 'perfil.php';</script>";
?>
