<?php
// Inicia a sessão no início do arquivo
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato - ProjetoTech</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/contato.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Navbar Unificada Responsiva -->
    <?php include("acessos/navbar_publico.php") ?>

    <section id="entre-em-contato">
        <div id="duvidas">
            <h2>Entre em Contato</h2>

            <p>Tire suas dúvidas, faça sugestões <br> ou relate bugs. Nossa equipe está pronta para ajudar!!</p>
        </div>
    </section>

    <main id="main-form">
        <div id="div-form">
            <form action="https://formsubmit.co/projetotech26@gmail.com" method="POST">
                <h3>Envie sua Mensagem</h3>
                <br>
                <div class="form-group">
                    <label for="nome">Seu Nome: </label>
                    <input type="text" name="nome" id="nome" required placeholder="Ex: Fulano da Silva">
                </div>
                <br>
                <div class="form-group">
                    <label for="email">E-mail: </label>
                    <input type="email" name="email" id="email" required placeholder="exemplo@gmail.com">
                </div>
                <br>
                <div class="form-group">
                    <label for="assunto">Assunto: </label>
                    <input type="text" name="assunto" id="assunto" required placeholder="O assunto do email aqui...">
                </div>
                <br>
                <div class="form-group">
                    <label for="mensagem">Mensagem: </label>
                    <textarea name="mensagem" id="mensagem" required placeholder="Sua Mensagem aqui..."></textarea>
                </div>
                <br>

                <button type="submit" class="submit">Enviar Mensagem</button>
            </form>
        </div>


        <div id="encontrar">
            <h2>Onde Nos Encontrar</h2>
            <div class="info-item">
                <span class="icon"><i class="fas fa-map-marker-alt"></i></span>
                <div>
                    <strong>Endereço Principal:</strong>
                    <p>R. Augusta Candiani, 64 - Inhoaíba <br>RJ - Rio de Janeiro, CEP 23070-020</p>
                </div>
            </div>
            <div class="info-item">
                <span class="icon"><i class="fas fa-envelope"></i></span>
                <div>
                    <strong>E-mail:</strong>
                    <p>projetotech26@gmail.com</p>
                </div>
            </div>
            <div class="info-item">
                <span class="icon"><i class="fas fa-phone-alt"></i></span>
                <div>
                    <strong>Telefone:</strong>
                    <p>(21) 99999-8888</p>
                </div>
            </div>

            <div class="map-container">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3675.3253407607963!2d-43.5802558!3d-22.9013679!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9be3f51705fc8b%3A0xd4e6050e748d37f9!2sR.%20Augusta%20Candiani%2C%2064%20-%20Inhoa%C3%ADba%2C%20Rio%20de%20Janeiro%20-%20RJ%2C%2023070-020!5e0!3m2!1spt-BR!2sbr!4v1759962480410!5m2!1spt-BR!2sbr"
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </main>
</body>

</html>