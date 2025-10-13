<?php
session_start();
include '../conexao.php';
if (!isset($_SESSION['admin'])) {
    header("Location: ../acessos/login.php");
    exit();
}
if (!isset($_GET['id'])) {
    echo "ID do cliente não informado.";
    exit();
}
$id = (int) $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = ?");
$stmt->execute([$id]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$cliente) {
    echo "Cliente não encontrado.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <header>
        <h1>Editar Cliente</h1>
        <nav><a href="listar_clientes.php">Voltar à Lista</a>
        </nav>
    </header>
    <div class="container">
        <form action="atualizar_clientes.php" method="post">
            <input type="hidden" name="id" value="<?= $cliente['id'] ?>">
            <label>Nome:</label><input type="text" name="nome" value="<?= htmlspecialchars($cliente['nome'])
                                                                        ?>" required><br>
            <label>CPF:</label><input type="text" name="cpf" value="<?= htmlspecialchars($cliente['cpf']) ?>"
                required> <br>
            <label>Email:</label><input type="email" name="email" value="<?= htmlspecialchars($cliente['email'])
                                                                            ?>" required> <br>
            <label>Telefone:</label><input type="text" name="telefone" value="<?=
                                                                                htmlspecialchars($cliente['telefone']) ?>"> <br>
            <label>Endereço:</label><input type="text" name="endereco" value="<?= htmlspecialchars($cliente['endereco']) ?>"> <br>

            <button type="submit">Atualizar</button>
        </form>
    </div>
    <footer> <!-- Rodapé com links para redes sociais -->
        <p>Siga-nos:
            <a href="#">Instagram</a> |
            <a href="#">Facebook</a> |
            <a href="#">X</a>
    </footer>
</body>

</html>