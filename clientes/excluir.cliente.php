<?php
session_start();
include '../conexao.php';
if (!isset($_SESSION['admin'])) {
 header("Location: ../acessos/login.php");
 exit();
}
if (!isset($_GET['id'])) {
 echo "ID não informado.";
 exit();
}
$id = (int) $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM clientes WHERE id = ?");
if ($stmt->execute([$id])) {
 header("Location: listar_clientes.php");
 exit();
} else {
 echo "Erro ao excluir cliente.";
 }
?>