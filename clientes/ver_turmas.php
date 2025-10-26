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
// ... (início do bloco POST no ver_turmas.php)

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
            $mensagem = "Erro: Turma inválida ou curso não encontrado.";
            // ... redirecionar e sair
        }

        // 2. Verifica se o aluno já está matriculado NESTA turma específica (evita duplicidade)
        $check_matricula = $pdo->prepare("SELECT id FROM turma_alunos WHERE id_turma = ? AND id_usuario = ?");
        $check_matricula->execute([$id_turma_a_matricular, $id_usuario]);

        if ($check_matricula->rowCount() > 0) {
            $mensagem = "Você já está matriculado(a) nesta turma!";
        }

        // 3. SE O NOVO CURSO FOR FÍSICO, VERIFICA RESTRIÇÃO
        else if ($tipo_curso_novo === 'Fisico') {

            // Verifica se o usuário JÁ POSSUI alguma matrícula em outro curso FÍSICO
            $check_fisico = $pdo->prepare("
                SELECT ta.id 
                FROM turma_alunos ta
                JOIN formar_turmas ft ON ta.id_turma = ft.id_turma
                JOIN curso c ON ft.id_curso = c.id_curso
                WHERE ta.id_usuario = ? 
                  AND c.tipo_curso = 'Fisico'
                  -- Opcional: Apenas turmas ativas/confirmadas. Se deixar fora, restringe a todas as matrículas, mesmo pendentes.
                  -- AND ta.status IN ('Pendente', 'Confirmada') 
            ");
            $check_fisico->execute([$id_usuario]);

            if ($check_fisico->rowCount() > 0) {
                $mensagem = "Restrição: Você já possui uma matrícula em um curso físico. Não é permitido se matricular em mais de um simultaneamente.";
            } else {
                // TUDO OK, MATRICULA!
                $sql_matricula = "INSERT INTO turma_alunos (id_turma, id_usuario, status) VALUES (?, ?, 'Pendente')";
                $stmt_matricula = $pdo->prepare($sql_matricula);
                $stmt_matricula->execute([$id_turma_a_matricular, $id_usuario]);

                $mensagem = "Matrícula em curso Físico realizada com sucesso! Status: Pendente.";
            }
        }

        // 4. Se o curso for Online, matricula sem restrição
        else { // tipo_curso_novo === 'Online'
            $sql_matricula = "INSERT INTO turma_alunos (id_turma, id_usuario, status) VALUES (?, ?, 'Pendente')";
            $stmt_matricula = $pdo->prepare($sql_matricula);
            $stmt_matricula->execute([$id_turma_a_matricular, $id_usuario]);

            $mensagem = "Matrícula em curso Online realizada com sucesso! Status: Pendente.";
        }

        // Redireciona para evitar reenvio do formulário (F5)
        header("Location: ver_turmas.php?msg=" . urlencode($mensagem));
        exit();
    } catch (PDOException $e) {
        $mensagem = "Erro ao tentar matricular: " . $e->getMessage();
    }
}

// ----------------------------------------------------
// LÓGICA DE MENSAGEM DE REDIRECIONAMENTO (GET)
// ----------------------------------------------------
if (isset($_GET['msg'])) {
    $mensagem = htmlspecialchars($_GET['msg']);
}

// ----------------------------------------------------
// QUERY PARA LISTAR TURMAS ABERTAS
// ----------------------------------------------------
$sql = "SELECT ft.*, c.nome_curso, i.nome AS instrutor_nome 
        FROM formar_turmas ft 
        JOIN curso c ON ft.id_curso = c.id_curso 
        JOIN instrutor i ON ft.id_instrutor = i.id_instrutor 
        WHERE ft.vagas_disponiveis > 0 AND ft.status = 'Aberta'";
$turmas = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);


// ----------------------------------------------------
// QUERY PARA LISTAR MATRÍCULAS DO ALUNO (para desabilitar o botão Matricular)
// ----------------------------------------------------
$sql_matriculas = "SELECT id_turma FROM turma_alunos WHERE id_usuario = ?";
$stmt_matriculas = $pdo->prepare($sql_matriculas);
$stmt_matriculas->execute([$id_usuario]);
$matriculas_aluno = $stmt_matriculas->fetchAll(PDO::FETCH_COLUMN); // Retorna apenas um array de id_turma


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
            <h1>ProjetoTech</h1>
            <nav>
                <ul>
                    <li><a href="../clientes/area_cliente.php">Área do Cliente</a></li>
                    <li><a href="../clientes/ver_agendamentos.php">Ver Agendamentos</a></li>
                    <li><a href="../acessos/logout.php">Sair</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main style="padding: 20px;">
        <h2>Turmas Abertas para Matrícula</h2>

        <?php if ($mensagem): ?>
            <p class="mensagem_alerta" style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px;">
                <?= htmlspecialchars($mensagem) ?>
            </p>
        <?php endif; ?>

        <?php if (empty($turmas)): ?>
            <p>Nenhuma turma disponível para matrícula no momento.</p>
        <?php else: ?>
            <table border="1" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr>
                        <th>Turma</th>
                        <th>Curso</th>
                        <th>Instrutor</th>
                        <th>Local</th>
                        <th>Início</th>
                        <th>Horário</th>
                        <th>Vagas</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($turmas as $t):
                        $ja_matriculado = in_array($t['id_turma'], $matriculas_aluno);
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($t['nome_turma']) ?></td>
                            <td><?= htmlspecialchars($t['nome_curso']) ?></td>
                            <td><?= htmlspecialchars($t['instrutor_nome']) ?></td>
                            <td><?= htmlspecialchars($t['local']) ?></td>
                            <td><?= htmlspecialchars($t['data_inicio']) ?></td>
                            <td><?= htmlspecialchars($t['horario']) ?></td>
                            <td style="text-align: center; font-weight: bold;"><?= htmlspecialchars($t['vagas_disponiveis']) ?></td>

                            <td style="text-align: center;">
                                <?php if ($ja_matriculado): ?>
                                    <button disabled style="background-color: #ccc; cursor: not-allowed;">Já Matriculado</button>
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