<?php
include '../php/conexao.php';
include '../acessos/verificar_admin.php'; // Garante que apenas admins acessem

// =======================================================
// 1. CONSULTA: MATRÍCULAS ONLINE (EAD)
// Tabela: matriculas_online, usuario, curso
// =======================================================
try {
    $sql_online = "
        SELECT
            u.nome AS nome_aluno,
            u.email AS email_aluno,
            c.nome_curso AS curso,
            mo.data_matricula,
            mo.status
        FROM matriculas_online mo
        JOIN usuario u ON mo.id_usuario = u.id_usuario
        JOIN curso c ON mo.id_curso = c.id_curso
        ORDER BY c.nome_curso, u.nome
    ";
    $stmt_online = $pdo->query($sql_online);
    $matriculas_online = $stmt_online->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $erro_online = "Erro ao buscar matrículas online: " . $e->getMessage();
    $matriculas_online = [];
}

// =======================================================
// 2. CONSULTA: MATRÍCULAS PRESENCIAIS (TURMAS)
// Tabela: turma_alunos, usuario, formar_turmas, curso, instrutor
// =======================================================
try {
    $sql_presencial = "
        SELECT
            u.nome AS nome_aluno,
            u.email AS email_aluno,
            c.nome_curso AS curso,
            ft.id_turma,
            i.nome AS nome_instrutor
        FROM turma_alunos ta
        JOIN usuario u ON ta.id_usuario = u.id_usuario
        JOIN formar_turmas ft ON ta.id_turma = ft.id_turma
        JOIN curso c ON ft.id_curso = c.id_curso
        JOIN instrutor i ON ft.id_instrutor = i.id_instrutor 
        ORDER BY ft.id_turma, u.nome
    ";
    $stmt_presencial = $pdo->query($sql_presencial);
    $matriculas_presenciais = $stmt_presencial->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $erro_presencial = "Erro ao buscar matrículas presenciais. Erro: " . $e->getMessage();
    $matriculas_presenciais = [];
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alunos Matriculados - Área Admin</title>
    <link rel="stylesheet" href="../css/listar_e_editar.css"> 
    <link rel="icon" href="../../ProjetoTech-GitHub/assets/imagens/Generated Image November 02, 2025 - 12_39AM.png" type="image/png">
    <style>
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            border-bottom: 2px solid #00b4d8;
            padding-bottom: 10px;
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            font-size: 0.9em;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
    </style>
</head>

<body>
    <?php include '../acessos/navbar_publico.php'; ?> 
    
    <header style="background: #122A3F; color: white; padding: 1.5rem 0; text-align: center;">
        <h1 style="margin: 0;">Listagem das Matrículas (Presencial e EAD)</h1>
    </header>

    <div class="container">

        <h2>Matrículas em Cursos Online (EAD)</h2>
        <?php if (isset($erro_online)): ?>
            <p style="color: red;"><?= $erro_online ?></p>
        <?php elseif (empty($matriculas_online)): ?>
            <p>Nenhum aluno matriculado em cursos online encontrado.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Aluno</th>
                    <th>E-mail</th>
                    <th>Curso Online</th>
                    <th>Data Matrícula</th>
                    <th>Status</th>
                </tr>
                <?php foreach ($matriculas_online as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars($m['nome_aluno']) ?></td>
                        <td><?= htmlspecialchars($m['email_aluno']) ?></td>
                        <td><?= htmlspecialchars($m['curso']) ?></td>
                        <td><?= date('d/m/Y', strtotime($m['data_matricula'])) ?></td>
                        <td><?= htmlspecialchars(ucfirst($m['status'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

        <h2>Matrículas em Turmas Presenciais</h2>
        <?php if (isset($erro_presencial)): ?>
            <p style="color: red;"><?= $erro_presencial ?></p>
        <?php elseif (empty($matriculas_presenciais)): ?>
            <p>Nenhum aluno matriculado em turmas presenciais encontrado.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Aluno</th>
                    <th>E-mail</th>
                    <th>Curso</th>
                    <th>ID Turma</th>
                    <th>Instrutor</th>
                </tr>
                <?php foreach ($matriculas_presenciais as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars($m['nome_aluno']) ?></td>
                        <td><?= htmlspecialchars($m['email_aluno']) ?></td>
                        <td><?= htmlspecialchars($m['curso']) ?></td>
                        <td><?= htmlspecialchars($m['id_turma']) ?></td>
                        <td><?= htmlspecialchars($m['nome_instrutor']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

    </div>
</body>

</html>