function mostrarOcultarSenha() {
    var senhaInput = document.getElementById("senha");
    if (senhaInput.type === "password") {
        senhaInput.type = "text";
    } else {
        senhaInput.type = "password";
    }
}

$(document).ready(function () {
    $('input[name="telefone"]').mask('(00) 00000-0000');
});

function validarFormularioCpf() {
    var cpf = document.getElementById("cpf").value;

    // Verifique se o botão "Filtrar Por CPF" foi clicado e o campo do CPF está vazio
    if (event.submitter.name === "filtrarPorCPF" && (!cpf || cpf === "")) {
        Swal.fire({
            title: "Atenção",
            text: "Por favor, preencha o campo CPF.",
            icon: "warning",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OK"
        });
        return false;
    }

    return true;
}

function validarData() {
    var data = document.getElementById("data").value;

    if (event.submitter.name === "filtrarPorData" && (!data || data === "")) {
        Swal.fire({
            title: "Atenção",
            text: "Por favor, selecione uma data antes de filtrar.",
            icon: "warning",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OK"
        });
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

function obterNomeUsuario(usuarioId, novoStatus, callback) {
    $.ajax({
        type: "POST",
        url: "obter_nome_usuario.php",
        data: { id: usuarioId },
        success: function (response) {
            callback(response, usuarioId, novoStatus);
        },
        error: function () {
            alert("Erro ao obter o nome do usuário.");
        }
    });
}

function exibirPrompt(usuarioNome, usuarioId, novoStatus) {
    var confirmMessage = novoStatus === 1 ? "Ativar" : "Desativar";

    Swal.fire({
        title: "Confirmação",
        text: "Tem certeza de que deseja " + confirmMessage + usuarioNome + "?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: "alterar_status.php",
                data: { id: usuarioId, status: novoStatus },
                success: function () {
                    location.reload();
                },
                error: function () {
                    alert("Erro ao alterar o status do usuário.");
                }
            });
        }
    });
}

function alterarStatus(usuarioId, novoStatus) {
    obterNomeUsuario(usuarioId, novoStatus, function (usuarioNome, usuarioId, novoStatus) {
        exibirPrompt(usuarioNome, usuarioId, novoStatus);
    });
}

function confirmarSaidaPrincipal() {
    Swal.fire({
        title: "Confirmação",
        text: "Deseja realmente sair do sistema?",
        icon: "question",
        iconColor: "#6E2CF3",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "login/login.php";
        }
    });
}

function confirmarSaida() {
    Swal.fire({
        title: "Confirmação",
        text: "Deseja realmente sair do sistema?",
        icon: "question",
        iconColor: "#6E2CF3",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "../login/login.php";
        }
    });
}