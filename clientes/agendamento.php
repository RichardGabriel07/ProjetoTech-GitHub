<?php

session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../acessos/login.php");
    exit();
}

$nomeInstrutor = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Cliente'; // Se o nome não existir, usa 'Cliente'
$nomeInstrutor = ucfirst($nomeInstrutor); // Coloca a primeira letra em maiúscula
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento</title>
    <link rel="stylesheet" href="../css/agendamento.css">
</head>

<body>
    <header>
        <div id="navbar">
            <h1>Projeto <span>Tech</span></h1>

            <nav id="navbar-li">
                <ul>
                    <li><a href="../index.html">Inicio</a></li>
                    <li><a href="../cursos.html">Cursos </a></li>
                    <li><a href="agendamento.php">Agendamento</a></li>
                    <li><a href="formar_turmas.html">Formar Turmas</a></li>
                    <li><a href="../contato.html">Contato</a></li>
                    <li><a href="../clientes/cadastrar_cliente.php">Cadastre-se</a></li>
                    <li id="wilma"><a href="../acessos/logout.php" id="sair">Sair</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section id="criar_conta">
        <div id="gratis">
            <h2>Agende nossa <span>Palestra!</span></h2>
            <p>Agende nossa palestra, selecione o curso, e aprenda muito com a nossa equipe.</p>
        </div>

        <div id="right-side">
            <img src="../asstes/imagens/ChatGPT_Image_8_de_out._de_2025__22_58_56-removebg-preview.png" alt="Imagem de cadastro">
        </div>
    </section>

    <main id="box_formulario_cadastro">
        <h2>Agende nossa visita!</h2>

        <form method="POST" action="salvar_agendamento.php" class="formulario">
            <!-- Campos do formulário -->
            <div id="left_side_form">
                <div class="input_group">
                    <label for="instrutor">Selecione o Instrutor:</label>
                    <select name="instrutor" id="instrutor" required>
                        <option value="" disabled selected>Selecione um Instrutor</option>
                        <option value="rhuan"><?php echo "<span>" . $nomeInstrutor . "</span>"?></option>
                        <option value="richard">Richard</option>
                        <option value="kayke">Kayke</option>

                    </select>
                    <br>
                </div>
                <!-- <div class="input_group">
                    <label for="curso">Curso de Interesse:</label>
                    <select name="curso" id="curso" required>
                        <option value="" disabled selected>Selecione um curso</option>
                        <option value="programacao-web">Programação Web</option>
                        <option value="informatica-basica">Informatica Basica</option>
                        <option value="photoshop">Photoshop</option>

                    </select>
                    <br>
                </div>
            </div> -->

            <div id="right_side_form">
                <div class="input_group">
                    <label for="data">Data da Palestra:</label>
                    <input type="date" name="data" id="data" required>
                    <br>
                </div>
                <div class="input_group">
                    <label for="hora">Hora da Palestra:</label>
                    <input type="time" name="hora" id="hora" required>
                    <br>
                </div>

                <div class="input_group">
                    <label for="endereco">Endereço:</label>
                    <input type="text" name="endereco" id="endereco" required placeholder="Digite seu endereço completo" autocomplete="off">
                    <br>
                </div>
            </div>

            <p><button type="submit">Agendar</button></p>
        </form>
    </main>

    <script src="../js/script.js"></script>
</body>

</html>