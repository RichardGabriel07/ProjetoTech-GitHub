<?php
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do Cliente - ProjetoTech</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/area_cliente.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Navbar Unificada Responsiva -->
    <?php include("../acessos/navbar_publico.php") ?>

    <section id="criar_conta">
        <div id="gratis">
            <h2>Bem-vindo(a), <?php echo '<span>' . $nomeCapitalized . '</span>' ?>!</h2>
            <p>Bem vindo(a) a sua área, explore e sinta-se a vontade.</p>
        </div>

        <div id="right-side">
            <img src="../assets/imagens/ChatGPT_Image_8_de_out._de_2025__22_58_56-removebg-preview.png" alt="Imagem de cadastro">
        </div>
    </section>

    <main id="main_area_cliente">
        <div class="cursos_disponiveis">
            <div class="curso">
                <h3>Cursos Online</h3>
                <p>Acesse seus cursos online e continue aprendendo.</p>
                <a href="../curso_online/cursos_online.php" class="btn">Ver Cursos Online</a>
            </div>
            <div class="curso">
                <h3>Agendar Palestra</h3>
                <p>Agende uma palestra com um de nossos instrutores.</p>
                <a href="agendamento.php" class="btn">Agendar Agora</a>
            </div>
            <div class="curso">
                <h3>Minhas Turmas</h3>
                <p>Veja as turmas disponíveis para matrícula.</p>
                <a href="ver_turmas.php" class="btn">Ver Turmas</a>
            </div>
            <div class="curso">
                <h3>Meus Agendamentos</h3>
                <p>Veja os agendamentos que você fez.</p>
                <a href="ver_agendamentos.php" class="btn">Ver Agendamentos</a>
            </div>
        </div>
    </main>
</body>

</html>