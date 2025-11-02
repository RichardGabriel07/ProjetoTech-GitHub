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
    <title>Cursos - ProjetoTech</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/cursos.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Navbar Unificada Responsiva -->
    <?php include("acessos/navbar_publico.php") ?>

    <section id="criar_conta">
        <div id="left-side">
            <h1>Explore os nossos <span>Cursos Gratuitos</span></h1>
            <p>Descubra uma variedade de cursos online projetados para aprimorar suas habilidades tecnológicas. Desde
                programação até design gráfico, temos algo para todos. Inscreva-se hoje e comece sua jornada de
                aprendizado
                conosco!</p>
        </div>

        <div id="right-side">
            <img src="assets/imagens/ChatGPT_Image_8_de_out._de_2025__22_58_56-removebg-preview.png"
                alt="Imagem de cadastro">
        </div>
    </section>

    <section id="section-cards">
        <h1>Nossos Cursos: </h1>
        <br>

        <div class="cards-container">

            <div class="cards">
                <a href="curso_online/cursos_online.php">
                    <img src="assets/imagens/code.png" alt="ícone de programação">
                    <h3>Programação</h3>
                    <p>Aprenda a escrever códigos usando linguagens de programação</p>
                </a>
            </div>

            <div class="cards">
                <a href="curso_online/cursos_online.php">
                <img src="assets/imagens/monitor.png" alt="ícone de informática básica">
                <h3>Informática Básica</h3>
                <p>Domine conceitos essenciais de computação e uso de softwares</p>
                </a>
            </div>

            <div class="cards">
                <a href="curso_online/cursos_online.php"> <img src="assets/imagens/database.png" alt="ícone de banco de dados">
                    <h3>Bancos de Dados</h3>
                    <p>Aprenda a organizar, consultar e gerenciar informações em bancos de dados</p>
                </a>
            </div>

            <div class="cards">
                <a href="curso_online/cursos_online.php">
                <img src="assets/imagens/photoshop.png" alt="ícone de Photoshop">
                <h3>Photoshop</h3>
                <p>Domine ferramentas de edição de imagens e design gráfico</p>
                </a>
            </div>

        </div>
    </section>
</body>

</html>