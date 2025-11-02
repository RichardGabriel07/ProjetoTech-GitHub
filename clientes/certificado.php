<?php
session_start();
require_once '../php/conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../acessos/login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Buscar certificados do usuário
$sql = "SELECT 
            cert.*,
            c.nome_curso,
            c.duracao
        FROM certificados cert
        JOIN curso c ON cert.id_curso = c.id_curso
        WHERE cert.id_usuario = :usuario
        ORDER BY cert.data_emissao DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['usuario' => $id_usuario]);
$certificados = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Certificados - ProjetoTech</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/area_cliente.css">
    <style>
        .certificados-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }
        
        .certificado-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-top: 5px solid;
            border-image: linear-gradient(90deg, #667eea, #764ba2) 1;
            transition: transform 0.3s;
        }
        
        .certificado-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        
        .cert-icon {
            font-size: 48px;
            text-align: center;
            margin-bottom: 15px;
        }
        
        .cert-titulo {
            font-size: 20px;
            color: #122A3F;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .cert-info {
            font-size: 14px;
            color: #666;
            margin: 5px 0;
            text-align: center;
        }
        
        .cert-codigo {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            text-align: center;
            margin: 15px 0;
            word-break: break-all;
        }
        
        .cert-acoes {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .btn {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: transform 0.3s;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        .btn-secondary {
            background: #f8f9fa;
            color: #333;
            border: 1px solid #ddd;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .vazio {
            text-align: center;
            padding: 50px;
            color: #666;
        }
    </style>
</head>
<body>
    <?php include '../acessos/navbar_publico.php'; ?>
    
    <main style="max-width: 1200px; margin: 0 auto; padding: 20px;">
        <h1>🎓 Meus Certificados</h1>
        
        <?php if (empty($certificados)): ?>
            <div class="vazio">
                <p style="font-size: 48px;">📜</p>
                <h2>Você ainda não possui certificados</h2>
                <p>Complete seus cursos para receber certificados!</p>
                <br>
                <a href="../curso_online/cursos_online.php" class="btn btn-primary" style="display: inline-block; padding: 15px 30px;">
                    Ver Cursos Disponíveis
                </a>
            </div>
        <?php else: ?>
            
            <p style="color: #666; margin-bottom: 20px;">
                Você possui <strong><?php echo count($certificados); ?></strong> certificado(s) emitido(s).
            </p>
            
            <div class="certificados-grid">
                <?php foreach ($certificados as $cert): ?>
                    <div class="certificado-card">
                        <div class="cert-icon">🏆</div>
                        
                        <div class="cert-titulo">
                            <?php echo htmlspecialchars($cert['nome_curso']); ?>
                        </div>
                        
                        <div class="cert-info">
                            ⏱️ <?php echo $cert['carga_horaria']; ?> horas
                        </div>
                        
                        <div class="cert-info">
                            📅 Concluído em <?php echo date('d/m/Y', strtotime($cert['data_conclusao'])); ?>
                        </div>
                        
                        <div class="cert-codigo">
                            <?php echo htmlspecialchars($cert['codigo_validacao']); ?>
                        </div>
                        
                        <div class="cert-acoes">
                            <a href="certificado.php?codigo=<?php echo $cert['codigo_validacao']; ?>" 
                               class="btn btn-primary"
                               target="_blank">
                                📄 Visualizar
                            </a>
                            <a href="validar_certificado.php?codigo=<?php echo $cert['codigo_validacao']; ?>" 
                               class="btn btn-secondary"
                               target="_blank">
                                🔍 Validar
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
        <?php endif; ?>
    </main>
</body>
</html>