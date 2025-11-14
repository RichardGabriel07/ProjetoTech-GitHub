<?php
// Inicia a sessão para poder usar variáveis de sessão (como mensagens de erro) 
session_start();

// Verifica se existe uma mensagem salva na sessão 
$mensagem = isset($_SESSION['mensagem']) ? $_SESSION['mensagem'] : '';

// Após capturar, remove a mensagem da sessão (evita reaparecimento após atualizar a página) 
unset($_SESSION['mensagem']);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ProjetoTech</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&display=swap" rel="stylesheet">
    <script src="../js/script.js" defer></script>
    <link rel="icon" href="../../ProjetoTech-GitHub/assets/imagens/Generated Image November 02, 2025 - 12_39AM.png" type="image/png">

</head>

<body>
    <!-- Navbar Unificada Responsiva -->
    <?php include("navbar_publico.php") ?>

    <section id="criar_conta">
        <div id="gratis">
            <h2>Bem-Vindo(a) de <span>Volta!</span></h2>
            <p>Faça login para continuar sua jornada de aprendizado.</p>
        </div>

        <div id="right-side">
            <img src="../assets/imagens/ChatGPT_Image_8_de_out._de_2025__22_58_56-removebg-preview.png" alt="Imagem de cadastro">
        </div>
    </section>

    <main id="box_formulario_login"> <!-- Conteúdo principal -->
        <h2>Login do Cliente</h2>

        <!-- Exibe a mensagem de erro, se houver -->
        <?php if (!empty($mensagem)): ?>
            <p class="erro"> <?= $mensagem ?></p>
        <?php endif; ?>

        <!-- Formulário de login -->
        <form method="POST" action="processa_login.php" class="formulario"
            autocomplete="off">
            <!-- Campo CPF com estratégia para bloquear preenchimento automático -->
             <div class="input_group">
            <label for="cpf">CPF:</label>
            <input type="text" name="cpf" id="cpf" maxlength="14" required readonly
                onfocus="this.removeAttribute('readonly');" autocomplete="off">
            </div>
            <!-- Campo senha com mesma estratégia -->
             <div class="input_group">
            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required readonly
                onfocus="this.removeAttribute('readonly');" autocomplete="new-password">
            </div>

            <!-- Botão de envio -->
            <button type="submit">Entrar</button>
        </form>
    </main>
</body>

</html>