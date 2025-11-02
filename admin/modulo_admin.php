<?php
require_once '../php/conexao.php';
require_once '../acessos/verificar_admin.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$acao = isset($_GET['acao']) ? $_GET['acao'] : 'listar';
$id_curso = isset($_GET['id_curso']) ? intval($_GET['id_curso']) : 0;
$id_modulo = isset($_GET['id_modulo']) ? intval($_GET['id_modulo']) : 0;

if (!$id_curso) die("Curso inválido.");

/* DELETAR */
if ($acao === 'excluir' && $id_modulo > 0) {
    $pdo->prepare("DELETE FROM modulos WHERE id_modulo=?")->execute([$id_modulo]);
    header("Location: modulo_admin.php?id_curso=$id_curso&msg=modulo_excluido");
    exit;
}

/* EDITAR */
$modulo = null;
if ($acao === 'editar') {
    $stmt = $pdo->prepare("SELECT * FROM modulos WHERE id_modulo=? AND id_curso=?");
    $stmt->execute([$id_modulo, $id_curso]);
    $modulo = $stmt->fetch(PDO::FETCH_ASSOC);
}

/* SALVAR */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = $_POST['titulo'];
    $ordem = intval($_POST['ordem']);

    if ($id_modulo > 0) {
        $pdo->prepare("UPDATE modulos SET titulo=?, ordem=? WHERE id_modulo=? AND id_curso=?")
            ->execute([$titulo, $ordem, $id_modulo, $id_curso]);
    } else {
        $pdo->prepare("INSERT INTO modulos (id_curso, titulo, ordem) VALUES (?, ?, ?)")
            ->execute([$id_curso, $titulo, $ordem]);
    }

    header("Location: modulo_admin.php?id_curso=$id_curso");
    exit;
}

/* LISTAR */
$stmt = $pdo->prepare("SELECT * FROM modulos WHERE id_curso=? ORDER BY ordem ASC");
$stmt->execute([$id_curso]);
$modulos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Admin Módulos</title></head>
<body>

<h1>Módulos do Curso</h1>
<a href="modulo_admin.php?acao=adicionar&id_curso=<?= $id_curso ?>">+ Adicionar Módulo</a>
<hr>

<?php if ($acao === 'listar'): ?>

<ul>
<?php foreach ($modulos as $m): ?>
<li>
    <?= $m['titulo'] ?> (Ordem: <?= $m['ordem'] ?>)
    — <a href="modulo_admin.php?acao=editar&id_modulo=<?= $m['id_modulo'] ?>&id_curso=<?= $id_curso ?>">Editar</a>
    — <a href="modulo_admin.php?acao=excluir&id_modulo=<?= $m['id_modulo'] ?>&id_curso=<?= $id_curso ?>" onclick="return confirm('Excluir?');">Excluir</a>
    — <a href="aula_admin.php?id_modulo=<?= $m['id_modulo'] ?>">Gerenciar Aulas</a>
</li>
<?php endforeach; ?>
</ul>

<?php else: ?>

<h2><?= $id_modulo ? 'Editar Módulo' : 'Adicionar Módulo' ?></h2>

<form method="post">
    <label>Título:</label><br>
    <input type="text" name="titulo" value="<?= isset($modulo['titulo']) ? $modulo['titulo'] : '' ?>" required><br><br>

    <label>Ordem:</label><br>
    <input type="number" name="ordem" value="<?= isset($modulo['ordem']) ? $modulo['ordem'] : 1 ?>"><br><br>

    <button type="submit">Salvar</button>
</form>

<a href="modulo_admin.php?id_curso=<?= $id_curso ?>">Voltar</a>

<?php endif; ?>

</body>
</html>
