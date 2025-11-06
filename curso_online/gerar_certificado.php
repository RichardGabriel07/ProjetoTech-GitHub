<?php
// 1. Configuração e Segurança
session_start();
require_once '../php/conexao.php'; // Incluir conexão com o banco

// Verifica login e ID do curso
if (!isset($_SESSION['id_usuario']) || !isset($_GET['id_curso'])) {
    header("Location: ../acessos/login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_curso = (int) $_GET['id_curso'];

// Função para gerar código de validação único
function gerarCodigoValidacao($length = 10) {
    $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $codigo = '';
    for ($i = 0; $i < $length; $i++) {
        $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }
    return $codigo;
}

// =======================================================================
// 2. VERIFICAR CONDIÇÕES (LENDO O PROGRESSO SALVO)
// =======================================================================

// A. Buscar a matrícula e o progresso já salvo pelo 'aula.php'
$sql_matricula = "SELECT id_matricula, progresso FROM matriculas_online 
                  WHERE id_usuario = :usuario AND id_curso = :curso";
$stmt_matricula = $pdo->prepare($sql_matricula);
$stmt_matricula->execute([
    'usuario' => $id_usuario,
    'curso' => $id_curso
]);
$matricula = $stmt_matricula->fetch(PDO::FETCH_ASSOC);

if (!$matricula) {
    header("Location: meu_curso.php?id={$id_curso}&erro=matricula_invalida");
    exit;
}

$id_matricula = $matricula['id_matricula'];
$progresso_salvo = (float) $matricula['progresso'];

// B. Verificar o progresso salvo (que aula.php atualizou para 100.00)
if ($progresso_salvo < 100) {
    header("Location: meu_curso.php?id={$id_curso}&erro=progresso_incompleto");
    exit;
}

// C. Verificar se o certificado já existe
$sql_check_cert = "SELECT id_certificado FROM certificados 
                   WHERE id_usuario = :usuario AND id_curso = :curso";
$stmt_check_cert = $pdo->prepare($sql_check_cert);
$stmt_check_cert->execute([
    'usuario' => $id_usuario,
    'curso' => $id_curso
]);
$certificado_existe = $stmt_check_cert->fetchColumn();

if ($certificado_existe) {
    // Já existe, apenas redireciona para a visualização
    header("Location: ../../ProjetoTech-GitHub/clientes/certificado.php?curso={$id_curso}");
    exit;
}

// =======================================================================
// 3. GERAR O CERTIFICADO
// =======================================================================

// Buscar informações do curso para a carga horária
$sql_curso_info = "SELECT duracao FROM curso WHERE id_curso = :id";
$stmt_curso_info = $pdo->prepare($sql_curso_info);
$stmt_curso_info->execute(['id' => $id_curso]);
$curso_info = $stmt_curso_info->fetch(PDO::FETCH_ASSOC);

if (!$curso_info) {
    header("Location: meu_curso.php?id={$id_curso}&erro=curso_nao_encontrado");
    exit;
}

// ✅ CORREÇÃO AQUI: Usando $curso_info, que é a variável definida
$carga_horaria = intval($curso_info['duracao']);
$codigo_validacao = gerarCodigoValidacao();

// O SQL que inclui TUDO o que é NOT NULL (id_matricula)
$sql_insert_cert = "INSERT INTO certificados 
                    (id_usuario, id_curso, id_matricula, codigo_validacao, carga_horaria, data_emissao, data_conclusao) 
                    VALUES 
                    (:usuario, :curso, :matricula, :codigo, :carga, NOW(), NOW())";
        
$stmt_insert_cert = $pdo->prepare($sql_insert_cert);
        
try {
    $stmt_insert_cert->execute([
        'usuario' => $id_usuario,
        'curso' => $id_curso,
        'matricula' => $id_matricula, 
        'codigo' => $codigo_validacao,
        'carga' => $carga_horaria // Valor agora é 30 (int)
    ]);
    
    // ✅ Sucesso! Redireciona para a visualização
    header("Location: ../../ProjetoTech-GitHub/clientes/certificado.php?curso={$id_curso}");
    exit;

} catch (PDOException $e) {
    // ❌ Se ainda falhar, o erro será registrado no log (para debug mais tarde)
    error_log("ERRO FATAL NO INSERT CERTIFICADO: " . $e->getMessage()); 
    header("Location: meu_curso.php?id={$id_curso}&erro=db_falha");
    exit;
}
?>