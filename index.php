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
    <title>ProjetoTech</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="icon" href="../ProjetoTech-GitHub/assets/imagens/Generated Image November 02, 2025 - 12_39AM.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Navbar Unificada Responsiva -->
    <?php include("acessos/navbar_publico.php") ?>

    <main>
        <div id="left-side-main">
            <h1>Aprenda <span class="blue-label">Programação <br></span> e habilidades de <br> <span class="blue-label">
                    Computação</span></h1>
            <p>Oferecemos cursos gratuitos de Programação,<br> Informática Básica, Bancos de Dados e <br> Photoshop.</p>
            <a href="curso_online/cursos_online.php">Comece Agora</a>

        </div>

        <div id="right-side-main">
            <img src="assets/imagens/chatgpt_image_projeto_tech_womam-removebg-preview.png"
                alt="girl in the computer using softwares">
        </div>
    </main>

    <section id="section-cards">
        <div class="cards">
            <img src="assets/imagens/code.png" alt="ícone de programação">
            <h3>Programação</h3>
            <p>Aprenda a escrever códigos usando <br> linguagens de programação</p>
        </div>

        <div class="cards">
            <img src="assets/imagens/monitor.png" alt="ícone de informática básica">
            <h3>Informática Básica</h3>
            <p>Domine conceitos essenciais de <br> computação e uso de softwares</p>
        </div>

        <div class="cards">
            <img src="assets/imagens/database.png" alt="ícone de banco de dados">
            <h3>Bancos de Dados</h3>
            <p>Aprenda a organizar, consultar e  gerenciar informações em bancos de dados</p>
        </div>

        <div class="cards">
            <img src="assets/imagens/photoshop.png" alt="ícone de Photoshop">
            <h3>Photoshop</h3>
            <p>Domine ferramentas de edição <br> de imagens e design gráfico</p>
        </div>
    </section>

    <section id="apoiadores">
        <h2>Nossos Apoiadores</h2>
        <div class="logos-grid-wrapper">

            <div class="logo-item">
                <a href="#" target="_blank" rel="nofollow noopener">
                    <div class="logo-image-wrapper">
                        <img class="logo-image" src="../ProjetoTech-GitHub/assets/imagens/EncantoDasVelasSemBg.png" alt="Encanto das Velas">
                    </div>
                    <p class="logo-title">Encanto das Velas</p>
                </a>
            </div>

            <div class="logo-item">
                <a href="#" target="_blank" rel="nofollow noopener">
                    <div class="logo-image-wrapper">
                        <img class="logo-image" src="../ProjetoTech-GitHub/assets/imagens/bibliotecaCampoGrande.jpeg" alt="Biblioteca Campo Grande">
                    </div>
                    <p class="logo-title">Biblioteca Manuel Ignácio da Silva Alvarenga</p>
                </a>
            </div>

            <div class="logo-item">
                <a href="#" target="_blank" rel="nofollow noopener">
                    <div class="logo-image-wrapper">
                        <img class="logo-image" src="../ProjetoTech-GitHub/assets/imagens/futurasCientistas-removebg-preview-artguru.png" alt="Futuras Cientistas">
                    </div>
                    <p class="logo-title">Futuras Cientistas</p>
                </a>
            </div>

            <div class="logo-item">
                <a href="#" target="_blank" rel="nofollow noopener">
                    <div class="logo-image-wrapper">
                        <img class="logo-image" src="../ProjetoTech-GitHub/assets/imagens/link-removebg-preview-artguru.png" alt="Link">
                    </div>
                    <p class="logo-title">Link</p>
                </a>
            </div>

        </div>
    </section>

</body>

</html>