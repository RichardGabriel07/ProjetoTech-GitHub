<?php
// Ativa a exibição de todos os erros (útil para depuração durante o desenvolvimento)
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start(); // Inicia a sessão para acessar variáveis de sessão (como autenticação do cliente)
include '../conexao.php'; // Inclui o arquivo de conexão com o banco de dados
// Verifica se está logado
if (!isset($_SESSION['id_usuario'])) {
    echo "<script>
 alert ('Você precisa estar logado para acessar esta página.');
 window.location.href = '../acessos/login.php';
 </script>";
    exit;
}
$id = $_SESSION['id_usuario'];
// Processa o formulário se enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $telefone = trim($_POST["telefone"]);
    $endereco = trim($_POST["endereco"]);
    try {
        $sql = "UPDATE clientes SET nome = :nome, email = :email, telefone = :telefone, endereco =
:endereco WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            echo "<script>
 alert('Cadastro alterado com sucesso!');
 window.location.href = '../clientes/area_cliente.php'; </script>";
            echo "<script>
 alert('Erro ao alterar o cadastro.');
 window.location.href = '../clientes/area_cliente.php';
 </script>";
        }
        exit;
    } catch (PDOException $e) {
        echo "<script>
 alert('Erro: " . $e->getMessage() . "');
 window.location.href = '../clientes/area_cliente.php';
 </script>";
        exit;
    }
}
// Busca dados atuais
$sql = "SELECT nome, email, telefone, endereco FROM clientes WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Alterar Dados do Cliente</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="..js/script.js" defer></script>
</head>

<body>
    <div class="container">
        <h2>Alterar Meus Dados</h2>
        <form method="post" class="formulario">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($cliente['nome']) ?>" required>
            <p>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($cliente['email']) ?>" required>
            <p>
                <label for="telefone">Telefone:</label>
                <input type="text" name="telefone" id="telefone" value="<?= htmlspecialchars($cliente['telefone']) ?>"
                    required>
            <p>
                <label for="endereco">Endereço:</label>
                <input type="text" name="endereco" id="endereco" value="<?= htmlspecialchars($cliente['endereco']) ?>"
                    required>
            <p>
                <button type="submit">Salvar Alterações</button>
        </form>
        <div class="voltar">
            <a href="../clientes/area_cliente.php">Voltar para Área do Cliente</a>
        </div>
    </div>
    <footer> <!-- Rodapé com links para redes sociais -->
        <p>Siga-nos:
            <a href="#">Instagram</a> |
            <a href="#">Facebook</a> |
            <a href="#">X</a>
    </footer>
</body>

</html>