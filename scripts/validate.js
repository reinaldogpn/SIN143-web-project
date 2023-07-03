window.onload = function () {
    // Obtém referências aos elementos do formulário
    var form = document.querySelector('form');
    var nameInput = document.getElementById('name');
    var cpfInput = document.getElementById('cpf');
    var phoneInput = document.getElementById('phone');
    var emailInput = document.getElementById('email');
    var passwordInput = document.getElementById('password');
    var passwordCheckInput = document.getElementById('passwordcheck');

    // Adiciona um ouvinte de evento de envio ao formulário
    form.addEventListener('submit', function (event) {
        // Verifica se o campo de nome está vazio
        if (nameInput.value.trim() === '') {
            alert('Por favor, insira o nome.');
            event.preventDefault();
            return;
        }

        // Verifica se o campo de CPF está vazio
        if (cpfInput.value.trim() === '') {
            alert('Por favor, insira o CPF.');
            event.preventDefault();
            return;
        }

        // Verifica se o campo de telefone está vazio
        if (phoneInput.value.trim() === '') {
            alert('Por favor, insira o telefone.');
            event.preventDefault();
            return;
        }

        // Verifica se o campo de e-mail está vazio ou em um formato inválido
        if (emailInput.value.trim() === '') {
            alert('Por favor, insira o e-mail.');
            event.preventDefault();
            return;
        } else if (!validateEmail(emailInput.value.trim())) {
            alert('Por favor, insira um e-mail válido.');
            event.preventDefault();
            return;
        }

        // Verifica se o campo de senha está vazio
        if (passwordInput.value.trim() === '') {
            alert('Por favor, insira a senha.');
            event.preventDefault();
            return;
        }

        // Verifica se o campo de confirmação de senha está vazio ou não coincide com a senha digitada
        if (passwordCheckInput.value.trim() === '') {
            alert('Por favor, confirme a senha.');
            event.preventDefault();
            return;
        } else if (passwordCheckInput.value.trim() !== passwordInput.value.trim()) {
            alert('As senhas não coincidem.');
            event.preventDefault();
            return;
        }

        // Se todas as validações passaram, o formulário será enviado
    });

    // Função de validação de e-mail simples
    function validateEmail(email) {
        var re = /\S+@\S+\.\S+/;
        return re.test(email);
    }
};