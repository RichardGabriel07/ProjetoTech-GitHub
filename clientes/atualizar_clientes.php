<?php
session_start();
include '../conexao.php';
if (!isset($_SESSION['admin'])) {
 header("Location: ../acessos/login.php");
 exit(); }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 $id = $_POST['id'];
 $nome = $_POST['nome'];
 $cpf = $_POST['cpf'];
 $email = $_POST['email'];
 $telefone = $_POST['telefone'];
 $endereco = $_POST['endereco'];
$sql = "UPDATE clientes SET nome = ?, cpf = ?, email = ?, telefone = ?, endereco = ? WHERE id = ?";
 $stmt = $pdo->prepare($sql);
 if ($stmt->execute([$nome, $cpf, $email, $telefone, $endereco, $id])) {
 header("Location: listar_clientes.php");
 exit();
 } else {
 echo "Erro ao atualizar cliente.";
 }
} else {
 echo "Requisição inválida.";
 }
?>