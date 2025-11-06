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

// Variável para checar se houve nova inserção de progresso
$progresso_foi_atualizado = false;

if (!$stmt_check_progresso->fetch()) {
    $sql_insert_progresso = "INSERT INTO progresso_aulas (id_aula, id_matricula, concluida, data_conclusao) VALUES (?, ?, 1, NOW())";
    $stmt_insert = $pdo->prepare($sql_insert_progresso);
    $stmt_insert->execute([$id_aula, $id_matricula]);
    $progresso_foi_atualizado = true; // Marca que um novo progresso foi inserido
}


// =======================================================================
// 4. ATUALIZAR O PROGRESSO TOTAL (O SEU "PASSO 3" QUE FALTAVA)
//    (Executa sempre que a aula é carregada, garantindo consistência,
//     ou de forma otimizada, apenas se $progresso_foi_atualizado == true)
// =======================================================================

// Para garantir que o progresso esteja sempre correto, recalculamos.
// (Se quisesse otimizar, colocaria o bloco abaixo dentro de: if ($progresso_foi_atualizado) { ... } )

try {
    // A. Total de aulas ATIVAS do curso
    $sql_total = "SELECT COUNT(a.id_aula) FROM aulas a 
                  JOIN modulos m ON a.id_modulo = m.id_modulo
                  WHERE m.id_curso = :curso AND a.ativo = 1";
    $stmt_total = $pdo->prepare($sql_total);
    $stmt_total->execute(['curso' => $id_curso]);
    $total_aulas = $stmt_total->fetchColumn();

    // B. Aulas concluídas por esta matrícula
    $sql_concluidas = "SELECT COUNT(id_progresso) FROM progresso_aulas 
                       WHERE id_matricula = :matricula AND concluida = 1";
    $stmt_concluidas = $pdo->prepare($sql_concluidas);
    $stmt_concluidas->execute(['matricula' => $id_matricula]);
    $concluidas = $stmt_concluidas->fetchColumn();

    // C. Calcular e formatar o progresso
    $progresso_percentual = 0.00;
    if ($total_aulas > 0) {
        // Arredonda para 2 casas decimais, conforme a tabela matriculas_online
        $progresso_percentual = round(($concluidas / $total_aulas) * 100, 2); 
    }

    // D. Atualiza a coluna 'progresso' na tabela matriculas_online
    $sql_update_prog = "UPDATE matriculas_online 
                        SET progresso = :progresso 
                        WHERE id_matricula = :matricula";
    $stmt_update_prog = $pdo->prepare($sql_update_prog);
    $stmt_update_prog->execute([
        'progresso' => $progresso_percentual,
        'matricula' => $id_matricula
    ]);

} catch (PDOException $e) {
    // Se falhar, não impede a visualização da aula, mas registra o erro
    error_log("Erro ao atualizar progresso total: " . $e->getMessage());
}

// =======================================================================
// FIM DA ATUALIZAÇÃO DE PROGRESSO
// =======================================================================


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
    <link rel="stylesheet" href="../css/aula.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href="../../ProjetoTech-GitHub/assets/imagens/Generated Image November 02, 2025 - 12_39AM.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&display=swap" rel="stylesheet">
</head>

<body>

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
        <p>&copy; ProjetoTech - Todos os direitos reservados</p>
    </footer>
    
</body>

</html>