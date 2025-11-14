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
    <meta name="description" content="Aprenda programação, informática básica, bancos de dados e Photoshop com cursos gratuitos no ProjetoTech. Acelere seu aprendizado com conteúdo prático e atualizado.">
    <title>ProjetoTech</title>
    <link rel="stylesheet" href="csssite.css">
    <link rel="icon" href="assets/imagens/Generated Image November 02, 2025 - 12_39AM.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <?php include 'acessos/navbar_publico.php'; ?>
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

    <section id="section-cards" class="hidden-scroll" aria-labelledby="cursos-title">
        <h2 id="cursos-title" class="sr-only">Nossos Cursos</h2>
        <div class="cards">
            <img src="assets/imagens/code.png" alt="Ícone representando programação web">
            <h3>Programação Web</h3>
            <p>Aprenda a criar sites e aplicativos web com HTML, CSS e JavaScript</p>
        </div>

        <div class="cards">
            <img src="assets/imagens/monitor.png" alt="Ícone representando informática básica">
            <h3>Informática Básica</h3>
            <p>Domine conceitos essenciais de computação e uso de softwares</p>
        </div>

        <div class="cards">
            <img src="assets/imagens/database.png" alt="Ícone representando bancos de dados">
            <h3>Bancos de Dados</h3>
            <p>Aprenda a organizar, consultar e gerenciar informações em bancos de dados</p>
        </div>

        <div class="cards">
            <img src="assets/imagens/photoshop.png" alt="Ícone representando Photoshop">
            <h3>Photoshop</h3>
            <p>Domine ferramentas de edição de imagens e design gráfico</p>
        </div>
    </section>

    <div id="sobre-nos" class="bloco-site hidden-scroll">
        <div class="bloco-wrapper">
            <h2 class="titulo-secao">Sobre Nós</h2>
            <p class="texto-secao">
                O <strong>ProjetoTech</strong> é uma iniciativa gratuita que acelera o aprendizado em
                Programação, Informática, Bancos de Dados e Design. Nosso foco é ensino prático,
                conteúdo atualizado e comunidade — para você evoluir mais rápido e com propósito.
            </p>
            <a class="btn-secao" href="curso_online/cursos_online.php">Ver cursos</a>
        </div>
    </div>

    <div id="nossos-professores" class="bloco-site hidden-scroll">
        <div class="bloco-wrapper">
            <h2 class="titulo-secao">Nossos Professores e Diretores</h2>

            <div class="teachers-grid">
                <article class="teacher-card" id="rhuan">
                    <img loading="lazy" src="assets/imagens/rhuan3.jpg" alt="Retrato do professor Rhuan Gabriel">
                    <h3>Rhuan Gabriel</h3>
                    <p>Dev Full-Stack e instrutor de Programação Web. Foca em projetos reais com HTML, CSS, JS e PHP.</p>
                </article>

                <article class="teacher-card">
                    <img loading="lazy" src="assets/imagens/kayke2.jpeg" alt="Retrato da professora Kayke Gonçalves">
                    <h3>Kayke Gonçalves</h3>
                    <p>Especialista em Banco de Dados. Ensina modelagem, SQL e boas práticas com exercícios guiados.</p>
                </article>

                <article class="teacher-card">
                    <img loading="lazy" src="assets/imagens/thawany.jpeg" alt="Retrato da Professora Thawany Ponciano">
                    <h3>Thawany Ponciano</h3>
                    <p>Designer e instrutora de Photoshop. Ensina edição de imagens e design gráfico.</p>
                </article>

                <article class="teacher-card">
                    <img loading="lazy" src="assets/imagens/richard.jpg" alt="Retrato do Professor Richard Gabriel">
                    <h3>Richard Gabriel</h3>
                    <p>Instrutor de Montagem e Manutenção no ProjetoTech,com experiência em treinamentos e apresentações na área de tecnologia.</p>
                </article>

                <article class="teacher-card">
                    <img loading="lazy" src="assets/imagens/Amanda.jpeg" alt="Retrato do Professora Amanda de Deus">
                    <h3>Amanda de Deus</h3>
                    <p>Diretora de Marketing com vasta experiência em estratégias de engajamento e comunicação para ONGs.</p>
                </article>
            </div>
        </div>
    </div>

    <div id="video-introducao" class="bloco-site hidden-scroll">
        <div class="bloco-wrapper">
            <h2 class="titulo-secao">Assista nossa introdução</h2>

            <div class="video-wrapper">
                <iframe
                    src="https://www.youtube.com/embed/DLxcWaiZJvU"
                    title="Vídeo de apresentação do ProjetoTech"
                    loading="lazy"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen>
                </iframe>
            </div>

            <p class="texto-secao texto-menor">
                Um overview rápido do que fazemos, como funcionam as trilhas e como começar agora.
            </p>
        </div>
    </div>


    <section id="apoiadores" class="hidden-scroll">
        <h2>Nossos Apoiadores</h2>
        <div class="logos-grid-wrapper">

            <div class="logo-item">
                <a href="#" target="_blank" rel="nofollow noopener">
                    <div class="logo-image-wrapper logo-image-wrapper-encanto-das-velas">
                        <img class="logo-image" src="assets/imagens/EncantoDasVelasSemBg.png" alt="Encanto das Velas">
                    </div>
                </a>
            </div>

            <div class="logo-item">
                <a href="#" target="_blank" rel="nofollow noopener">
                    <div class="logo-image-wrapper">
                        <img class="logo-image biblioteca" src="assets/imagens/bibliotecaCampoGrande.jpeg" alt="Biblioteca Campo Grande">
                    </div>
                </a>
            </div>

            <div class="logo-item">
                <a href="https://www.abacatepay.com/" target="_blank" rel="nofollow noopener">
                    <div class="logo-image-wrapper link">
                        <img class="logo-image" src="assets/imagens/abacatePay.png" alt="AbacatePay">
                    </div>
                </a>
            </div>

            <div class="logo-item">
                <a href="#" target="_blank" rel="nofollow noopener">
                    <div class="logo-image-wrapper logo-image-wrapper-futuras-cientistas">
                        <img class="logo-image" src="assets/imagens/futurasCientistas-removebg-preview-artguru.png" alt="Futuras Cientistas">
                    </div>
                </a>
            </div>

            <div class="logo-item">
                <a href="#" target="_blank" rel="nofollow noopener">
                    <div class="logo-image-wrapper link">
                        <img class="logo-image" src="assets/imagens/link-removebg-preview-artguru.png" alt="Link">
                    </div>
                </a>
            </div>

        </div>
    </section>

    <a href="#" id="scrollTopBtn" title="Voltar ao topo">&uarr;</a>

    <footer>
        <footer>
            <div class="footer-container">

                <div class="footer-col">
                    <h4>ProjetoTech</h4>
                    <p>Acelerando o aprendizado em tecnologia com conteúdo prático e atualizado.</p>
                    <p>&copy; 2023 ProjetoTech. Todos os direitos reservados.</p>
                </div>

                <div class="footer-col">
                    <h4>Navegação</h4>
                    <ul>
                        <li><a href="#">Início</a></li>
                        <li><a href="#sobre-nos">Sobre Nós</a></li>
                        <li><a href="curso_online/cursos_online.php">Cursos</a></li>
                        <li><a href="#nossos-professores">Professores</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Cursos</h4>
                    <ul>
                        <li><a href="#">Programação Web</a></li>
                        <li><a href="#">Informática Básica</a></li>
                        <li><a href="#">Bancos de Dados</a></li>
                        <li><a href="#">Photoshop</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Siga-nos</h4>
                    <div class="social-links">
                        <a href="https://www.instagram.com/_projetotech/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>

            </div>
        </footer>

        <script>
            // Lógica para o botão "Voltar ao Topo"
            const scrollTopBtn = document.getElementById("scrollTopBtn");

            window.onscroll = function() {
                if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                    scrollTopBtn.style.display = "block";
                } else {
                    scrollTopBtn.style.display = "none";
                }
            };

            scrollTopBtn.onclick = function(e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            };

            // Lógica para Animação de Scroll
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible-scroll');
                    }
                });
            }, {
                threshold: 0.1 // A animação começa quando 10% do item está visível
            });

            const hiddenElements = document.querySelectorAll('.hidden-scroll');
            hiddenElements.forEach((el) => observer.observe(el));
        </script>

</body>

</html>