<?php
// Garante que a sessão está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica estados de login
$is_client_logged_in = isset($_SESSION['id_usuario']) && !empty($_SESSION['id_usuario']);
$is_admin_logged_in = isset($_SESSION['admin']) && $_SESSION['admin'] === true;

// Obtém nome do usuário logado
$user_name = '';
if ($is_client_logged_in && isset($_SESSION['nome'])) {
    $user_name = htmlspecialchars($_SESSION['nome']);
} elseif ($is_admin_logged_in) {
    $user_name = 'Admin';
}

// Detecta URL base para resolver problemas de caminho
$base_path = '';
$current_dir = dirname($_SERVER['PHP_SELF']);

// Se estiver em subdiretórios, ajusta o caminho base
if (strpos($current_dir, '/admin') !== false || strpos($current_dir, '/clientes') !== false || strpos($current_dir, '/acessos') !== false) {
    $base_path = '../';
}

?>

<!-- Estilos e Scripts para Navbar Responsiva -->
<link rel="stylesheet" href="<?php echo $base_path; ?>css/navbar.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<header>
    <nav class="navbar">
        <div class="nav-container">
            <!-- Logo -->
            <div class="nav-logo">
                <h1>Projeto <span>Tech</span></h1>
            </div>

            <!-- Menu Desktop -->
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="<?php echo $base_path; ?>index.php" class="nav-link">Início</a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $base_path; ?>cursos.php" class="nav-link">Cursos</a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $base_path; ?>clientes/agendamento.php" class="nav-link">Agendamento</a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $base_path; ?>clientes/ver_turmas.php" class="nav-link">Turmas</a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $base_path; ?>contato.php" class="nav-link">Contato</a>
                </li>
                
                <!-- Links dinâmicos baseados no estado de login -->
                <?php if (!$is_client_logged_in && !$is_admin_logged_in): ?>
                    <li class="nav-item">
                        <a href="<?php echo $base_path; ?>clientes/cadastrar_cliente.php" class="nav-link nav-btn">Cadastre-se</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo $base_path; ?>acessos/login.php" class="nav-link nav-btn nav-btn-primary">Entrar</a>
                    </li>
                
                <?php elseif ($is_client_logged_in): ?>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link user-menu">
                            <i class="fas fa-user-circle"></i>
                            <span><?php echo $user_name; ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo $base_path; ?>clientes/area_cliente.php">
                                <i class="fas fa-user"></i> Área do Cliente
                            </a></li>
                            <li><a href="<?php echo $base_path; ?>clientes/ver_agendamentos.php">
                                <i class="fas fa-calendar"></i> Meus Agendamentos
                            </a></li>
                            <li><a href="<?php echo $base_path; ?>clientes/ver_turmas.php">
                                <i class="fas fa-chalkboard-teacher"></i> Minhas Turmas
                            </a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo $base_path; ?>acessos/logout.php">
                                <i class="fas fa-sign-out-alt"></i> Sair
                            </a></li>
                        </ul>
                    </li>
                
                <?php elseif ($is_admin_logged_in): ?>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link user-menu admin-menu">
                            <i class="fas fa-user-shield"></i>
                            <span><?php echo $user_name; ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo $base_path; ?>admin/area_administrativa.php">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a></li>
                            <li><a href="<?php echo $base_path; ?>admin/listar_e_editar_usuarios.php">
                                <i class="fas fa-users"></i> Gerenciar Usuários
                            </a></li>
                            <li><a href="<?php echo $base_path; ?>admin/listar_e_editar_cursos.php">
                                <i class="fas fa-graduation-cap"></i> Gerenciar Cursos
                            </a></li>
                            <li><a href="<?php echo $base_path; ?>admin/gerenciar_agendamentos.php">
                                <i class="fas fa-calendar-check"></i> Gerenciar Agendamentos
                            </a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo $base_path; ?>acessos/logout.php">
                                <i class="fas fa-sign-out-alt"></i> Sair
                            </a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- Menu Mobile Toggle -->
            <div class="nav-toggle">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </div>
    </nav>
</header>

<script>
// Menu Mobile Toggle
document.querySelector('.nav-toggle').addEventListener('click', function() {
    document.querySelector('.nav-menu').classList.toggle('active');
    this.classList.toggle('active');
});

// Dropdown Menu
document.querySelectorAll('.user-menu').forEach(menu => {
    menu.addEventListener('click', function(e) {
        e.preventDefault();
        const dropdown = this.nextElementSibling;
        dropdown.classList.toggle('active');
        
        // Fecha outros dropdowns
        document.querySelectorAll('.dropdown-menu').forEach(other => {
            if (other !== dropdown) {
                other.classList.remove('active');
            }
        });
    });
});

// Fecha dropdowns ao clicar fora
document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown')) {
        document.querySelectorAll('.dropdown-menu').forEach(dropdown => {
            dropdown.classList.remove('active');
        });
    }
});
</script>
