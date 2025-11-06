<?php
session_start();
include '../php/conexao.php';  // Sua conexão PDO
include '../acessos/verificar_admin.php';  // Verifica se é admin

$mensagem = '';  // Para feedback
$turma_para_editar = null; // Variável para armazenar dados da turma se estiver em modo de edição
$modo_edicao = false;

// ----------------------------------------------------
// Lógica de Mensagem de Redirecionamento (GET)
// ----------------------------------------------------
if (isset($_GET['msg'])) {
    $mensagem = htmlspecialchars($_GET['msg']);
}

// ----------------------------------------------------
// 1. TRATAMENTO DO GET PARA CARREGAR DADOS DE EDIÇÃO
// ----------------------------------------------------
if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $id_turma_editar = (int)$_GET['edit_id'];
    
    $stmt_edit = $pdo->prepare("SELECT * FROM formar_turmas WHERE id_turma = ?");
    $stmt_edit->execute([$id_turma_editar]);
    $turma_data = $stmt_edit->fetch(PDO::FETCH_ASSOC);
    
    if ($turma_data) {
        $turma_para_editar = $turma_data;
        $modo_edicao = true;
    } else {
        $mensagem = "Erro: Turma para edição não encontrada.";
        // Redireciona para a página principal se o ID for inválido
        header("Location: criar_listar_e_editar_turmas.php?msg=" . urlencode($mensagem));
        exit();
    }
}

// ----------------------------------------------------
// 2. TRATAMENTO DO POST (CRIAR, EXCLUIR, EDITAR)
// ----------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Identifica a ação (Criar ou Editar)
    $action = isset($_POST['action']) ? $_POST['action'] : 'criar';

    if ($action === 'criar' || $action === 'editar') {
        // Coleta e sanitiza os dados (comuns a Criar e Editar)
        $id_turma = ($action === 'editar' && isset($_POST['id_turma'])) ? (int)$_POST['id_turma'] : 0;
        $nome_turma = trim($_POST['nome_turma']);
        $id_curso = isset($_POST['id_curso']) && $_POST['id_curso'] !== '' ? (int)$_POST['id_curso'] : 0;
        $id_instrutor = isset($_POST['id_instrutor']) && $_POST['id_instrutor'] !== '' ? (int)$_POST['id_instrutor'] : 0;
        $local = trim($_POST['local']);
        $data_inicio = $_POST['data_inicio'];
        $data_fim = $_POST['data_fim'];
        $horario = trim($_POST['horario']);
        $descricao = trim($_POST['descricao']);

        // Validação básica reforçada
        if (($action === 'editar' && $id_turma <= 0) || empty($nome_turma) || $id_curso <= 0 || $id_instrutor <= 0 || empty($local) || empty($data_inicio) || empty($horario) || empty($data_fim)) {
            $mensagem = "Preencha todos os campos obrigatórios e selecione um Curso e um Instrutor válidos.";
        } else {
            $pode_processar = true;

            // Validação de chaves estrangeiras (pode ser simplificada, mas é mais seguro)
            $check_curso = $pdo->prepare("SELECT id_curso FROM curso WHERE id_curso = ?");
            $check_curso->execute([$id_curso]);
            if ($check_curso->rowCount() == 0) {
                $mensagem = "Erro: O curso selecionado (ID $id_curso) não existe no banco.";
                $pode_processar = false;
            }

            if ($pode_processar) {
                $check_instrutor = $pdo->prepare("SELECT id_instrutor FROM instrutor WHERE id_instrutor = ?");
                $check_instrutor->execute([$id_instrutor]);
                if ($check_instrutor->rowCount() == 0) {
                    $mensagem = "Erro: O instrutor selecionado (ID $id_instrutor) não existe no banco.";
                    $pode_processar = false;
                }
            }

            if ($pode_processar) {
                try {
                    if ($action === 'criar') {
                        // BLOCO DE CRIAÇÃO
                        $sql = "INSERT INTO formar_turmas (nome_turma, id_curso, id_instrutor, local, data_inicio, data_fim, horario, descricao) 
                                VALUES (:nome, :curso, :instrutor, :local, :data_inicio, :data_fim, :horario, :descricao)";
                        $params = [
                            ':nome' => $nome_turma, ':curso' => $id_curso, ':instrutor' => $id_instrutor, 
                            ':local' => $local, ':data_inicio' => $data_inicio, ':data_fim' => $data_fim, 
                            ':horario' => $horario, ':descricao' => $descricao
                        ];
                        $mensagem_sucesso = "Turma criada com sucesso!";

                    } else {
                        // BLOCO DE EDIÇÃO (UPDATE)
                        $sql = "UPDATE formar_turmas SET 
                                nome_turma = :nome, id_curso = :curso, id_instrutor = :instrutor, 
                                local = :local, data_inicio = :data_inicio, data_fim = :data_fim, 
                                horario = :horario, descricao = :descricao
                                WHERE id_turma = :id_turma";
                        $params = [
                            ':nome' => $nome_turma, ':curso' => $id_curso, ':instrutor' => $id_instrutor, 
                            ':local' => $local, ':data_inicio' => $data_inicio, ':data_fim' => $data_fim, 
                            ':horario' => $horario, ':descricao' => $descricao, ':id_turma' => $id_turma
                        ];
                        $mensagem_sucesso = "Turma ID $id_turma atualizada com sucesso!";
                    }

                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($params);
                    
                    // Redireciona para evitar reenvio do formulário (F5)
                    header("Location: criar_listar_e_editar_turmas.php?msg=" . urlencode($mensagem_sucesso));
                    exit();

                } catch (PDOException $e) {
                    $mensagem = "Erro ao processar turma: " . $e->getMessage();
                }
            }
        }
        
    } elseif ($action === 'excluir' && isset($_POST['id_turma'])) {
        // --- BLOCO DE EXCLUSÃO (Já existente) ---
        $id_turma_excluir = (int)$_POST['id_turma'];

        try {
            $sql_excluir = "DELETE FROM formar_turmas WHERE id_turma = :id_turma";
            $stmt_excluir = $pdo->prepare($sql_excluir);
            $stmt_excluir->execute([':id_turma' => $id_turma_excluir]);

            $mensagem_sucesso = ($stmt_excluir->rowCount() > 0) ? "Turma ID $id_turma_excluir excluída com sucesso!" : "Erro: Turma ID $id_turma_excluir não encontrada.";
            
            header("Location: criar_listar_e_editar_turmas.php?msg=" . urlencode($mensagem_sucesso));
            exit();

        } catch (PDOException $e) {
            $mensagem = "Erro ao excluir turma: " . $e->getMessage();
        }
    }
}

// ----------------------------------------------------
// PREPARAÇÃO DOS DADOS PARA HTML
// ----------------------------------------------------
// Prepara os dados para os SELECTs (sempre recarrega)
$stmt_cursos = $pdo->query("SELECT id_curso, nome_curso FROM curso");
$stmt_instrutores = $pdo->query("SELECT id_instrutor, nome FROM instrutor");

// Query para listar todas as turmas
$sql_listagem = "SELECT ft.*, c.nome_curso, i.nome AS instrutor_nome 
                 FROM formar_turmas ft 
                 JOIN curso c ON ft.id_curso = c.id_curso 
                 JOIN instrutor i ON ft.id_instrutor = i.id_instrutor";
$turmas = $pdo->query($sql_listagem)->fetchAll(PDO::FETCH_ASSOC);

// Definindo o título e a ação do formulário com base no modo de edição
$titulo_form = $modo_edicao ? "Editar Turma ID {$turma_para_editar['id_turma']}" : "Criar Nova Turma";

// Variáveis para preencher o formulário
$form_data = $modo_edicao ? $turma_para_editar : [
    'nome_turma' => '', 'id_curso' => '', 'id_instrutor' => '', 
    'local' => '', 'data_inicio' => '', 'data_fim' => '', 
    'horario' => '', 'descricao' => ''
];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title><?= $titulo_form ?> - ProjetoTech</title>
    <link rel="stylesheet" href="../css/listar_e_editar.css">
    <link rel="icon" href="../../ProjetoTech-GitHub/assets/imagens/Generated Image November 02, 2025 - 12_39AM.png" type="image/png">

</head>

<body>
    <header>
        <div id="navbar">
            <h1>ProjetoTech</h1>
            <nav>
                <ul>
                    <li><a href="../admin/area_administrativa.php">Painel Admin</a></li>
                    <li><a href="../acessos/logout.php">Sair</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <main id="box_formulario_cadastro">
        <h2><?= $titulo_form ?></h2>
        <?php if ($mensagem): ?>
            <p class="mensagem_alerta"><?= htmlspecialchars($mensagem) ?></p> 
        <?php endif; ?>

        <form method="POST" id="form_turma">
            
            <input type="hidden" name="action" value="<?= $modo_edicao ? 'editar' : 'criar' ?>"> 
            
            <?php if ($modo_edicao): ?>
                <input type="hidden" name="id_turma" value="<?= htmlspecialchars($turma_para_editar['id_turma']) ?>">
            <?php endif; ?>

            <div class="input_group">
                <label for="nome_turma">Nome da Turma:</label>
                <input type="text" id="nome_turma" name="nome_turma" required 
                       value="<?= htmlspecialchars($form_data['nome_turma']) ?>">
            </div>

            <div class="input_group">
                <label for="id_curso">Curso:</label>
                <select id="id_curso" name="id_curso" required>
                    <option value="" disabled <?= $form_data['id_curso'] === '' ? 'selected' : '' ?>>Selecione um curso</option>
                    <?php 
                    // Rewind e iteração para a lista de cursos
                    $stmt_cursos->execute();
                    while ($row = $stmt_cursos->fetch(PDO::FETCH_ASSOC)): 
                        $selected = ($row['id_curso'] == $form_data['id_curso']) ? 'selected' : '';
                    ?>
                        <option value="<?= $row['id_curso'] ?>" <?= $selected ?>>
                            <?= htmlspecialchars($row['nome_curso']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="input_group">
                <label for="id_instrutor">Instrutor:</label>
                <select id="id_instrutor" name="id_instrutor" required>
                    <option value="" disabled <?= $form_data['id_instrutor'] === '' ? 'selected' : '' ?>>Selecione um instrutor</option>
                    <?php 
                    // Rewind e iteração para a lista de instrutores
                    $stmt_instrutores->execute();
                    while ($row = $stmt_instrutores->fetch(PDO::FETCH_ASSOC)): 
                        $selected = ($row['id_instrutor'] == $form_data['id_instrutor']) ? 'selected' : '';
                    ?>
                        <option value="<?= $row['id_instrutor'] ?>" <?= $selected ?>>
                            <?= htmlspecialchars($row['nome']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="input_group">
                <label for="local">Local:</label>
                <input type="text" id="local" name="local" required 
                       value="<?= htmlspecialchars($form_data['local']) ?>">
            </div>

            <div class="input_group">
                <label for="data_inicio">Data de Início:</label>
                <input type="date" id="data_inicio" name="data_inicio" required min="<?= date('Y-m-d'); ?>"
                       value="<?= htmlspecialchars($form_data['data_inicio']) ?>">
            </div>

            <div class="input_group">
                <label for="data_fim">Data do Término:</label>
                <input type="date" id="data_fim" name="data_fim" required min="<?= date('Y-m-d'); ?>"
                       value="<?= htmlspecialchars($form_data['data_fim']) ?>">
            </div>

            <div class="input_group">
                <label for="horario">Horário:</label>
                <input type="text" id="horario" name="horario" required placeholder="Ex: 08:00 às 12:00"
                       value="<?= htmlspecialchars($form_data['horario']) ?>">
            </div>

            <div class="input_group">
                <label for="descricao">Descrição (opcional):</label>
                <textarea id="descricao" name="descricao"><?= htmlspecialchars($form_data['descricao']) ?></textarea>
            </div>

            <button type="submit"><?= $modo_edicao ? 'Salvar Alterações' : 'Criar Turma' ?></button>
            
            <?php if ($modo_edicao): ?>
                 <a href="criar_listar_e_editar_turmas.php" style="display: block; margin-top: 10px; text-align: center; color: #007bff;">Cancelar Edição</a>
            <?php endif; ?>

        </form>
    </main>

    <section id="box_formulario_cadastro" style="margin-top: 20px;">
        <h2>Turmas Cadastradas:</h2>

        <table border="1">
            <tr>
                <th>ID Turma</th>
                <th>Nome da Turma</th>
                <th>Curso</th> 
                <th>Instrutor</th>
                <th>Local</th>
                <th>Data de Início</th>
                <th>Data do Término</th>
                <th>Horário</th>
                <th>Vagas Disponíveis</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>

            <?php foreach ($turmas as $turma): ?>
                <tr>
                    <td><?= htmlspecialchars($turma['id_turma']) ?></td>
                    <td><?= htmlspecialchars($turma['nome_turma']) ?></td>
                    <td><?= htmlspecialchars($turma['nome_curso']) ?></td> 
                    <td><?= htmlspecialchars($turma['instrutor_nome']) ?></td>
                    <td><?= htmlspecialchars($turma['local']) ?></td>
                    <td><?= htmlspecialchars($turma['data_inicio']) ?></td>
                    <td><?= htmlspecialchars($turma['data_fim']) ?></td>
                    <td><?= htmlspecialchars($turma['horario']) ?></td>
                    <td><?= htmlspecialchars($turma['vagas_disponiveis']) ?></td>
                    <td><?= htmlspecialchars($turma['status']) ?></td>
                    <td>
                        <button onclick="location.href='criar_listar_e_editar_turmas.php?edit_id=<?= htmlspecialchars($turma['id_turma']) ?>'" class="btn-editar">Editar</button>

                        <form method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir a turma <?= htmlspecialchars($turma['id_turma']) ?>?');">
                            <input type="hidden" name="id_turma" value="<?= htmlspecialchars($turma['id_turma']) ?>">
                            <input type="hidden" name="action" value="excluir">
                            <button type="submit" style="background-color: #dc3545; color: white;">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

</body>
</html>