<?php include '../acessos/verificar_admin.php'; ?>

<main id="box_formulario_cadastro">
    <h2>Agende nossa visita!</h2>

    <form method="POST" action="listar_e_editar_agendamentos.php" class="formulario">
        <input type="hidden" name="id_agendamento" id="id_agendamento" value="">
        <input type="hidden" name="action" id="action" value="incluir">
        <input type="hidden" name="id_usuario" id="id_usuario" value="0">

        <div id="right_side_form">
            <div class="input_group">
                <label for="data">Data da Palestra:</label>
                <input type="date" name="data" id="data" required min="2024-01-01" max="2035-12-31">
            </div>

            <div class="input_group">
                <label for="hora">Hora da Palestra:</label>
                <input type="time" name="hora" id="hora" required>
            </div>

            <div class="input_group">
                <label for="endereco">Endereço:</label>
                <input type="text" name="endereco" id="endereco" required>
            </div>
        </div>

        <p><button type="submit" id="btn_submit">Agendar</button></p>
    </form>
</main>