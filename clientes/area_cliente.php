<?php

use Dom\Text;

session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../acessos/login.php");
    exit();
}

$nome = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Cliente'; // Se o nome não existir, usa 'Cliente'
$nomeCapitalized = ucfirst($nome); // Coloca a primeira letra em maiúscula
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Área do Cliente</title>
    <link rel="stylesheet" href="../css/area_cliente.css">
</head>

<body>
    <header>
        <div id="navbar">
            <h1>Projeto <span>Tech</span></h1>

            <nav id="navbar-li">
                <ul>
                    <li><a href="../index.html">Inicio</a></li>
                    <li><a href="../cursos.php">Cursos </a></li>
                    <li><a href="../clientes/agendamento.php">Agendamento</a></li>
                    <li><a href="formar_turmas.html">Formar Turmas</a></li>
                    <li><a href="../contato.html">Contato</a></li>
                    <li id="wilma"><a href="../acessos/logout.php" id="sair">Sair</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section id="criar_conta">
        <div id="gratis">
            <h2>Bem-vindo(a), <?php echo '<span>' . $nomeCapitalized . '</span>' ?>!</h2>
            <p>Bem vindo(a) a sua área, explore e sinta-se a vontade.</p>
        </div>

        <div id="right-side">
            <img src="../asstes/imagens/ChatGPT_Image_8_de_out._de_2025__22_58_56-removebg-preview.png" alt="Imagem de cadastro">
        </div>
    </section>

    <main id="main_area_cliente">
        <div class="cursos_disponiveis">
            <div class="curso">
                <h3>Cursos Disponiveis</h3>
                <p>Veja os cursos que nós temos.</p>
                <a href="../cursos.html" class="btn">Ver Meus Cursos</a>
            </div>
            <div class="curso">
                <h3>Agendar palestra</h3>
                <p>Agende uma palestra com um de nossos instrutores.</p>
                <a href="../clientes/agendamento.php" class="btn">Agendar Agora</a>
            </div>
            <div class="curso">
                <h3>Formar Turmas</h3>
                <p>Participe de novas turmas e aprenda mais.</p>
                <a href="formar_turmas.php" class="btn">Ver Turmas</a>
            </div>
            <div class="curso">
                <h3>Ver Turmas</h3>
                <p>Veja as turmas disponíveis para matrícula.</p>
                <a href="ver_turmas.php" class="btn">Ver Turmas</a>
            </div>
            <div class="curso">
                <h3>Ver Agendamento(s)</h3>
                <p>Veja os agendamentos que você fez.</p>
                <a href="ver_agendamentos.php" class="btn">Ver Agendamentos</a>
            </div>
        </div>
    </main>
</body>

</html>