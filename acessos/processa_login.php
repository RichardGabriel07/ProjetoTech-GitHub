<?php
session_start();
include '../php/conexao.php'; // Conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cpf = trim($_POST['cpf']);
    $senha = $_POST['senha'];
    $nome = trim($_POST['nome']);

    if (empty($cpf) || empty($senha)) {
        $_SESSION['mensagem'] = "Preencha todos os campos.";
        header("Location: ../acessos/login.php");
        exit();
    }

    // Login do Administrador
    if ($cpf === '100.000.000-01' && $senha === 'admin') {
        $_SESSION['admin'] = true;
        $_SESSION['nome'] = "Administrador";
        $_SESSION['id_usuario'] = 0; // ID fixo para o administrador
        header("Location: ../admin/area_administrativa.php");
        exit();
    }

    // Verifica login do cliente
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE cpf = ?");
    $stmt->execute([$cpf]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cliente && password_verify($senha, $cliente['senha'])) {
        $_SESSION['cpf'] = $cliente['cpf'];
        // CORREÇÃO: Usar 'id_usuario' que é o nome da coluna no banco de dados.
        $_SESSION['id_usuario'] = $cliente['id_usuario'];
        $_SESSION['nome'] = $cliente['nome'];

        // Assumindo que a página de destino está correta
        header("Location: ../clientes/area_cliente.php");
        exit();
    } else {
        $_SESSION['mensagem'] = "CPF ou senha inválidos.";
        header("Location: ../acessos/login.php");
        exit();
    }
}
?>