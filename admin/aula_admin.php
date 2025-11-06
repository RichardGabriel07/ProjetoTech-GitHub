<?php
require_once '../php/conexao.php';
session_start();
require_once '../acessos/verificar_admin.php';

$acao = isset($_GET['acao']) ? $_GET['acao'] : 'listar';
$id_modulo = isset($_GET['id_modulo']) ? intval($_GET['id_modulo']) : 0;
$id_aula = isset($_GET['id_aula']) ? intval($_GET['id_aula']) : 0;

if (!$id_modulo) die("Módulo inválido.");

/* EXCLUIR */
if ($acao === 'excluir' && $id_aula > 0) {
    $pdo->prepare("DELETE FROM aulas WHERE id_aula=?")->execute([$id_aula]);
    header("Location: aula_admin.php?id_modulo=$id_modulo");
    exit;
}

/* EDITAR */
$aula = null;
if ($acao === 'editar') {
    $stmt = $pdo->prepare("SELECT * FROM aulas WHERE id_aula=?");
    $stmt->execute([$id_aula]);
    $aula = $stmt->fetch(PDO::FETCH_ASSOC);
}

/* SALVAR */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $tipo = $_POST['tipo'];
    $conteudo = $_POST['conteudo'];
    $duracao = intval($_POST['duracao_minutos']);
    $ordem = intval($_POST['ordem']);

    if ($id_aula > 0) {
        $pdo->prepare("UPDATE aulas SET titulo=?, descricao=?, tipo=?, conteudo=?, duracao_minutos=?, ordem=? WHERE id_aula=?")
            ->execute([$titulo, $descricao, $tipo, $conteudo, $duracao, $ordem, $id_aula]);
    } else {
        $pdo->prepare("INSERT INTO aulas (id_modulo, titulo, descricao, tipo, conteudo, duracao_minutos, ordem, ativo) VALUES (?, ?, ?, ?, ?, ?, ?, 1)")
            ->execute([$id_modulo, $titulo, $descricao, $tipo, $conteudo, $duracao, $ordem]);
    }

    header("Location: aula_admin.php?id_modulo=$id_modulo");
    exit;
}

/* LISTAR AULAS */
$stmt = $pdo->prepare("SELECT * FROM aulas WHERE id_modulo=? ORDER BY ordem ASC");
$stmt->execute([$id_modulo]);
$aulas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Aulas Admin</title>
    <link rel="icon" href="../../ProjetoTech-GitHub/assets/imagens/Generated Image November 02, 2025 - 12_39AM.png" type="image/png">

</head>

<body>

    <h1>Aulas do Módulo</h1>
    <a href="aula_admin.php?acao=adicionar&id_modulo=<?= $id_modulo ?>">+ Adicionar Aula</a>
    <hr>

    <?php if ($acao === 'listar'): ?>

        <ul>
            <?php foreach ($aulas as $a): ?>
                <li>
                    <?= $a['titulo'] ?> (<?= $a['tipo'] ?>)
                    — <a href="aula.php?id_aula=<?= $a['id_aula'] ?>&id_curso=<?= isset($a['id_curso']) ? $a['id_curso'] : '' ?>">Ver</a>
                    — <a href="aula_admin.php?acao=editar&id_modulo=<?= $id_modulo ?>&id_aula=<?= $a['id_aula'] ?>">Editar</a>
                    — <a href="aula_admin.php?acao=excluir&id_modulo=<?= $id_modulo ?>&id_aula=<?= $a['id_aula'] ?>" onclick="return confirm('Excluir?');">Excluir</a>
                </li>
            <?php endforeach; ?>
        </ul>

    <?php else: ?>

        <h2><?= $id_aula ? 'Editar Aula' : 'Adicionar Aula' ?></h2>

        <form method="post">
            <label>Título:</label><br>
            <input type="text" name="titulo" value="<?= isset($aula['titulo']) ? $aula['titulo'] : '' ?>" required><br><br>

            <label>Descrição:</label><br>
            <textarea name="descricao"><?= isset($aula['descricao']) ? $aula['descricao'] : '' ?></textarea><br><br>

            <label>Tipo:</label><br>
            <select name="tipo">
                <option value="video" <?= isset($aula['tipo']) ? ($aula['tipo'] === 'video' ? 'selected' : '') : '' ?>>Vídeo</option>
                <option value="texto" <?= isset($aula['tipo']) ? ($aula['tipo'] === 'texto' ? 'selected' : '') : '' ?>>Texto</option>
                <option value="pdf" <?= isset($aula['tipo']) ? ($aula['tipo'] === 'pdf' ? 'selected' : '') : '' ?>>PDF</option>
                <option value="link" <?= isset($aula['tipo']) ? ($aula['tipo'] === 'link' ? 'selected' : '') : '' ?>>Link</option>
            </select><br><br>

            <label>Conteúdo (URL ou texto):</label><br>
            <textarea name="conteudo"><?= isset($aula['conteudo']) ? $aula['conteudo'] : '' ?></textarea><br><br>

            <label>Duração:</label><br>
            <input type="number" name="duracao_minutos" value="<?= isset($aula['duracao_minutos']) ? $aula['duracao_minutos'] : 0 ?>"><br><br>

            <label>Ordem:</label><br>
            <input type="number" name="ordem" value="<?= isset($aula['ordem']) ? $aula['ordem'] : 1 ?>"><br><br>

            <button type="submit">Salvar</button>
        </form>

        <a href="aula_admin.php?id_modulo=<?= $id_modulo ?>">Voltar</a>

    <?php endif; ?>

</body>

</html>