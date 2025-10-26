    <?php include '../acessos/verificar_admin.php';
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Area do Admin - ProjetoTech</title>
        <link rel="stylesheet" href="../css/area_administrativa.css">
    </head>

    <body>
        <header>
            <div id="navbar">
                <h1>Projeto <span>Tech</span></h1>

                <nav id="navbar-li">
                    <ul>
                        <li id="wilma"><a href="../acessos/logout.php" id="sair">Sair</a></li>
                    </ul>
                </nav>
            </div>

            <div id="welcome-message">
                <h2>Bem-vindo, <span><?php echo $_SESSION['nome']; ?>!</span></h2>
                <p>Seja Bem-vindo a nossa Área Administrativa</p>

            </div>
        </header>

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
                <a href="gerenciar_cursos.php" class="button">Ver e Permitir Cursos</a>
            </section>

            <section class="section_gerenciar" id="turma">
                <h2><i class="fas fa-users card-icon"></i> Ver e Permitir Turmas</h2>
                <a href="gerenciar_turmas.php" class="button">Ver e Permitir Turmas</a>
            </section>
        </main>
    </body>

    </html>