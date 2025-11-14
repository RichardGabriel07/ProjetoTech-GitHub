<?php include '../acessos/verificar_admin.php';
?>    
    <script src="../../js/script.js"></script>
    <main id="box_formulario_cadastro">
        <h2>Crie sua conta</h2>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['SCRIPT_NAME']); ?>" class="formulario">
            <input type="hidden" name="id_usuario" id="id_usuario">
            <input type="hidden" name="action" id="action" value="incluir">
            <!-- Campos do formulário -->
            <div class="input_group">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" required placeholder="Digite seu nome completo" autocomplete="off">
                <br>
            </div>
            <div class="input_group">
                <label for="cpf">CPF:</label>
                <input type="text" name="cpf" id="cpf" required maxlength="14" placeholder="000.000.000-00" autocomplete="off" oninput="formatarCPF(this)">
                <br>
            </div>
            <div class="input_group">
                <label for="email">E-mail:</label>
                <input type="email" name="email" id="email" required autocomplete="off" placeholder="exemplo@gmail.com">
                <br>
            </div>
            <div class="input_group">
                <label for="senha">Senha:</label>
                <input type="password" name="senha" id="senha" required autocomplete="new-password" placeholder="Crie uma senha forte">
                <br>
            </div>
            <p><button type="submit" id="btn_submit">Cadastrar</button></p>
        </form>
    </main>