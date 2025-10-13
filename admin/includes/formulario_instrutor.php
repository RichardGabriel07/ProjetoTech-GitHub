        <?php include '../acessos/verificar_admin.php';
?>    
    <main id="box_formulario_cadastro">
        <h2>Cadastre o Instrutor:</h2>

        <form method="POST" action="./listar_e_editar_instrutores.php" class="formulario">
    <input type="hidden" name="id_instrutor" id="id_instrutor"> 
    
    <input type="hidden" name="action" id="action" value="incluir">

            <!-- Campos do formulário -->
            <div class="input_group">
                <label for="nome_instrutor">Nome do Instrutor:</label>
                <input type="text" name="nome_instrutor" id="nome_instrutor" required placeholder="ex: João Silva">
                <br>
            </div>

            <div class="input_group">
                <label for="area_de_atuacao">Área de atuação:</label>
                <input type="text" name="area_de_atuacao" id="area_de_atuacao" required placeholder="Ex: Desenvolvimento Web, Data Science...">
                <br>
            </div>

            <div class="input_group">
                <label for="sexo">Sexo:</label>
                <select name="sexo" id="sexo" required>
                    <option value="" selected disabled>Selecione:</option>
                    <option value="M">Masculino</option>
                    <option value="F">Feminino</option>
                    <option value="Outro">Outro</option>
                    <br>
                </select>
            </div>

            <div class="input_group">
                <p><button type="submit" id="btn_submit">Cadastre o Instrutor</button></p>

            </div>
        </form>
    </main>