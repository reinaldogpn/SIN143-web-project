function enviarFormulario(form) {
    var dadosFormulario = new FormData(form);
    var action = form.querySelector('[name="action"]').value;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../php/authentication.php', true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Lógica para manipular a resposta do servidor, se necessário
            var response = JSON.parse(xhr.responseText);
            if (response.error) {
                alert(response.message);
            } else {
                // Sucesso no login ou registro
                alert(response.message);
                window.location.href = "../html";
            }
        }
    };
    
    xhr.send(dadosFormulario);
}