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
                // Armazena a autenticação do usuário
                sessionStorage.setItem('sessionAuthenticated', 'true');
                // Redireciona o usuário para a página inicial
                window.location.href = "home.html";
            }
        }
    };

    xhr.send(dadosFormulario);
}

// Função que abre a janela modal p/ atualização das informações de perfil
function openUpdateUserModal() {
    var modal = document.getElementById("updateUserModal");
    modal.style.display = "block";
}

// Fechar o modal ao clicar fora da área do modal
window.addEventListener("click", function (event) {

    const updateUserModal = document.getElementById("updateUserModal");

    if (event.target == updateUserModal) {

        updateUserModal.style.display = "none";

        /* Limpa os campos ao clicar fora da janela modal

        const nameField = document.getElementById("new_name");
        const emailField = document.getElementById("new_email");
        const passwordField = document.getElementById("new_password");

        nameField.value = "";
        emailField.value = "";
        passwordField.value = "";
        
        */

    }

});

// Função que mostra a tabela de eventos no perfil do usuário
function showEventTable() {
    // Cria uma instância do objeto XMLHttpRequest
    var xhr = new XMLHttpRequest();

    // Define a requisição GET com o URL do servidor
    xhr.open('GET', 'php/user.php?action=getEventTable', true);

    // Define o callback a ser executado quando a resposta da requisição for recebida
    xhr.onreadystatechange = function() {
        // Verifica se a requisição foi concluída e a resposta está pronta
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Parseia a resposta JSON recebida em formato de texto para um objeto JavaScript
            var resultados = JSON.parse(xhr.responseText);
            var eventTableBody = document.getElementById('eventTableBody');

            // Verifica se a resposta é igual a 'NA', indicando que nenhum evento foi encontrado
            if (resultados === 'NA') {
                // Cria uma linha vazia para exibir a mensagem 'Nenhum evento encontrado'
                var emptyRow = document.createElement('tr');
                var emptyCell = document.createElement('td');
                emptyCell.colSpan = 8; // Define o número de colunas que a célula vazia deve ocupar
                emptyCell.textContent = 'Nenhum evento encontrado.';
                emptyRow.appendChild(emptyCell);
                eventTableBody.appendChild(emptyRow);
            } else {
                // Itera sobre cada evento encontrado e cria as linhas correspondentes na tabela
                resultados.forEach(function(evento) {
                    var row = document.createElement('tr');
                    var titleCell = document.createElement('td');
                    var descriptionCell = document.createElement('td');
                    var dateCell = document.createElement('td');
                    var timeCell = document.createElement('td');
                    var locationCell = document.createElement('td');
                    var categoryCell = document.createElement('td');
                    var priceCell = document.createElement('td');
                    var imageCell = document.createElement('td');

                    // Define o conteúdo de cada célula da linha com base nas propriedades do evento
                    titleCell.textContent = evento.title;
                    descriptionCell.textContent = evento.description;
                    dateCell.textContent = evento.date;
                    timeCell.textContent = evento.time;
                    locationCell.textContent = evento.location;
                    categoryCell.textContent = evento.category;
                    priceCell.textContent = evento.price;
                    imageCell.textContent = evento.image;

                    // Adiciona as células à linha
                    row.appendChild(titleCell);
                    row.appendChild(descriptionCell);
                    row.appendChild(dateCell);
                    row.appendChild(timeCell);
                    row.appendChild(locationCell);
                    row.appendChild(categoryCell);
                    row.appendChild(priceCell);
                    row.appendChild(imageCell);

                    // Adiciona a linha à tabela
                    eventTableBody.appendChild(row);
                });
            }
        }
    };

    // Envia a requisição ao servidor
    xhr.send();
}
