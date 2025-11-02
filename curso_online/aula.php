<?php
require_once '../php/conexao.php';
session_start();

// Receber IDs da URL e do usuário
$id_aula  = filter_input(INPUT_GET, 'id_aula', FILTER_VALIDATE_INT);
$id_curso = filter_input(INPUT_GET, 'id_curso', FILTER_VALIDATE_INT);
$id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;

if (!$id_aula || !$id_curso || !$id_usuario) {
    die("❌ Parâmetros inválidos ou usuário não logado.");
}

// 1. VERIFICAR MATRÍCULA E OBTER ID
$sql_matricula = "SELECT id_matricula FROM matriculas_online WHERE id_curso = ? AND id_usuario = ?";
$stmt_matricula = $pdo->prepare($sql_matricula);
$stmt_matricula->execute([$id_curso, $id_usuario]);
$matricula = $stmt_matricula->fetch(PDO::FETCH_ASSOC);

if (!$matricula) {
    header("Location: meu_curso.php?id={$id_curso}&erro=acesso_negado");
    exit;
}

$id_matricula = $matricula['id_matricula'];

// 2. BUSCAR DADOS DA AULA + MÓDULO
$sql_aula = "
    SELECT 
        a.titulo AS titulo_aula, 
        a.conteudo AS url_video, 
        a.duracao_minutos,
        m.titulo AS titulo_modulo
    FROM aulas a
    JOIN modulos m ON a.id_modulo = m.id_modulo
    WHERE a.id_aula = ?
      AND m.id_curso = ?
    LIMIT 1";
$stmt_aula = $pdo->prepare($sql_aula);
$stmt_aula->execute([$id_aula, $id_curso]);
$aula = $stmt_aula->fetch(PDO::FETCH_ASSOC);

if (!$aula) {
    header("Location: meu_curso.php?id={$id_curso}&erro=aula_nao_encontrada");
    exit;
}

// 3. REGISTRAR PROGRESSO DA AULA
$sql_check_progresso = "SELECT id_progresso FROM progresso_aulas WHERE id_aula = ? AND id_matricula = ?";
$stmt_check_progresso = $pdo->prepare($sql_check_progresso);
$stmt_check_progresso->execute([$id_aula, $id_matricula]);

if (!$stmt_check_progresso->fetch()) {
    $sql_insert_progresso = "INSERT INTO progresso_aulas (id_aula, id_matricula, concluida, data_conclusao) VALUES (?, ?, 1, NOW())";
    $stmt_insert = $pdo->prepare($sql_insert_progresso);
    $stmt_insert->execute([$id_aula, $id_matricula]);
}

// Variáveis para o HTML
$titulo_aula = htmlspecialchars($aula['titulo_aula']);
$titulo_modulo = htmlspecialchars($aula['titulo_modulo']);
$url_video = htmlspecialchars($aula['url_video']);
$duracao = $aula['duracao_minutos'];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aula: <?= $titulo_aula ?> - ProjetoTech</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/area_cliente.css">
    <link rel="stylesheet" href="estilo_aula.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&display=swap" rel="stylesheet">
</head>

<body>

    <!-- Navbar Unificada Responsiva -->
    <?php include('../acessos/navbar_publico.php'); ?>

    <header style="background: linear-gradient(135deg, #122A3F, #00b4d8); color: white; padding: 2rem; border-radius: 10px; margin: 2rem auto; max-width: 1200px;">
        <div style="display: flex; align-items: center; gap: 2rem;">
            <a href="meu_curso.php?id=<?= $id_curso ?>" style="color: white; text-decoration: none; font-size: 1.2rem;">
                ⬅️ Voltar ao Curso
            </a>
            <h1 style="margin: 0; font-size: 1.8rem;">📚 <?= $titulo_modulo ?></h1>
        </div>
    </header>

    <main class="container">
        <section class="conteudo-aula">
            <h2><?= $titulo_aula ?></h2>

            <div class="video-player">
                <iframe
                    width="100%"
                    height="500"
                    src="<?= $url_video ?>"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>

            </div>

            <p class="duracao">Duração: <?= $duracao ?> minutos</p>
            <p class="status">✅ Aula concluída (progresso atualizado automaticamente)</p>
        </section>
    </main>

    <footer>
        <p>&copy; Cursos Online - Todos os direitos reservados</p>
    </footer>

</body>

</html>