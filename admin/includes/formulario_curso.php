        <?php include '../acessos/verificar_admin.php';
        ?>
        <main id="box_formulario_cadastro">
            <h2>Cadastre o Curso:</h2>

            <form method="POST" action="./listar_e_editar_cursos.php" class="formulario">
                <input type="hidden" name="id_curso" id="id_curso">
                <input type="hidden" name="action" id="action" value="incluir">
                <!-- Campos do formulário -->
                <div class="input_group">
                    <label for="nome_curso">Nome do Curso:</label>
                    <input type="text" name="nome_curso" id="nome_curso" required placeholder="ex: Photoshop CS6">
                    <br>
                </div>

                <div class="input_group">
                    <label for="tipo_curso">Tipo de Curso:</label>
                    <select name="tipo_curso" id="tipo_curso">
                        <option value="" selected disabled>Selecione:</option>
                        <option value="Presencial">Presencial</option>
                        <option value="Online">Online</option>
                    </select>
                    <br>
                </div>

                <div class="input_group">
                    <label for="duracao">Duração:</label>
                    <input type="text" name="duracao" id="duracao" required placeholder="ex: ??? horas">
                    <br>
                </div>

                <div class="input_group">
                    <label for="descricao">Descrição:</label>
                    <textarea name="descricao" id="descricao" placeholder="Curso de Photoshop: aprenda a criar, editar e manipular imagens como um profissional "></textarea>
                    <br>
                </div>

                <div class="input_group">
                    <p><button type="submit" id="btn_submit">Cadastre o Curso</button></p>

                </div>
            </form>
        </main>