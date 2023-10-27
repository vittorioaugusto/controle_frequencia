function mostrarOcultarSenha() {
    var senhaInput = document.getElementById("senha");
    if (senhaInput.type === "password") {
        senhaInput.type = "text";
    } else {
        senhaInput.type = "password";
    }
}

function confirmarSaida() {
    var confirmacao = confirm("Deseja realmente sair do sistema?");
    if (confirmacao) {
        window.location.href = "sair.php";
    }
}
