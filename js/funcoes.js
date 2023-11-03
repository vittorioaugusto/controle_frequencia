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


// Função para verificar o campo de data e hora
function verificarCampoDataHora() {
    var dataHoraInput = document.querySelector('input[name="data_hora"]');
    var avisoDiv = document.getElementById('aviso');

    if (dataHoraInput.value) {
        avisoDiv.innerText = 'Campo preenchido';
    } else {
        avisoDiv.innerText = '';
    }
}
// Função para mostrar uma mensagem de confirmação
function confirmarRegistro(event) {
    var dataHoraInput = document.querySelector('input[name="data_hora"]');
    
    if (dataHoraInput.value) {
        var confirmacao = confirm('Realmente deseja realizar a frequência?');
        
        if (!confirmacao) {
            event.preventDefault(); // Impede o envio do formulário se o usuário clicar em "Cancelar"
        }
    }
}
// Adicione um ouvinte de evento para verificar o campo quando houver uma alteração
document.querySelector('input[name="data_hora"]').addEventListener('input', verificarCampoDataHora);
// Adicione um ouvinte de evento para mostrar a mensagem de confirmação
document.querySelector('form').addEventListener('submit', confirmarRegistro);


function alterarStatus(usuarioId, novoStatus) {
    var confirmMessage = novoStatus === 1 ? "Ativar" : "Desativar";
    if (confirm("Tem certeza de que deseja " + confirmMessage + " o usuário?")) {
        $.ajax({
            type: "POST",
            url: "alterar_status.php",
            data: { id: usuarioId, status: novoStatus },
            success: function (data) {
                // Atualize a página para refletir as mudanças
                location.reload();
            },
            error: function () {
                alert("Erro ao alterar o status do usuário.");
            }
        });
    }
}


function confirmarSaida() {
    var confirmacao = confirm("Deseja realmente sair do sistema?");
    if (confirmacao) {
        window.location.href = "sair.php";
    }
}
