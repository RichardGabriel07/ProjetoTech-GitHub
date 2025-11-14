document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("donationForm");

    if (!form) {
        console.error("❌ Formulário com ID 'donationForm' não encontrado.");
        return;
    }

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        // Coleta os dados do formulário
        const nome = document.getElementById("donorName").value.trim();
        const email = document.getElementById("donorEmail").value.trim();
        const telefone = document.getElementById("cellphone").value.trim();
        const cpfCnpj = document.getElementById("tax_id").value.trim();
        const valor = document.getElementById("donationAmount").value.trim();

        // ✅ Regex para validação
        const regexEmail = /^\S+@\S+\.\S+$/;
        const regexTelefone = /^\d{10,11}$/; // Exemplo: 21987654321
        const regexCPF = /^\d{11}$/;
        const regexCNPJ = /^\d{14}$/;

        // ✅ Validações dos campos
        if (nome.length < 3) {
            alert("❌ Digite seu nome completo.");
            return;
        }

        if (!regexEmail.test(email)) {
            alert("❌ Digite um e-mail válido.");
            return;
        }

        if (!regexTelefone.test(telefone)) {
            alert("❌ Digite um telefone válido (somente números).");
            return;
        }

        if (!(regexCPF.test(cpfCnpj) || regexCNPJ.test(cpfCnpj))) {
            alert("❌ Digite um CPF ou CNPJ válido (somente números).");
            return;
        }

        if (isNaN(valor) || valor <= 0) {
            alert("❌ Digite um valor válido para doação.");
            return;
        }

        // ✅ Preparação dos dados para envio
        const formData = new FormData();
        formData.append("amount", valor);
        formData.append("name", nome);
        formData.append("email", email);
        formData.append("cellphone", telefone);
        formData.append("tax_id", cpfCnpj);

        // ✅ Envio da requisição via Fetch API
        fetch("../admin/create_billing.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())  // Parse para JSON direto
        .then(data => {
            console.log("JSON RECEBIDO:", data);

            // ✅ Checa se a resposta foi bem-sucedida
            if (data.success && data.paymentUrl) {
                const btn = document.querySelector("button[type='submit']");
                btn.disabled = true;
                btn.innerText = "Redirecionando...";
                setTimeout(() => window.location.href = data.paymentUrl, 800);
            } else {
                alert("❌ Erro ao gerar pagamento: " + (data.error || "Verifique os dados."));
            }
        })
        .catch(error => {
            console.error("Erro de rede:", error);
            alert("❌ Erro de conexão com o servidor.");
        });
    });
});
