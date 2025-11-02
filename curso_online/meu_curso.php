<?php
session_start();
require_once '../php/conexao.php';

// Segurança
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../acessos/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("❌ Curso não especificado!");
}

$id_curso = (int) $_GET['id'];
$id_usuario = $_SESSION['id_usuario'];

// Verificar matrícula
$sql_matricula = "SELECT * FROM matriculas_online 
                  WHERE id_usuario = :usuario AND id_curso = :curso";
$stmt = $pdo->prepare($sql_matricula);
$stmt->execute(['usuario' => $id_usuario, 'curso' => $id_curso]);
$matricula = $stmt->fetch();

if (!$matricula) {
    die("❌ Você não está matriculado! <a href='../cursos_online.php'>Ver cursos</a>");
}

$id_matricula = $matricula['id_matricula'];

// Buscar curso
$sql_curso = "SELECT * FROM curso WHERE id_curso = :id";
$stmt = $pdo->prepare($sql_curso);
$stmt->execute(['id' => $id_curso]);
$curso = $stmt->fetch();

// Buscar módulos
$sql_modulos = "SELECT * FROM modulos 
                WHERE id_curso = :curso AND ativo = 1 
                ORDER BY ordem";
$stmt = $pdo->prepare($sql_modulos);
$stmt->execute(['curso' => $id_curso]);
$modulos = $stmt->fetchAll();

// Calcular progresso
$sql_total = "SELECT COUNT(*) as total 
              FROM aulas a
              JOIN modulos m ON a.id_modulo = m.id_modulo
              WHERE m.id_curso = :curso AND a.ativo = 1";
$stmt = $pdo->prepare($sql_total);
$stmt->execute(['curso' => $id_curso]);
$total_aulas = $stmt->fetch()['total'];

$sql_concluidas = "SELECT COUNT(*) as concluidas 
                   FROM progresso_aulas 
                   WHERE id_matricula = :matricula AND concluida = 1";
$stmt = $pdo->prepare($sql_concluidas);
$stmt->execute(['matricula' => $id_matricula]);
$aulas_concluidas = $stmt->fetch()['concluidas'];

$progresso = $total_aulas > 0 ? round(($aulas_concluidas / $total_aulas) * 100, 2) : 0;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $curso['nome_curso']; ?> - ProjetoTech</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/area_cliente.css">
    <link rel="icon" href="../assets/imagens/Generated Image November 02, 2025 - 12_39AM.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/imagens/Generated Image November 02, 2025 - 12_39AM.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/imagens/Generated Image November 02, 2025 - 12_39AM.png">
    <link rel="manifest" href="../assets/imagens/Generated Image November 02, 2025 - 12_39AM.png">
    <link rel="mask-icon" href="../assets/imagens/Generated Image November 02, 2025 - 12_39AM.png" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <meta name="description" content="Curso online de <?php echo $curso['nome_curso']; ?>">
    <meta name="keywords" content="curso, online, <?php echo $curso['nome_curso']; ?>">
    <meta name="author" content="ProjetoTech">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">
    <meta name="google" content="notranslate">
    <meta name="google" content="notranslate">
    <meta name="google" content="notranslate">
    <meta name="google" content="notranslate">
    <style>
        .curso-header {
            background: linear-gradient(135deg, #122A3F, #00b4d8);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .progresso-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .barra-progresso {
            width: 100%;
            height: 30px;
            background: #e0e0e0;
            border-radius: 15px;
            overflow: hidden;
            position: relative;
        }
        
        .barra-preenchida {
            height: 100%;
            background: linear-gradient(90deg, #00b4d8, #122A3F);
            transition: width 0.5s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .modulo {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .modulo h3 {
            color: #122A3F;
            border-bottom: 3px solid #00b4d8;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        
        .aula {
            padding: 15px;
            margin: 10px 0;
            border-left: 4px solid #ccc;
            background: #f8f9fa;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .aula:hover {
            transform: translateX(5px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .aula.concluida {
            border-left-color: #28a745;
            background: #d4edda;
        }
        
        .aula.disponivel {
            border-left-color: #00b4d8;
            cursor: pointer;
        }
        
        .aula.bloqueada {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .icone {
            font-size: 24px;
            margin-right: 10px;
        }

        .btn-voltar {
            text-align: center;
            margin-top: 20px;
        }

        .btn-voltar a {
            text-decoration: none;
            color: white;
            background: #00b4d8;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease;
        }   

        .btn-voltar a:hover {
            background: #0096c7;
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0, 180, 216, 0.4);
        }
    </style>
</head>
<body>
    <?php include '../acessos/navbar_publico.php'; ?>
    
    <main style="max-width: 1200px; margin: 0 auto; padding: 20px;">
        
        <!-- Cabeçalho do Curso -->
        <div class="curso-header">
            <h1>📚 <?php echo $curso['nome_curso']; ?></h1>
            <p><?php echo $curso['descricao']; ?></p>
            <p>⏱️ Duração: <?php echo $curso['duracao']; ?></p>
        </div>
        
        <!-- Barra de Progresso -->
        <div class="progresso-container">
            <h2>📊 Seu Progresso</h2>
            <div class="barra-progresso">
                <div class="barra-preenchida" style="width: <?php echo $progresso; ?>%">
                    <?php echo $progresso; ?>%
                </div>
            </div>
            <p style="margin-top: 10px;">
                ✅ <strong><?php echo $aulas_concluidas; ?></strong> de 
                <strong><?php echo $total_aulas; ?></strong> aulas concluídas
            </p>
            
            <?php if ($progresso >= 100): ?>
                <div style="background: #28a745; color: white; padding: 15px; border-radius: 10px; margin-top: 15px;">
                    <h3>🎉 PARABÉNS! Você completou o curso!</h3>
                    <a href="../clientes/certificado.php?curso=<?php echo $id_curso; ?>" 
                       style="color: white; text-decoration: underline;">
                        🎓 Clique aqui para ver seu certificado
                    </a>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Módulos e Aulas -->
        <?php foreach ($modulos as $modulo): ?>
            
            <div class="modulo">
                <h3>📖 <?php echo $modulo['titulo']; ?></h3>
                <?php if ($modulo['descricao']): ?>
                    <p style="color: #666; margin-bottom: 15px;">
                        <?php echo $modulo['descricao']; ?>
                    </p>
                <?php endif; ?>
                
                <?php
                // Buscar aulas deste módulo
                $sql_aulas = "SELECT * FROM aulas 
                              WHERE id_modulo = :modulo AND ativo = 1 
                              ORDER BY ordem";
                $stmt = $pdo->prepare($sql_aulas);
                $stmt->execute(['modulo' => $modulo['id_modulo']]);
                $aulas = $stmt->fetchAll();
                
                foreach ($aulas as $aula):
                    // Verificar se esta aula foi concluída
                    $sql_check = "SELECT * FROM progresso_aulas 
                                  WHERE id_matricula = :matricula 
                                  AND id_aula = :aula 
                                  AND concluida = 1";
                    $stmt = $pdo->prepare($sql_check);
                    $stmt->execute([
                        'matricula' => $id_matricula,
                        'aula' => $aula['id_aula']
                    ]);
                    $concluida = $stmt->fetch();
                    
                    // Definir classe CSS
                    $classe = $concluida ? 'concluida' : 'disponivel';
                    $icone = $concluida ? '✅' : '▶️';
                    $link = "aula.php?id_aula=" . $aula['id_aula'] . "&id_curso=" . $id_curso;
                ?>
                
                <a href="<?php echo $link; ?>" style="text-decoration: none; color: inherit;">
                    <div class="aula <?php echo $classe; ?>">
                        <span class="icone"><?php echo $icone; ?></span>
                        <strong><?php echo $aula['titulo']; ?></strong>
                        
                        <?php if ($aula['duracao_minutos']): ?>
                            <span style="float: right; color: #666;">
                                ⏱️ <?php echo $aula['duracao_minutos']; ?> min
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($concluida): ?>
                            <span style="float: right; color: #28a745; margin-right: 20px;">
                                Concluída
                            </span>
                        <?php endif; ?>
                    </div>
                </a>
                
                <?php endforeach; ?>
            </div>
            
        <?php endforeach; ?>
        
        <?php if (empty($modulos)): ?>
            <div style="text-align: center; padding: 50px;">
                <p>⚠️ Este curso ainda não possui módulos cadastrados.</p>
            </div>
        <?php endif; ?>

        <div class="btn-voltar">
            <a href="./cursos_online.php" class="btn">
                ← Voltar para Cursos
            </a>
        </div>
        
    </main>
</body>
</html>