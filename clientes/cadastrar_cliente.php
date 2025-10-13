<!DOCTYPE html>
<html lang="pt-br"> <!-- Define o idioma da página como português do Brasil -->

<head>
    <!-- Identifica a página no navegador e definições para o documento -->

        <!-- Define a codificação de caracteres como UTF-8, suportando acentuação e dispositivos móveis -->
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Crie sua conta </title> <!-- Título da aba do navegador -->
        <link rel="stylesheet" href="../css/cadastrar_cliente.css">
        <script src="../js/script.js" defer></script>
</head>

<body>
    <header>
        <div id="navbar">
            <h1>Projeto <span>Tech</span></h1>

            <nav id="navbar-li">
                <ul>
                    <li><a href="../index.html">Inicio</a></li>
                    <li><a href="cursos.html">Cursos </a></li>
                    <li><a href="agendamento.html">Agendamento</a></li>
                    <li><a href="formar_turmas.html">Formar Turmas</a></li>
                    <li><a href="../contato.html">Contato</a></li>
                    <li><a href="./cadastrar_cliente.php">Cadastre-se</a></li>
                    <li id="wilma"><a href="../acessos/login.php" id="entrar">Entrar</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section id="criar_conta">
        <div id="gratis">
            <h2>Crie sua conta <span>Gratuita</span></h2>
            <p>Junte-se a milhares de alunos que estão aprimorando suas <br> habilidades em computação.</p>
        </div>

        <div id="right-side">
            <img src="../asstes/imagens/chatgpt_image_projeto_tech_womam-removebg-preview.png" alt="Imagem de cadastro">
        </div>
    </section>


    <main id="box_formulario_cadastro">
        <h2>Crie sua conta</h2>

        <form method="POST" action="../clientes/salvar_cliente.php" class="formulario">
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