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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastre-se - ProjetoTech</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/cadastrar_cliente.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&display=swap" rel="stylesheet">
    <script src="../js/script.js" defer></script>
    <link rel="icon" href="../../ProjetoTech-GitHub/assets/imagens/Generated Image November 02, 2025 - 12_39AM.png" type="image/png">

</head>

<body>
    <!-- Navbar Unificada Responsiva -->
    <?php include("../acessos/navbar_publico.php") ?>

    <section id="criar_conta">
        <div id="gratis">
            <h2>Crie sua conta <span>Gratuita</span></h2>
            <p>Junte-se a milhares de alunos que estão aprimorando suas <br> habilidades em computação.</p>
        </div>

        <div id="right-side">
            <img src="../assets/imagens/chatgpt_image_projeto_tech_womam-removebg-preview.png" alt="Imagem de cadastro">
        </div>
    </section>


    <main id="box_formulario_cadastro">
        <h2>Crie sua conta</h2>

        <form method="POST" action="salvar_cliente.php" class="formulario">
            <!-- Campos do formulário -->
             <div class="input_group">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required placeholder="Digite seu nome completo" autocomplete="off">
    <br>    </div>
            <div class="input_group">
            <label for="cpf">CPF:</label>
            <input type="text" name="cpf" id="cpf" required maxlength="14" placeholder="000.000.000-00" autocomplete="off" oninput="formatarCPF(this)">
    <br>    </div>
            <div class="input_group">
            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" required autocomplete="off" placeholder="exemplo@gmail.com">
    <br>    </div>
            <div class="input_group">
            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required autocomplete="new-password" placeholder="Crie uma senha forte">
    <br>    </div>
            <p><button type="submit">Cadastrar</button></p>
        </form>
    </main>
</body>

</html>