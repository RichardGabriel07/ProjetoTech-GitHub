<?php
session_start();
include '../php/conexao.php'; // Sua conexão PDO

// 1. Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) { 
    header("Location: ../acessos/login.php"); 
    exit(); 
}

$id_usuario = $_SESSION['id_usuario'];
$mensagem = '';

// LÓGICA DE MENSAGEM DE REDIRECIONAMENTO (GET)
if (isset($_GET['msg'])) {
    $mensagem = htmlspecialchars($_GET['msg']);
}

// ----------------------------------------------------
// QUERY PARA LISTAR APENAS OS AGENDAMENTOS DO USUÁRIO LOGADO
// ----------------------------------------------------
$sql = "SELECT
            a.id_agendamento,
            a.data,
            a.horario,
            a.local,
            a.status,
            u.nome AS nome_usuario,
            -- COALESCE garante que, se o JOIN falhar, o nome digitado em 'agendamento.id_instrutor' seja usado
            COALESCE(i.nome, a.id_instrutor) AS nome_instrutor 
        FROM
            agendamento AS a
        -- Faz JOIN com a tabela usuário (apenas para confirmar, mas já estamos usando o filtro abaixo)
        LEFT JOIN
            usuario AS u ON a.id_usuario = u.id_usuario
        
        -- O JOIN CORRIGIDO: Liga a coluna VARCHAR (nome) com o nome do instrutor, ignorando case/espaços
        LEFT JOIN
            instrutor AS i ON TRIM(LOWER(a.id_instrutor)) = TRIM(LOWER(i.nome))
            
        -- FILTRA APENAS PELO ID DO USUÁRIO LOGADO!
        WHERE
            a.id_usuario = ?
            
        ORDER BY
            a.data ASC, a.horario ASC";

try {
    $stmt = $pdo->prepare($sql);
    // Passa o ID do usuário logado como parâmetro
    $stmt->execute([$id_usuario]); 
    $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Erro na query de agendamentos do usuário: " . $e->getMessage());
    $agendamentos = [];
    $mensagem = "Erro ao carregar seus agendamentos do banco de dados.";
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Agendamentos - ProjetoTech</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/ver_dados.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&display=swap" rel="stylesheet">
    <link rel="icon" href="../../ProjetoTech-GitHub/assets/imagens/Generated Image November 02, 2025 - 12_39AM.png" type="image/png">
</head>
<body>
    <!-- Navbar Unificada Responsiva -->
    <?php include("../acessos/navbar_publico.php") ?>

    <main>
        <h2>Meus Agendamentos</h2>
        
        <?php if ($mensagem): ?>
            <p class="mensagem_alerta">
                <?= htmlspecialchars($mensagem) ?>
            </p>
        <?php endif; ?>

        <?php if (empty($agendamentos)): ?>
            <div class="mensagem_vazia">
                <p>Você não possui agendamentos cadastrados.</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Instrutor</th>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Local</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($agendamentos as $a): ?>
                            <tr>
                                <td data-label="ID"><?= htmlspecialchars($a['id_agendamento']) ?></td>
                                <td data-label="Instrutor"><?= htmlspecialchars(isset($a['nome_instrutor']) ? $a['nome_instrutor'] : 'N/A') ?></td>
                                <td data-label="Data"><?= date('d/m/Y', strtotime($a['data'])) ?></td>
                                <td data-label="Horário"><?= htmlspecialchars($a['horario']) ?></td>
                                <td data-label="Local"><?= htmlspecialchars($a['local']) ?></td>
                                <td data-label="Status">
                                    <?php 
                                        $status_class = 'status-pendente';
                                        if ($a['status'] === 'Confirmado') $status_class = 'status-confirmado';
                                        if ($a['status'] === 'Cancelado') $status_class = 'status-cancelado';
                                    ?>
                                    <span class="status-badge <?= $status_class ?>">
                                        <?= htmlspecialchars($a['status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>