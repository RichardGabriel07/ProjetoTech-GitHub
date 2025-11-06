<?php
session_start();
include '../php/conexao.php'; // Sua conexão PDO
include '../acessos/verificar_admin.php'; // Verifica se é admin

$mensagem = '';

// ----------------------------------------------------
// LÓGICA DE APROVAÇÃO/REJEIÇÃO DE AGENDAMENTOS (POST)
// ----------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {

    $id_agendamento = (int)$_POST['id_agendamento'];
    $action = $_POST['action'];

    try {
        if ($action === 'confirmar') {
            // CONFIRMAR AGENDAMENTO
            $sql = "UPDATE agendamento SET status = 'Confirmado' WHERE id_agendamento = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id_agendamento]);
            $mensagem = "Agendamento confirmado com sucesso!";
        } elseif ($action === 'rejeitar' || $action === 'cancelar') {
            // REJEITAR/CANCELAR AGENDAMENTO
            // Neste caso, a melhor prática é apenas mudar o status ou deletar o registro.
            // Vamos mudar o status para 'Cancelado'
            $sql = "UPDATE agendamento SET status = 'Cancelado' WHERE id_agendamento = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id_agendamento]);
            $mensagem = "Agendamento cancelado/rejeitado com sucesso!";
        }
    } catch (PDOException $e) {
        $mensagem = "Erro ao processar agendamento: " . $e->getMessage();
    }

    // Redireciona para evitar reenvio do formulário (F5)
    header("Location: gerenciar_agendamentos.php?msg=" . urlencode($mensagem));
    exit();
}

// ----------------------------------------------------
// LÓGICA DE MENSAGEM DE REDIRECIONAMENTO (GET)
// ----------------------------------------------------
if (isset($_GET['msg'])) {
    $mensagem = htmlspecialchars($_GET['msg']);
}

// ----------------------------------------------------
// QUERY PARA LISTAR TODOS OS AGENDAMENTOS (FINALMENTE CORRIGIDA)
// ----------------------------------------------------
$sql = "SELECT
            a.id_agendamento,
            a.data,
            a.horario,
            a.local,
            a.status,
            u.nome AS nome_usuario,
            -- Se o JOIN falhar, COALESCE retorna o nome digitado em 'agendamento.id_instrutor'
            COALESCE(i.nome, a.id_instrutor) AS nome_instrutor 
        FROM
            agendamento AS a
        LEFT JOIN
            usuario AS u ON a.id_usuario = u.id_usuario
        
        -- NOVO JOIN: LIGA O NOME NA TABELA AGENDAMENTO COM O NOME NA TABELA INSTRUTOR
        -- TRIM e LOWER são usados para evitar problemas de case e espaços
        LEFT JOIN
            instrutor AS i ON TRIM(LOWER(a.id_instrutor)) = TRIM(LOWER(i.nome))
            
        ORDER BY
            CASE WHEN a.status = 'Pendente' THEN 1 ELSE 2 END, 
            a.data ASC, a.horario ASC";

// O restante da lógica de execução (try...catch) permanece a mesma

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erro na query de agendamentos: " . $e->getMessage());
    $agendamentos = [];
    $mensagem = "Erro ao carregar agendamentos do banco de dados.";
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Gerenciar Agendamentos - Admin</title>
    <link rel="stylesheet" href="../css/listar_e_editar.css">
    <link rel="icon" href="../../ProjetoTech-GitHub/assets/imagens/Generated Image November 02, 2025 - 12_39AM.png" type="image/png">

</head>

<body>
    <header>
        <div id="navbar">
            <h1>ProjetoTech</h1>
            <nav>
                <ul>
                    <li><a href="../admin/area_administrativa.php">Área Administrativa</a></li>
                    <li><a href="../admin/gerenciar_matriculas.php">Gerenciar Matrículas</a></li>
                    <li><a href="../acessos/logout.php">Sair</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main id="box_formulario_cadastro">
        <h2>Gerenciamento de Agendamentos</h2>

        <?php if ($mensagem): ?>
            <p class="mensagem_alerta"><?= htmlspecialchars($mensagem) ?></p>
        <?php endif; ?>

        <?php if (empty($agendamentos)): ?>
            <p>Nenhum agendamento encontrado no sistema.</p>
        <?php else: ?>
            <table border="1" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuário</th>
                        <th>Instrutor</th>
                        <th>Data</th>
                        <th>Horário</th>
                        <th>Local</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($agendamentos as $a): ?>
                        <tr>
                            <td><?= htmlspecialchars($a['id_agendamento'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars(isset($a['nome_usuario']) ? $a['nome_usuario'] : 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars(isset($a['nome_instrutor']) ? $a['nome_instrutor'] : 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($a['data'], ENT_QUOTES, 'UTF-8') ?></td>
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

                            <td style="text-align: center;">
                                <?php if ($a['status'] === 'Pendente'): ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id_agendamento" value="<?= $a['id_agendamento'] ?>">
                                        <input type="hidden" name="action" value="confirmar">
                                        <button type="submit" style="background-color: #28a745; color: white; border: none; padding: 5px 8px; cursor: pointer;">Aprovar</button>
                                    </form>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Rejeitar este agendamento?');">
                                        <input type="hidden" name="id_agendamento" value="<?= $a['id_agendamento'] ?>">
                                        <input type="hidden" name="action" value="rejeitar">
                                        <button type="submit" style="background-color: #dc3545; color: white; border: none; padding: 5px 8px; cursor: pointer;">Rejeitar</button>
                                    </form>

                                <?php elseif ($a['status'] === 'Confirmado'): ?>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Cancelar este agendamento confirmado?');">
                                        <input type="hidden" name="id_agendamento" value="<?= $a['id_agendamento'] ?>">
                                        <input type="hidden" name="action" value="cancelar">
                                        <button type="submit" style="background-color: #ffc107; color: #333; border: none; padding: 5px 8px; cursor: pointer;">Cancelar</button>
                                    </form>

                                <?php else: // 'Cancelado' 
                                ?>
                                    <span>(Fechado)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>

</html>