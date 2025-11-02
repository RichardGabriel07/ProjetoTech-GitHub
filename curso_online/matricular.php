<?php
// 1. SEMPRE começar com sessão e conexão
session_start();
require_once '../php/conexao.php';

// 2. SEGURANÇA: Se não estiver logado, manda pro login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../acessos/login.php");
    exit;
}

// 3. PEGAR o ID do curso da URL
if (!isset($_GET['id'])) {
    die("❌ Erro: Curso não especificado!");
}

$id_curso = (int) $_GET['id']; // Converter pra número
$id_usuario = $_SESSION['id_usuario'];

// 4. VERIFICAR: Esse curso existe?
$sql_curso = "SELECT * FROM curso WHERE id_curso = :id AND tipo_curso = 'Online'";
$stmt = $pdo->prepare($sql_curso);
$stmt->execute(['id' => $id_curso]);
$curso = $stmt->fetch();

// Se não existir, mostra erro
if (!$curso) {
    die("❌ Erro: Curso não encontrado ou não é online!");
}

// 5. VERIFICAR: Já está matriculado neste curso?
$sql_verifica = "SELECT * FROM matriculas_online 
                 WHERE id_usuario = :usuario 
                 AND id_curso = :curso";

$stmt = $pdo->prepare($sql_verifica);
$stmt->execute([
    'usuario' => $id_usuario,
    'curso' => $id_curso
]);

$ja_matriculado = $stmt->fetch();

// Se já estiver matriculado
if ($ja_matriculado) {
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Já Matriculado</title>
        <link rel="stylesheet" href="../css/area_cliente.css">
    </head>
    <body>
        <main style="text-align: center; padding: 50px;">
            <h1>⚠️ Você já está matriculado!</h1>
            <p>Você já está matriculado no curso <strong><?php echo $curso['nome_curso']; ?></strong></p>
            <br>
            <a href="./meu_curso.php?id=<?php echo $id_curso; ?>" class="btn">
                📚 Ver Minhas Aulas
            </a>
            <br><br>
            <a href="./cursos_online.php">← Voltar para Cursos</a>
        </main>
    </body>
    </html>
    <?php
    exit;
}

// 6. MATRICULAR o aluno no curso
try {
    $sql_matricula = "INSERT INTO matriculas_online 
                      (id_usuario, id_curso, data_matricula, status, progresso) 
                      VALUES 
                      (:usuario, :curso, NOW(), 'ativa', 0.00)";
    
    $stmt = $pdo->prepare($sql_matricula);
    $stmt->execute([
        'usuario' => $id_usuario,
        'curso' => $id_curso
    ]);
    
    // ✅ SUCESSO! Redireciona para ver o curso
    header("Location: ./meu_curso.php?id=$id_curso");
    exit;
    
} catch (PDOException $e) {
    // ❌ Deu erro
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Erro na Matrícula</title>
        <link rel="stylesheet" href="../css/area_cliente.css">
    </head>
    <body>
        <main style="text-align: center; padding: 50px;">
            <h1>❌ Erro ao Matricular</h1>
            <p>Ocorreu um erro ao processar sua matrícula.</p>
            <p style="color: red;"><?php echo $e->getMessage(); ?></p>
            <br>
            <a href="../cursos_online.php">← Voltar para Cursos</a>
        </main>
    </body>
    </html>
    <?php
}
?>