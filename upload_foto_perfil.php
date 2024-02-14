<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: index.php"); // Redirecione para a página de login se não estiver logado
    exit();
}

// Crie o diretório uploads se não existir
$diretorio_destino = 'uploads/';
if (!file_exists($diretorio_destino)) {
    mkdir($diretorio_destino, 0777, true);
}

// Verifique se um arquivo foi enviado
if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
    // Crie um nome único para o arquivo
    $nome_arquivo = uniqid('perfil_') . '_' . basename($_FILES['foto_perfil']['name']);
    
    // Caminho completo para onde a foto será armazenada
    $caminho_destino = $diretorio_destino . $nome_arquivo;
    
    // Mova o arquivo para o diretório de destino
    if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $caminho_destino)) {
        // Atualize o caminho da foto de perfil na sessão
        $_SESSION['foto_perfil'] = $caminho_destino;
        
        // Redirecione de volta à página de perfil
        header("Location: perfil.php");
        exit();
    } else {
        echo "Erro ao fazer upload do arquivo.";
    }
} else {
    echo "Nenhum arquivo enviado.";
}
?>
