<?php
session_start();
include '../php/conexao.php'; // Sua conexão PDO

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../acessos/login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$mensagem = '';

// ----------------------------------------------------
// LÓGICA DE MATRÍCULA (POST)
// ----------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'matricular') {

    $id_turma_a_matricular = (int)$_POST['id_turma'];

    try {
        // 1. Obtém o TIPO do curso que o usuário está tentando se matricular
        $stmt_tipo_curso = $pdo->prepare("
            SELECT c.tipo_curso 
            FROM formar_turmas ft
            JOIN curso c ON ft.id_curso = c.id_curso
            WHERE ft.id_turma = ?
        ");
        $stmt_tipo_curso->execute([$id_turma_a_matricular]);
        $tipo_curso_novo = $stmt_tipo_curso->fetchColumn();

        // Se a turma não existir ou não tiver tipo definido, para aqui.
        if (!$tipo_curso_novo) {
            throw new Exception("Erro: Turma ou tipo de curso não encontrado.");
        }

        // 2. Verifica se o usuário JÁ está matriculado em uma turma do MESMO TIPO de curso
        $stmt_check_tipo = $pdo->prepare("
            SELECT ta.id 
            FROM turma_alunos ta
            JOIN formar_turmas ft ON ta.id_turma = ft.id_turma
            JOIN curso c ON ft.id_curso = c.id_curso
            WHERE ta.id_usuario = ? AND c.tipo_curso = ?
        ");
        $stmt_check_tipo->execute([$id_usuario, $tipo_curso_novo]);

        if ($stmt_check_tipo->rowCount() > 0) {
            // Regra de Negócio: Cliente não pode ter dois cursos do mesmo tipo (Ex: duas turmas de 'Programação')
            $mensagem = "Atenção: Você já está matriculado em uma turma do tipo '{$tipo_curso_novo}'. Não é permitido se matricular em outro curso do mesmo tipo.";
        } else {
            // 3. Verifica se o usuário JÁ está matriculado nesta turma específica
            $stmt_matricula_existente = $pdo->prepare("SELECT id FROM turma_alunos WHERE id_turma = ? AND id_usuario = ?");
            $stmt_matricula_existente->execute([$id_turma_a_matricular, $id_usuario]);

            if ($stmt_matricula_existente->rowCount() > 0) {
                 $mensagem = "Você já está matriculado nesta turma.";
            } else {
                // 4. Inicia a transação
                $pdo->beginTransaction();

                // Insere o registro na tabela de relacionamento (turma_alunos)
                $sql_insert = "INSERT INTO turma_alunos (id_turma, id_usuario) VALUES (?, ?)";
                $stmt_insert = $pdo->prepare($sql_insert);
                $stmt_insert->execute([$id_turma_a_matricular, $id_usuario]);

                // 5. Decrementa o número de vagas disponíveis na tabela formar_turmas
                $sql_update_vagas = "UPDATE formar_turmas SET vagas_disponiveis = vagas_disponiveis - 1 WHERE id_turma = ? AND vagas_disponiveis > 0";
                $stmt_update_vagas = $pdo->prepare($sql_update_vagas);
                $stmt_update_vagas->execute([$id_turma_a_matricular]);

                if ($stmt_update_vagas->rowCount() === 0) {
                    $pdo->rollBack();
                    throw new Exception("Erro: A turma esgotou as vagas durante o processo de matrícula.");
                }

                // 6. Confirma a transação
                $pdo->commit();
                $mensagem = "Matrícula na turma realizada com sucesso! (Tipo de Curso: {$tipo_curso_novo})";
            }
        }
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $mensagem = "Erro na matrícula: " . $e->getMessage();
    }
}

// LÓGICA DE LISTAGEM DE TURMAS (GET)
// Query para listar apenas turmas ativas e com vagas
$sql = "SELECT ft.*, c.nome_curso, c.tipo_curso, i.nome AS instrutor_nome 
        FROM formar_turmas ft 
        JOIN curso c ON ft.id_curso = c.id_curso 
        LEFT JOIN instrutor i ON ft.id_instrutor = i.id_instrutor 
        WHERE ft.vagas_disponiveis > 0 AND ft.status = 'Aberta'
        ORDER BY ft.data_inicio ASC";
$turmas = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Turmas Disponíveis - ProjetoTech</title>
    <link rel="stylesheet" href="../css/area_cliente.css">
</head>

<body>
    <header>
        <div id="navbar">
            <h1>Projeto <span>Tech</span></h1>
            <nav id="navbar-li">
                <ul>
                    <li><a href="../index.php">Início</a></li>
                    <li><a href="../cursos.php">Cursos</a></li>
                    <li><a href="agendamento.php">Agendamento</a></li>
                    <li><a href="ver_turmas.php">Turmas Disponíveis</a></li>
                    <li><a href="../contato.html">Contato</a></li>
                    <li><a href="area_cliente.php">Área do Cliente</a></li>
                    <li id="wilma"><a href="../acessos/logout.php" id="sair">Sair</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main id="main_turmas">
        <h2>Turmas Abertas para Matrícula</h2>
        
        <?php if (!empty($mensagem)): ?>
            <p style="padding: 10px; border-radius: 5px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; margin-bottom: 15px;"><?= htmlspecialchars($mensagem) ?></p>
        <?php endif; ?>

        <?php if (empty($turmas)): ?>
            <p>Nenhuma turma disponível no momento.</p>
        <?php else: ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>Nome da Turma</th>
                        <th>Curso</th>
                        <th>Tipo</th>
                        <th>Instrutor</th>
                        <th>Local</th>
                        <th>Início</th>
                        <th>Horário</th>
                        <th>Vagas</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Prepara a query para checar se o usuário já está matriculado
                    $stmt_check_matricula = $pdo->prepare("SELECT id FROM turma_alunos WHERE id_turma = ? AND id_usuario = ?");
                    ?>
                    <?php foreach ($turmas as $t): ?>
                        <?php 
                        // Verifica se o usuário já está matriculado nesta turma
                        $stmt_check_matricula->execute([$t['id_turma'], $id_usuario]);
                        $ja_matriculado = $stmt_check_matricula->rowCount() > 0;
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($t['nome_turma']) ?></td>
                            <td><?= htmlspecialchars($t['nome_curso']) ?></td>
                            <td><?= htmlspecialchars($t['tipo_curso']) ?></td>
                            <td><?= htmlspecialchars($t['instrutor_nome']) ?></td>
                            <td><?= htmlspecialchars($t['local']) ?></td>
                            <td><?= htmlspecialchars(date('d/m/Y', strtotime($t['data_inicio']))) ?></td>
                            <td><?= htmlspecialchars($t['horario']) ?></td>
                            <td style="text-align: center; font-weight: bold;"><?= htmlspecialchars($t['vagas_disponiveis']) ?></td>

                            <td style="text-align: center;">
                                <?php if ($ja_matriculado): ?>
                                    <button disabled style="background-color: #ccc; cursor: not-allowed; padding: 8px 12px; border-radius: 4px;">Já Matriculado</button>
                                <?php else: ?>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Confirmar matrícula em <?= htmlspecialchars($t['nome_turma']) ?>?');">
                                        <input type="hidden" name="id_turma" value="<?= htmlspecialchars($t['id_turma']) ?>">
                                        <input type="hidden" name="action" value="matricular">
                                        <button type="submit" style="background-color: #007bff; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer;">Matricular</button>
                                    </form>
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