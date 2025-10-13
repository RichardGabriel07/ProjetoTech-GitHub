<?php
// Inicia a sessão se ainda não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. Checa se o usuário está logado COMO ADMIN
// Verifica se a variável de sessão 'admin' existe e se é verdadeira
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    
    // 2. Se não for admin, destrói a sessão atual (por segurança)
    session_unset();
    session_destroy();
    
    // 3. Redireciona para a página de login com uma mensagem
    $_SESSION['mensagem_erro'] = "Acesso negado. Apenas administradores podem acessar esta área.";
    header("Location: ../acessos/login.php"); 
    exit();
}
// Se a execução do código chegar até aqui, o acesso é permitido.
?>

