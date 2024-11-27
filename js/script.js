document.addEventListener("DOMContentLoaded", function() {
    // Captura o formulário
    const form = document.querySelector('form');

    // Função para mostrar erro
    function showError(input, message) {
        const errorElement = document.createElement('span');
        errorElement.textContent = message;
        errorElement.style.color = 'red';
        errorElement.style.fontSize = '12px';
        errorElement.style.marginTop = '5px';
        errorElement.style.display = 'block';

        // Remove erro antigo e adiciona novo
        const parent = input.parentElement;
        const existingError = parent.querySelector('span');
        if (existingError) {
            parent.removeChild(existingError);
        }
        parent.appendChild(errorElement);

        input.style.borderColor = 'red';
    }

    // Função para limpar erros
    function clearError(input) {
        const parent = input.parentElement;
        const errorElement = parent.querySelector('span');
        if (errorElement) {
            parent.removeChild(errorElement);
        }
        input.style.borderColor = '';
    }

    // Validação antes de enviar
    form.addEventListener('submit', function (event) {
        let valid = true;

        // Campos do formulário
        const nome = document.getElementById('nome');
        const telefone = document.getElementById('telefone');
        const email = document.getElementById('email');
        const servicos = document.getElementById('servicos');
        const data = document.getElementById('data');
        const horario = document.getElementById('horario');

        // Validar nome
        if (!nome.value.trim()) {
            valid = false;
            showError(nome, "O nome é obrigatório.");
        } else {
            clearError(nome);
        }

        // Validar telefone (mínimo de 10 dígitos)
        const telefoneRegex = /^\d{10,11}$/;
        if (!telefone.value.trim() || !telefoneRegex.test(telefone.value)) {
            valid = false;
            showError(telefone, "Digite um telefone válido (com DDD).");
        } else {
            clearError(telefone);
        }

        // Validar e-mail
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email.value.trim() || !emailRegex.test(email.value)) {
            valid = false;
            showError(email, "Digite um e-mail válido.");
        } else {
            clearError(email);
        }

        // Validar seleção de serviços
        if (servicos.selectedOptions.length === 0) {
            valid = false;
            showError(servicos, "Selecione ao menos um serviço.");
        } else {
            clearError(servicos);
        }

        // Validar data
        const today = new Date().toISOString().split('T')[0];
        if (!data.value || data.value < today) {
            valid = false;
            showError(data, "Escolha uma data válida no futuro.");
        } else {
            clearError(data);
        }

        // Validar horário
        if (!horario.value.trim()) {
            valid = false;
            showError(horario, "O horário é obrigatório.");
        } else {
            clearError(horario);
        }

        // Se o formulário não for válido, impede o envio
        if (!valid) {
            event.preventDefault();
        }
    });
});
