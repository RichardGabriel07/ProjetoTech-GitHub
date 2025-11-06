<?php
require_once '../php/conexao.php';
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) die("Acesso negado.");

$id_curso = filter_input(INPUT_GET, 'id_curso', FILTER_VALIDATE_INT) ?? null;

// Se não houver id_curso, mostra select
$cursos = $pdo->query("SELECT id_curso, nome_curso FROM curso ORDER BY nome_curso")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id_curso = intval($_POST['id_curso']);
  $titulo = $_POST['titulo'] ?? '';
  $ordem = intval($_POST['ordem'] ?? 0);

  $sql = "INSERT INTO modulos (id_curso, titulo, ordem) VALUES (?, ?, ?)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$id_curso, $titulo, $ordem]);

  header("Location: modulo_admin.php?id_curso={$id_curso}&sucesso=1");
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <title>Adicionar Módulo</title>
  <link rel="icon" href="../../ProjetoTech-GitHub/assets/imagens/Generated Image November 02, 2025 - 12_39AM.png" type="image/png">

</head>

<body>
  <h1>Adicionar Módulo</h1>
  <form method="post">
    <label>Curso:</label><br>
    <select name="id_curso" required>
      <option value="">Selecione</option>
      <?php foreach ($cursos as $c): ?>
        <option value="<?= $c['id_curso'] ?>" <?= ($id_curso && $id_curso == $c['id_curso']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($c['nome_curso']) ?>
        </option>
      <?php endforeach; ?>
    </select><br><br>

    <label>Título do módulo:</label><br>
    <input type="text" name="titulo" required><br><br>

    <label>Ordem:</label><br>
    <input type="number" name="ordem" value="1"><br><br>

    <button type="submit">Salvar Módulo</button>
  </form>
</body>

</html>