<?php
session_start();
include '../php/conexao.php'; // Sua conexão PDO
include '../acessos/verificar_admin.php'; // Verifica se é admin

$mensagem = '';

// ----------------------------------------------------
// LÓGICA DE APROVAÇÃO/REJEIÇÃO (POST)
// ----------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
    
    $id_matricula = (int)$_POST['id_matricula'];
    
    // --- AÇÃO: CONFIRMAR MATRÍCULA ---
    if ($_POST['action'] === 'confirmar') {
        try {
            // A vaga já foi contada (pelo trigger de INSERT) quando o aluno se inscreveu.
            // A única coisa a fazer é mudar o status do aluno.
            $sql_confirmar = "UPDATE turma_alunos SET status = 'Confirmada' WHERE id = ?";
            $stmt_confirmar = $pdo->prepare($sql_confirmar);
            $stmt_confirmar->execute([$id_matricula]);
            
            $mensagem = "Matrícula confirmada com sucesso!";

        } catch (PDOException $e) {
            $mensagem = "Erro ao confirmar matrícula: " . $e->getMessage();
        }
    }
    
    // --- AÇÃO: REJEITAR MATRÍCULA (Devolve a vaga) ---
    elseif ($_POST['action'] === 'rejeitar') {
        
        // 1. Precisamos do id_turma para devolver a vaga na tabela 'formar_turmas'
        $stmt_get_id = $pdo->prepare("SELECT id_turma FROM turma_alunos WHERE id = ?");
        $stmt_get_id->execute([$id_matricula]);
        $id_turma_afetada = $stmt_get_id->fetchColumn();

        if ($id_turma_afetada) {
            
            // INICIA A TRANSAÇÃO (Igual ao meus_cursos.php)
            $pdo->beginTransaction();
            
            try {
                // 2. Deleta a matrícula do aluno (rejeição)
                $sql_cancelar = "DELETE FROM turma_alunos WHERE id = ?";
                $stmt_cancelar = $pdo->prepare($sql_cancelar);
                $stmt_cancelar->execute([$id_matricula]);

                // 3. Devolve a vaga (Inscritos - 1, Vagas + 1)
                $sql_update_turma = "UPDATE formar_turmas
                                     SET inscritos = inscritos - 1,
                                         vagas_disponiveis = vagas_disponiveis + 1
                                     WHERE id_turma = ?";
                $stmt_update = $pdo->prepare($sql_update_turma);
                $stmt_update->execute([$id_turma_afetada]);

                // 4. Se a turma estava 'Lotada', agora ela deve voltar a 'Aberta'
                $sql_update_status = "UPDATE formar_turmas 
                                      SET status = 'Aberta' 
                                      WHERE id_turma = ? AND status = 'Lotada' AND vagas_disponiveis > 0";
                $stmt_status = $pdo->prepare($sql_update_status);
                $stmt_status->execute([$id_turma_afetada]);

                // Se tudo deu certo, confirma as mudanças
                $pdo->commit();
                $mensagem = "Matrícula rejeitada com sucesso. A vaga foi devolvida.";

            } catch (PDOException $e) {
                // Se qualquer etapa falhar, desfaz todas as mudanças
                $pdo->rollBack();
                $mensagem = "Erro ao tentar rejeitar a matrícula: " . $e->getMessage();
            }
            
        } else {
            $mensagem = "Erro: Matrícula não encontrada.";
        }
    }
    
    // Redireciona para evitar reenvio do formulário (F5)
    header("Location: gerenciar_matriculas.php?msg=" . urlencode($mensagem));
    exit();
}

// ----------------------------------------------------
// LÓGICA DE MENSAGEM DE REDIRECIONAMENTO (GET)
// ----------------------------------------------------
if (isset($_GET['msg'])) {
    $mensagem = htmlspecialchars($_GET['msg']);
}

// ----------------------------------------------------
// QUERY PARA LISTAR TODAS AS MATRÍCULAS
// ----------------------------------------------------
$sql = "SELECT
            ta.id AS id_matricula,
            ta.status AS status_matricula,
            ta.data_matricula,
            u.nome AS nome_usuario,
            c.nome_curso,
            ft.nome_turma,
            ft.status AS status_turma
        FROM
            turma_alunos AS ta
        JOIN
            usuario AS u ON ta.id_usuario = u.id_usuario
        JOIN
            formar_turmas AS ft ON ta.id_turma = ft.id_turma
        JOIN
            curso AS c ON ft.id_curso = c.id_curso
        ORDER BY
            -- Prioriza matrículas pendentes no topo da lista
            CASE WHEN ta.status = 'Pendente' THEN 1 ELSE 2 END, 
            ta.data_matricula ASC"; // Mostra as pendentes mais antigas primeiro

$stmt = $pdo->query($sql);
$matriculas = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Matrículas - Admin</title>
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
                    <li><a href="../admin/gerenciar_agendamentos.php">Gerenciar Agendamentos</a></li>
                    <li><a href="../acessos/logout.php">Sair</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main id="box_formulario_cadastro">
        <h2>Gerenciamento de Matrículas</h2>
        
        <?php if ($mensagem): ?>
            <p class="mensagem_alerta"><?= htmlspecialchars($mensagem) ?></p>
        <?php endif; ?>

        <?php if (empty($matriculas)): ?>
            <p>Nenhuma matrícula encontrada no sistema.</p>
        <?php else: ?>
            <table border="1" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr>
                        <th>ID Mat.</th>
                        <th>Aluno</th>
                        <th>Curso (Turma)</th>
                        <th>Status Turma</th>
                        <th>Data Pedido</th>
                        <th>Status Matrícula</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matriculas as $m): ?>
                        <tr>
                            <td><?= htmlspecialchars($m['id_matricula']) ?></td>
                            <td><?= htmlspecialchars($m['nome_usuario']) ?></td>
                            <td><?= htmlspecialchars($m['nome_curso']) ?> (<?= htmlspecialchars($m['nome_turma']) ?>)</td>
                            <td><?= htmlspecialchars($m['status_turma']) ?></td>
                            <td><?= htmlspecialchars($m['data_matricula']) ?></td>
                            <td>
                                <?php 
                                    $cor_status = 'gray';
                                    if ($m['status_matricula'] === 'Confirmada') $cor_status = 'green';
                                    if ($m['status_matricula'] === 'Cancelada') $cor_status = 'red';
                                ?>
                                <strong style="color: <?= $cor_status; ?>;">
                                    <?= htmlspecialchars($m['status_matricula']) ?>
                                </strong>
                            </td>
                            
                            <td style="text-align: center;">
                                <?php if ($m['status_matricula'] === 'Pendente'): ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id_matricula" value="<?= $m['id_matricula'] ?>">
                                        <input type="hidden" name="action" value="confirmar">
                                        <button type="submit" style="background-color: #28a745; color: white; border: none; padding: 5px 8px; cursor: pointer;">Aprovar</button>
                                    </form>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Rejeitar esta matrícula? A vaga será devolvida.');">
                                        <input type="hidden" name="id_matricula" value="<?= $m['id_matricula'] ?>">
                                        <input type="hidden" name="action" value="rejeitar">
                                        <button type="submit" style="background-color: #dc3545; color: white; border: none; padding: 5px 8px; cursor: pointer;">Rejeitar</button>
                                    </form>
                                
                                <?php elseif ($m['status_matricula'] === 'Confirmada'): ?>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('CANCELAR esta matrícula (aluno já confirmado)? A vaga será devolvida.');">
                                        <input type="hidden" name="id_matricula" value="<?= $m['id_matricula'] ?>">
                                        <input type="hidden" name="action" value="rejeitar">
                                        <button type="submit" style="background-color: #dc3545; color: white; border: none; padding: 5px 8px; cursor: pointer;">Cancelar Vaga</button>
                                    </form>
                                
                                <?php else: // 'Cancelada' ?>
                                    <span>(N/A)</span>
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