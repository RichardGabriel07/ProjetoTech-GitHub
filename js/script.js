// Máscara CPF
const cpf = document.getElementById("cpf");
if (cpf) {
    cpf.addEventListener("input", function () {
        let valor = cpf.value.replace(/\D/g, "");
        valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
        valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
        valor = valor.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
        cpf.value = valor;
    });
}
// Máscara Telefone
const telefone = document.getElementById("telefone");
if (telefone) {
    telefone.addEventListener("input", function () {
        let valor = telefone.value.replace(/\D/g, "");
        valor = valor.replace(/^(\d{2})(\d)/g, "($1) $2");
        valor = valor.replace(/(\d{4,5})(\d{4})$/, "$1-$2");
        telefone.value = valor;
    });
}
// Máscara CNPJ
const cnpj = document.getElementById("cnpj");
if (cnpj) {
    cnpj.addEventListener("input", function () {
        let valor = cnpj.value.replace(/\D/g, "");
        valor = valor.replace(/^(\d{2})(\d)/, "$1.$2");
        valor = valor.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
        valor = valor.replace(/\.(\d{3})(\d)/, '.$1/$2');
        valor = valor.replace(/(\d{4})(\d)/, "$1-$2");
        cnpj.value = valor;
    });
}
