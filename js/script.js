document.addEventListener("DOMContentLoaded", function() {
    // Captura o formulário
    const form = document.querySelector('form');

    // Validação antes de enviar
    form.addEventListener('submit', function(event) {
        let valid = true;

        // Verifica se todos os campos estão preenchidos
        const nome = document.getElementById('nome').value;
        const telefone = document.getElementById('telefone').value;
        const email = document.getElementById('email').value;
        const servicos = document.getElementById('servicos').selectedOptions;
        const data = document.getElementById('data').value;
        const horario = document.getElementById('horario').value;

        // Se algum campo obrigatório estiver vazio
        if (!nome || !telefone || !email || servicos.length === 0 || !data || !horario) {
            alert("Por favor, preencha todos os campos obrigatórios.");
            valid = false;
        }

        // Se o formulário não for válido, impede o envio
        if (!valid) {
            event.preventDefault();
        }
    });
});
