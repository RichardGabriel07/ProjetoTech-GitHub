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
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento - ProjetoTech</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/agendamento.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Navbar Unificada Responsiva -->
    <?php include("../acessos/navbar_publico.php") ?>

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