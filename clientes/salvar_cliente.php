<?php

include_once("../php/conexao.php");       // Conexão com o banco via PDO 

$mensagem = "";
$sucesso = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome     = trim($_POST["nome"]);
    $cpf        = trim($_POST["cpf"]);
    $email     = trim($_POST["email"]);
    $senha     = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    if ($nome && $cpf && $email && $senha) {
        try {
            $sql = "INSERT INTO usuario (nome, cpf, email, senha) 
                    VALUES (:nome, :cpf, :email, :senha)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha);

            if ($stmt->execute()) {
                $mensagem = "Cliente cadastrado com sucesso!";
                $sucesso = true;
            } else {
                $mensagem = "Erro ao cadastrar: verifique os dados.";
            }
        } catch (PDOException $e) {
            $mensagem = "Erro no cadastro: " . $e->getMessage();
        }
    } else {
        $mensagem = "Preencha todos os campos obrigatórios.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Resultado do Cadastro</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <!-- mensagem em caixa de diálogo + redirecionamento -->
    <script>
        alert("<?= $mensagem ?>");
        <?php if ($sucesso): ?>
            window.location.href = "../index.php";
        <?php else: ?>
            window.history.back(); // Volta para o formulário em caso de erro 
        <?php endif; ?>
    </script>
</body>

</html>