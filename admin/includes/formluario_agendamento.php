    <?php include '../acessos/verificar_admin.php';
?>    
    
    <main id="box_formulario_cadastro">
        <h2>Agende nossa visita!</h2>

        <form method="POST" action="/ProjetoTech/admin/listar_e_editar_agendamentos.php" class="formulario">
            <input type="hidden" name="id_agendamento" id="id_agendamento" value="">
            <input type="hidden" name="action" id="action" value="cadastrar">
            <!-- Campos do formulário -->
            <div id="left_side_form">
                <div class="input_group">
                    <label for="instrutor">Selecione o Instrutor:</label>
                    <select name="instrutor" id="instrutor" required>
                        <option value="" disabled selected>Selecione um Instrutor</option>
                        <option value="rhuan">Rhuan</option>
                        <option value="richard">Richard</option>
                        <option value="kayke">Kayke</option>

                    </select>
                    <br>
                </div>
                <div class="input_group">
                    <label for="curso">Curso de Interesse:</label>
                    <select name="curso" id="curso" required>
                        <option value="" disabled selected>Selecione um curso</option>
                        <option value="programacao-web">Programação Web</option>
                        <option value="informatica-basica">Informatica Basica</option>
                        <option value="photoshop">Photoshop</option>

                    </select>
                    <br>
                </div>
            </div>

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

            <p><button type="submit" id="btn_submit">Agendar</button></p>
        </form>
    </main>