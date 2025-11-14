<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faça a sua Doação - ProjetoTech</title>
    <meta name="description" content="Página de doação para apoiar o ProjetoTech e seus cursos gratuitos de programação e informática. Sua contribuição faz a diferença!">
    <link rel="icon" href="../../ProjetoTech-GitHub/assets/imagens/Generated Image November 02, 2025 - 12_39AM.png" type="image/png">
    <link rel="stylesheet" href="../css/doacao.css">
</head>

<body>
    <?php include '../acessos/navbar_publico.php'; ?>

    <section id="entre-em-contato">
        <div id="duvidas">
            <h2>Doe para ajudar o ProjetoTech</h2>

            <p>Sua doação ajuda o ProjetoTech a manter cursos gratuitos, infraestrutura e materiais didáticos. Participe como apoiador e ajude a transformar oportunidades de aprendizado para muitas pessoas.</p>
        </div>
    </section>

    <main id="box_doacao">
        <h2>Faça a sua Doação</h2>
        <p>Seu apoio é fundamental para continuarmos oferecendo cursos gratuitos de qualidade. Ao fazer uma doação, você ajuda a manter o ProjetoTech ativo e acessível para todos.</p>
        <form id="donationForm">
            <label for="donationAmount">Valor da Doação (R$):</label>
            <input type="number" id="donationAmount" name="donationAmount" min="1" required>

            <label for="donorName">Nome:</label>
            <input type="text" id="donorName" name="donorName">

            <label for="donorEmail">Email:</label>
            <input type="email" id="donorEmail" name="donorEmail">

            <label for="cellphone">Telefone:</label>
            <input type="number" id="cellphone" name="cellphone">

            <label for="tax_id">CPF ou CNPJ:</label>
            <input type="number" id="tax_id" name="tax_id">

            <button type="submit">Doar Agora</button>
        </form>

        <script src="../js/doacao.js" defer></script>
</body>

</html>