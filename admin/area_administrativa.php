    <?php include '../acessos/verificar_admin.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área Administrativa - ProjetoTech</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/area_administrativa.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Navbar Unificada Responsiva -->
    <?php include("../acessos/navbar_publico.php") ?>

    <div id="welcome-message">
        <h2>Bem-vindo, <span><?php echo $_SESSION['nome']; ?>!</span></h2>
        <p>Seja Bem-vindo a nossa Área Administrativa</p>
    </div>

    <h2 class="left-pad">Criar e Gerenciar Registros:</h2>

        <main id="gerenciar">
            <section class="section_gerenciar" id="instrutor">
                <h2><i class="fas fa-chalkboard-teacher card-icon"></i> Gerenciar Instrutores</h2>
                <a href="listar_e_editar_instrutores.php" class="button">Listar e Editar Instrutores</a>
            </section>

            <section class="section_gerenciar" id="curso">
                <h2><i class="fas fa-book card-icon"></i> Gerenciar Cursos</h2>
                <a href="listar_e_editar_cursos.php" class="button">Listar e Editar Cursos</a>
            </section>

            <section class="section_gerenciar" id="aluno">
                <h2><i class="fas fa-users card-icon"></i> Gerenciar Alunos</h2>
                <a href="listar_e_editar_usuarios.php" class="button">Listar e Editar Alunos</a>
            </section>

            <section class="section_gerenciar" id="agendamento">
                <h2><i class="fas fa-calendar-alt card-icon"></i> Gerenciar Agendamentos</h2>
                <a href="listar_e_editar_agendamentos.php" class="button">Listar e Editar Agendamentos</a>
            </section>

            <section class="section_gerenciar" id="turma">
                <h2><i class="fas fa-calendar-alt card-icon"></i> Gerenciar Turmas</h2>
                <a href="../admin/criar_listar_e_editar_turmas.php" class="button">Listar e Editar Turmas</a>
            </section>
        </main>
        <h2 class="left-pad">Ver e Permitir Agendamentos, Cursos e Turmas:</h2>

        <main id="gerenciar">
            <section class="section_gerenciar" id="agendamento">
                <h2><i class="fas fa-calendar-alt card-icon"></i> Ver e Permitir Agendamentos</h2>
                <a href="gerenciar_agendamentos.php" class="button">Ver e Permitir Agendamentos</a>
            </section>

            <section class="section_gerenciar" id="curso">
                <h2><i class="fas fa-book card-icon"></i> Ver e Permitir Cursos</h2>
                <a href="gerenciar_matriculas.php" class="button">Ver e Permitir Cursos</a>
            </section>

            <section class="section_gerenciar" id="turma">
                <h2><i class="fas fa-users card-icon"></i> Ver e Permitir Turmas</h2>
                <a href="gerenciar_turmas.php" class="button">Ver e Permitir Turmas</a>
            </section>
        </main>
    </body>

    </html>