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
    <title>Meus Agendamentos - ProjetoTech</title>
    <link rel="stylesheet" href="../css/area_cliente.css"> 
</head>
<body>
    <header>
        <div id="navbar">
            <h1>ProjetoTech</h1>
            <nav>
                <ul>
                    <li><a href="../clientes/area_cliente.php">Área cliente</a></li>
                    <li><a href="../clientes/ver_turmas.php">Minhas Turmas</a></li>
                    <li><a href="../acessos/logout.php">Sair</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main style="padding: 20px;">
        <h2>Meus Agendamentos</h2>
        
        <?php if ($mensagem): ?>
            <p class="mensagem_alerta" style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px;">
                <?= htmlspecialchars($mensagem) ?>
            </p>
        <?php endif; ?>

        <?php if (empty($agendamentos)): ?>
            <p>Você não possui agendamentos cadastrados.</p>
        <?php else: ?>
            <table border="1" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
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
                            <td><?= htmlspecialchars($a['id_agendamento']) ?></td>
                            <td><?= htmlspecialchars(isset($a['nome_instrutor']) ? $a['nome_instrutor'] : 'N/A') ?></td> 
                            <td><?= htmlspecialchars($a['data']) ?></td>
                            <td><?= htmlspecialchars($a['horario']) ?></td>
                            <td><?= htmlspecialchars($a['local']) ?></td>
                            <td>
                                <?php 
                                    $cor_status = 'gray';
                                    if ($a['status'] === 'Confirmado') $cor_status = 'green';
                                    if ($a['status'] === 'Cancelado') $cor_status = 'red';
                                ?>
                                <strong style="color: <?= $cor_status; ?>;">
                                    <?= htmlspecialchars($a['status']) ?>
                                </strong>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>