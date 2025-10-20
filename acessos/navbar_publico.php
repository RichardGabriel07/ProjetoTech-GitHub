<?php
// Garante que a sessão está iniciada para ler as variáveis de login
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// =================================================================
// VARIÁVEIS DE CHECAGEM DE SESSÃO
// Baseado no seu processa_login.php, a chave do CLIENTE é 'id_usuario'
// e a chave do ADMIN é 'admin'
// =================================================================
$is_client_logged_in = isset($_SESSION['id_usuario']) && !empty($_SESSION['id_usuario']);
$is_admin_logged_in = isset($_SESSION['admin']) && $_SESSION['admin'] === true;

?>

<nav id="navbar-li">
    <ul>
        <?php if (!$is_client_logged_in && !$is_admin_logged_in): ?>
            <li><a href="../acessos/login.php">Login</a></li>

            <li><a href="../acessos/formulario_usuario.php">Cadastre-se</a></li>
            
        <?php elseif ($is_client_logged_in): ?>
            <li><a href="../clientes/area_do_cliente.php">Área do Cliente</a></li>
            <li><a href="../acessos/logout.php">Sair</a></li>

        <?php elseif ($is_admin_logged_in): ?>
             <li><a href="../admin/area_administrativa.php">Dashboard</a></li>
            <li><a href="../acessos/logout.php" id="sair">Sair</a></li>
            
        <?php endif; ?>
    </ul>
</nav>
