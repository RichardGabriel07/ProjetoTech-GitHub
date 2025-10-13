<?php

session_start();
include '../php/conexao.php'; // Inclua sua conexão

// 1. VERIFICAR SE O USUÁRIO ESTÁ LOGADO (ID do cliente é fundamental)
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../acessos/login.php");
    exit();
}
$id_usuario = $_SESSION['id_usuario']; 
$mensagem = "";
$sucesso = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_curso     = trim($_POST["curso"]);
    $data      = trim($_POST["data"]);
    $horario      = trim($_POST["hora"]);
    $local  = trim($_POST["endereco"]);
    $instrutor  = trim($_POST["instrutor"]);

     // 2. VALIDAR OS DADOS (básico, pode ser expandido)
     if (empty($id_usuario)  || empty($id_curso) || empty($data) || empty($horario) || empty($local) || empty($instrutor)) {
        $mensagem = "Por favor, preencha todos os campos obrigatórios.";
    }

    if ($id_usuario && $id_curso && $data && $horario && $local && $instrutor) {
         // 3. INSERIR NO BANCO DE DADOS
        try {
            $sql = "INSERT INTO agendamento (id_usuario, id_curso, data, horario, local, id_instrutor) 
                    VALUES (:id_usuario, :id_curso, :data, :horario, :local, :id_instrutor)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->bindParam(':id_curso', $id_curso);
            $stmt->bindParam(':data', $data);
            $stmt->bindParam(':horario', $horario);
            $stmt->bindParam(':local', $local);
            $stmt->bindParam(':id_instrutor', $instrutor);


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
            window.location.href = "./area_cliente.php"; // Redireciona para a página de contato em caso de sucesso
        <?php else: ?>
            window.history.back(); // Volta para o formulário em caso de erro 
        <?php endif; ?>
    </script>
</body>

</html>