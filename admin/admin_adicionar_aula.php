<?php
require_once '../php/conexao.php';
require_once '../acessos/verificar_admin.php';

$id_modulo = filter_input(INPUT_GET, 'id_modulo', FILTER_VALIDATE_INT);

// Para escolher módulo se não veio via GET
$modulos = $pdo->query("SELECT m.id_modulo, m.titulo AS modulo, c.titulo AS curso FROM modulos m JOIN curso c ON m.id_curso = c.id_curso ORDER BY c.titulo, m.ordem")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id_modulo = isset($_POST['id_modulo']) ? intval($_POST['id_modulo']) : null;
  $titulo = isset($_POST['titulo']) ? $_POST['titulo'] : '';
  $descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';
  $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 'video';
  $conteudo = isset($_POST['conteudo']) ? $_POST['conteudo'] : '';
  $duracao = isset($_POST['duracao_minutos']) ? intval($_POST['duracao_minutos']) : 0;
  $ordem = isset($_POST['ordem']) ? intval($_POST['ordem']) : 0;

  $sql = "INSERT INTO aulas (id_modulo, titulo, descricao, tipo, conteudo, duracao_minutos, ordem, ativo, data_criacao) VALUES (?, ?, ?, ?, ?, ?, ?, 1, NOW())";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$id_modulo, $titulo, $descricao, $tipo, $conteudo, $duracao, $ordem]);

  header("Location: admin_aulas.php?id_modulo={$id_modulo}&sucesso=1");
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <title>Adicionar Aula</title>
  <link rel="icon" href="../../ProjetoTech-GitHub/assets/imagens/Generated Image November 02, 2025 - 12_39AM.png" type="image/png">

</head>

<body>
  <h1>Adicionar Aula</h1>
  <form method="post">
    <label>Módulo:</label><br>
    <select name="id_modulo" required>
      <option value="">Selecione</option>
      <?php foreach ($modulos as $m): ?>
        <option value="<?= $m['id_modulo'] ?>" <?= ($id_modulo && $id_modulo == $m['id_modulo']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($m['curso'] . " → " . $m['modulo']) ?>
        </option>
      <?php endforeach; ?>
    </select><br><br>

    <label>Título:</label><br><input type="text" name="titulo" required><br><br>
    <label>Descrição:</label><br><textarea name="descricao" rows="4"></textarea><br><br>

    <label>Tipo:</label><br>
    <select name="tipo">
      <option value="video">Vídeo</option>
      <option value="texto">Texto</option>
      <option value="pdf">PDF</option>
      <option value="link">Link</option>
    </select><br><br>

    <label>Conteúdo (URL ou texto):</label><br>
    <textarea name="conteudo" rows="3"></textarea><br><br>

    <label>Duração (minutos):</label><br><input type="number" name="duracao_minutos"><br><br>

    <label>Ordem:</label><br><input type="number" name="ordem" value="1"><br><br>

    <button type="submit">Salvar Aula</button>
  </form>
</body>

</html>