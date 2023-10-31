function mostrarOcultarSenha() {
    var senhaInput = document.getElementById("senha");
    if (senhaInput.type === "password") {
        senhaInput.type = "text";
    } else {
        senhaInput.type = "password";
    }
}

function validarFormulario() {
    var usuario = document.getElementById("usuario").value;
    var cpf = document.getElementById("cpf").value;

    // Verifique se o usuário selecionou um usuário, mas não preencheu o CPF
    if (usuario && usuario !== "" && (!cpf || cpf === "")) {
        alert("Por favor, preencha o campo CPF ao selecionar um usuário.");
        return false;
    }

    return true;
}

function confirmarSaida() {
    var confirmacao = confirm("Deseja realmente sair do sistema?");
    if (confirmacao) {
        window.location.href = "sair.php";
    }
}
