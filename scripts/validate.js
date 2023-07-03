function validateRegister() {

    // validar nome
    var name = document.getElementById("name").value;
    var nameRegex = /^[a-záàâãéèêíïóôõöúçñ ]+$/i;
    if (!nameRegex.test(name)) {
        alert("Nome inválido!");
        return false;
    }

    // validar cpf (informado somente números)
    var cpf = document.getElementById("cpf").value;
    var cpfRegex = /^[0-9]{11}$/;
    if (!cpfRegex.test(cpf)) {
        alert("CPF inválido!");
        return false;
    }

    // validar telefone
    var phone = document.getElementById("phone").value;
    var phoneRegex = /^[0-9]{10,11}$/;
    if (!phoneRegex.test(phone)) {
        alert("Telefone inválido!");
        return false;
    }

    // validar foto
    var avatar = document.getElementById("avatar").value;
    if (avatar != "") {
        var avatarRegex = /^.*\.(jpg|jpeg|png|gif|bmp)$/i;
        if (!avatarRegex.test(avatar)) {
            alert("Formato de imagem inválido!");
            return false;
        }
    }

    // validar email usando regex
    var email = document.getElementById("email").value;
    var emailRegex = /^[a-z0-9.]+@[a-z0-9]+\.[a-z]+(\.[a-z]+)?$/i;
    if (!emailRegex.test(email)) {
        alert("Email inválido!");
        return false;
    }

    // validar senha
    var password = document.getElementById("password").value;
    var passwordcheck = document.getElementById("passwordcheck").value;
    if (password != passwordcheck) {
        alert("As senhas não conferem!");
        return false;
    }

    return true;
}