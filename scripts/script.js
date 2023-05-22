function isValidEmail(email) {
    // Expressão regular para validar o formato do e-mail
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function sendForm(form) {
    
    var dadosFormulario = new FormData(form);
    var action = form.querySelector('[name="action"]').value;

    if (action === 'register') {

        var email = document.getElementById('r_email').value;
        var password1 = document.getElementById('r_password1').value;
        var password2 = document.getElementById('r_password2').value;

        // Validar campos de registro
        if (!isValidEmail(email)) {
            alert("Por favor, insira um email válido.");
            return;
        }

        if (password1.length > 255) {
            alert("A senha informada é muito longa.");
            return;
        }

        if (password1 !== password2) {
            alert("As senhas não coincidem.");
            return;
        }

    } else if (action === 'login') {
        // Adicione validações para campos de login, se necessário
        var email = document.getElementById('l_email').value;

        if (!isValidEmail(email)) {
            alert("Por favor, insira um email válido.");
            return;
        }

    }

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/authentication.php', true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Lógica para manipular a resposta do servidor, se necessário
            var response = JSON.parse(xhr.responseText);
            if (response.error) {
                alert(response.message);
            } else {
                // Sucesso no login ou registro
                alert(response.message);
                window.location.href = "home.html";
            }
        }
    };

    xhr.send(dadosFormulario);
}