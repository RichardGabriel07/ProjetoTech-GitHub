<?php
session_start();
include '../php/conexao.php'; // Sua conexão PDO

// Verifica se o usuário está logado
// if (!isset($_SESSION['id_usuario'])) {
//     header("Location: ../acessos/login.php");
//     exit();
// }

$usuario_logado = isset($_SESSION['id_usuario']);

$id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;
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

                $mensagem = "Matrícula em curso Físico realizada com sucesso! </br> Status: Pendente.";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turmas Disponíveis - ProjetoTech</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/ver_dados.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href="../../ProjetoTech-GitHub/assets/imagens/Generated Image November 02, 2025 - 12_39AM.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&display=swap" rel="stylesheet">
    <style>
        button a {
            text-decoration: none;
            color: white;
        }
        button a:hover {
            color: #0096c7;
        }

        /* Hero Section - Contato */
#entre-em-contato {
    background: linear-gradient(135deg, #122A3F 0%, #1e3a5f 100%);
    color: white;
    text-align: center;
    padding: 4rem 2rem;
    margin-bottom: 3rem;
    position: relative;
    overflow: hidden;
}

#entre-em-contato::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.03)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.03)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.1;
}

#entre-em-contato h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    position: relative;
    z-index: 1;
}

#entre-em-contato p {
    font-size: 1.2rem;
    opacity: 0.9;
    position: relative;
    z-index: 1;
    max-width: 600px;
    margin: 0 auto;
}

    </style>
</head>

<body>
    <!-- Navbar Unificada Responsiva -->
    <?php include("../acessos/navbar_publico.php") ?>

    <section id="entre-em-contato">
        <div id="duvidas">
            <h2>Matricule-se no Curso Presencial!</h2>

            <p>Estude as ultimas tecnologias lançadas<br>com conforto de nossas salas e com professores capacitados.</p>
        </div>
    </section>

    <main>
        <h2>Turmas Abertas</h2>

        <?php if ($mensagem): ?>
            <p class="mensagem_alerta">
                <?= htmlspecialchars($mensagem) ?>
            </p>
        <?php endif; ?>

        <?php if (empty($turmas)): ?>
            <div class="mensagem_vazia">
                <p>Nenhuma turma disponível para matrícula no momento.</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Turma</th>
                            <th>Curso</th>
                            <th>Instrutor</th>
                            <th>Dias da Semana</th>
                            <th>Início</th>
                            <th>Fim</th>
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
                                <td data-label="Turma"><?= htmlspecialchars($t['nome_turma']) ?></td>
                                <td data-label="Curso"><?= htmlspecialchars($t['nome_curso']) ?></td>
                                <td data-label="Instrutor"><?= htmlspecialchars($t['instrutor_nome']) ?></td>
                                <td data-label="Dias da Semana">Segunda e Quinta</td>
                                <td data-label="Início"><?= date('d/m/Y', strtotime($t['data_inicio'])) ?></td>
                                <td data-label="Final"><?= date('d/m/Y', strtotime($t['data_fim'])) ?></td>
                                <td data-label="Horário"><?= htmlspecialchars($t['horario']) ?></td>
                                <td data-label="Vagas">
                                    <span class="vagas-badge"><?= htmlspecialchars($t['vagas_disponiveis']) ?></span>
                                </td>
                                <td data-label="Ação">
                                    <?php if (!$usuario_logado): ?>
                                        <!-- Visitante: botão leva pro login -->
                                        <a href="../acessos/login.php"><button>🔒 Fazer Login</button></a>

                                    <?php elseif ($ja_matriculado): ?>
                                        <!-- Já matriculado: botão desabilitado -->
                                        <button disabled>Já Matriculado</button>

                                    <?php else: ?>
                                        <!-- Logado e não matriculado: pode matricular -->
                                        <form method="POST">
                                            <input type="hidden" name="action" value="matricular">
                                            <input type="hidden" name="id_turma" value="<?= $t['id_turma'] ?>">
                                            <button type="submit">Matricular</button>
                                        </form>
                                    <?php endif; ?>
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