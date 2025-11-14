<?php
session_start();
require_once '../php/conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../acessos/login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
// 1. Variável de Controle: Verifica se há um código na URL
$codigo_view = isset($_GET['codigo']) ? htmlspecialchars($_GET['codigo']) : null;
$certificados = []; // Variável para a lista (Modo 2)
$cert = null; // Variável para o certificado único (Modo 1)

// 2. BUSCAR NOME DO ALUNO (Necessário em ambos os modos)
$sql_user = "SELECT nome FROM usuario WHERE id_usuario = :id";
$stmt_user = $pdo->prepare($sql_user);
$stmt_user->execute(['id' => $id_usuario]);
$usuario_info = $stmt_user->fetch(PDO::FETCH_ASSOC);
$nome_aluno = $usuario_info ? htmlspecialchars($usuario_info['nome']) : 'Aluno Desconhecido';

// =======================================================
// LÓGICA DE EXIBIÇÃO: Prioriza Visualização Única
// =======================================================
if ($codigo_view) {
    // MODO 1: VISUALIZAÇÃO DE CERTIFICADO ÚNICO

    $sql_cert_view = "SELECT 
                cert.carga_horaria, 
                cert.data_conclusao, 
                cert.codigo_validacao, 
                c.nome_curso
            FROM certificados cert
            JOIN curso c ON cert.id_curso = c.id_curso
            WHERE cert.codigo_validacao = :codigo AND cert.id_usuario = :usuario";

    $stmt_view = $pdo->prepare($sql_cert_view);
    $stmt_view->execute(['usuario' => $id_usuario, 'codigo' => $codigo_view]);
    $cert = $stmt_view->fetch(PDO::FETCH_ASSOC); // Preenche $cert

    if ($cert) {
        // Se encontrou, preenche as variáveis para uso no HTML do certificado:
        $nome_curso = htmlspecialchars($cert['nome_curso']);
        $carga_horaria = $cert['carga_horaria'];
        $data_conclusao = date('d/m/Y', strtotime($cert['data_conclusao']));
        $codigo_validacao = htmlspecialchars($cert['codigo_validacao']);
    } else {
        // Se o código foi passado, mas não encontrou (evita carregar a lista se for erro 404)
        $certificados = [];
    }
}

// Se NÃO está no modo de visualização única ($cert é NULL) OU a busca falhou para o código
if (!$codigo_view || !$cert) {
    // MODO 2: LISTAR TODOS OS CERTIFICADOS (Lógica original)
    $sql_lista = "SELECT 
                cert.*,
                c.nome_curso,
                c.duracao
            FROM certificados cert
            JOIN curso c ON cert.id_curso = c.id_curso
            WHERE cert.id_usuario = :usuario
            ORDER BY cert.data_emissao DESC";

    $stmt_lista = $pdo->prepare($sql_lista);
    $stmt_lista->execute(['usuario' => $id_usuario]);
    $certificados = $stmt_lista->fetchAll(PDO::FETCH_ASSOC); // Preenche $certificados
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Certificados - ProjetoTech</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/area_cliente.css">
    <link rel="icon" href="../../ProjetoTech-GitHub/assets/imagens/Generated Image November 02, 2025 - 12_39AM.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&family=Parisienne&display=swap" rel="stylesheet">

    <style>
        /* ======================================================= */
        /* ESTILOS DE VISUALIZAÇÃO ÚNICA (CERTIFICADO) */
        /* ======================================================= */
        .certificado-container {
            /* Tamanho A4 Paisagem (ajuste conforme o tamanho real da sua imagem) */
            width: 842px;
            height: 595px;
            margin: 50px auto;
            position: relative;

            /* SUA IMAGEM AQUI */
            background-image: url('../assets/imagens/certificado_finalProjetoTech.png');
            background-size: contain;
            background-repeat: no-repeat;

            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            font-family: 'Arial', sans-serif;
            color: #122A3F;
            overflow: hidden;
        }

        .certificado-texto {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        /* ⚠️ AJUSTE ESTES VALORES PARA ALINHAR COM SUA IMAGEM DE FUNDO */
        .nome-aluno {
            position: absolute;
            top: 210px;
            /* AJUSTAR: Distância do topo */
            left: 50%;
            transform: translateX(-50%);
            font-size: 2.2em;
            color: #00b4d8;
            /* Cor Ciano para destaque */
            font-weight: 700;
            width: 700px;
            text-align: center;
        }

        .nome-curso {
            position: absolute;
            top: 278px;
            /* AJUSTAR */
            left: 50.5%;
            transform: translateX(-50%);
            font-size: 0.78em;
            color: #122A3F;
            width: 700px;
            text-align: center;
            font-weight: 600;
        }

        .detalhes {
            position: absolute;
            top: 336px;
            /* AJUSTAR */
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.8em;
            color: #122A3F;
            width: 700px;
            text-align: center;
        }

        .detalhes p {
            margin: 3px 0;
        }

        .codigo-validacao {
            position: absolute;
            bottom: 50px;
            right: 50px;
            font-size: 0.9em;
            color: #122A3F;
            padding: 5px 10px;
            border: 1px solid white;
        }

        .assinatura-diretor {
            font-family: "Parisienne", cursive;
            position: absolute;
            bottom: 175px;
            /* AJUSTAR: Distância da parte inferior da imagem */
            left: 48%;
            transform: translateX(-50%);
            font-size: 1.2em;
            color: #122A3F;
            /* Cor do texto da assinatura */
            font-weight: bold;
            text-align: center;
            width: 300px;
            /* Largura da área da assinatura */
        }

        #seus-certificados{
            text-align: center;
        }

        #seus-certificados > h1{
            font-size: 2.2rem;
        }

        .no-certificated{
            font-size: 1.2rem;
        }

        /* ======================================================= */
        /* CORREÇÃO DE IMPRESSÃO (Background Image) */
        /* ======================================================= */
        /* ======================================================= */
        /* CORREÇÃO DE IMPRESSÃO (Background Image e Conteúdo) */
        /* ======================================================= */
        @media print {
            /* 1. Esconde a navbar e os botões de ação */
            /* Se houver uma classe específica para o menu, use-a. Aqui, usamos o body > * mais seguro. */
            body>*:not(main),
            /* Esconde tudo fora da tag main */
            .btn-primary,
            /* Esconde o botão imprimir */
            .btn-secondary,
            /* Esconde o botão voltar */
            .titulo-pagina,
            .no-certificated,
            /* Esconde o título da página */
            .certificados-grid,
            /* Esconde a lista, se estiver presente */
            .container>header,
            /* Esconde o cabeçalho da página (Meu Certificado) */
            div[style*="text-align: center"]

            /* Esconde a div que envolve os botões */
                {
                display: none !important;
            }

            /* 2. Garante que a tag main e o container principal sejam exibidos */
            main {
                display: block !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }

            .certificado-container {
                position: fixed;
                left: 0;
                top: 0;
                margin: 0;
                padding: 0;
                box-shadow: none;

                /* PROPRIEDADES ESSENCIAIS PARA FORÇAR O FUNDO: */
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;

                /* Força o re-carregamento do fundo (USE SUA STRING BASE64 AQUI OU O PATH CORRETO) */
                background-image: url('../../ProjetoTech-GitHub/assets/imagens/certificado_finalProjetoTech.png') !important;
                /* Se estiver usando Base64, substitua a linha acima por:
                background-image: sua_string_base64_aqui !important;
                */
                background-size: contain !important;
                background-repeat: no-repeat !important;
                display: block !important;
            }

            .assinatura-diretor {
                font-family: "Parisienne", cursive;

            }

            /* 3. Garante que o texto tenha cor e seja visível */
            .nome-aluno,
            .nome-curso,
            .detalhes,
            .assinatura-diretor,
            .codigo-validacao {
                color: #122A3F !important;
                /* Força a cor do texto para ser visível no papel */
                display: block !important;
            }
        }

        /* ======================================================= */
        /* ESTILOS DA LISTA (Mantidos do seu arquivo) */
        /* ======================================================= */
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-top: 5px solid;
            border-image: linear-gradient(90deg, #00b4d8, #00b4d8) 1;
            /* Altere a cor do gradiente se quiser algo diferente de #667eea, #764ba2 */
            transition: transform 0.3s;
        }

        .certificado-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
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
            background: #00b4d8;
            /* Usando o Ciano como primário */
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
    <?php include '../acessos/navbar_publico.php';

    $nomeCapitalized = ucfirst($nome_aluno);
    ?>

    <main style="max-width: 1200px; margin: 0 auto; padding: 20px;">
        <div id="seus-certificados">
            <h1 class="no-certificated">🎓 Meus Certificados</h1>
            <p>Aqui você tem acesso aos seus certificados! </p>

        </div>

        <?php if ($cert): ?>
            <div class="certificado-container">
                <div class="certificado-texto">

                    <h2 class="nome-aluno"><?= $nomeCapitalized ?></h2>
                    <h3 class="nome-curso"><?= $nome_curso ?></h3>

                    <div class="detalhes">
                        <p>Pela conclusão do treinamento com carga horária de <?= $carga_horaria ?> horas</p>
                        <p>Emitido em: <?= $data_conclusao ?></p>
                    </div>

                    <div class="codigo-validacao">
                        Cód. de Validação: <strong><?= $codigo_validacao ?></strong>
                    </div>

                    <h2 class="assinatura-diretor">Rhuan Gabriel Baraúna</h2>
                </div>
            </div>

            <div style="text-align: center; margin-top: 30px;">
                <button onclick="window.print()" class="btn btn-primary" style="margin-right: 10px; background: #00b4d8;">
                    🖨️ Imprimir Certificado
                </button>
                <a href="certificado.php" class="btn btn-secondary" style="background: #6c757d;">
                    ← Voltar para Lista
                </a>
            </div>

        <?php elseif (!empty($certificados)): ?>
            <p style="color: #666; margin-bottom: 20px;">
                Você possui <strong><?php echo count($certificados); ?></strong> certificado(s) emitido(s).
            </p>

            <div class="certificados-grid">
                <?php foreach ($certificados as $cert_item): ?>
                    <div class="certificado-card">
                        <div class="cert-icon">🏆</div>

                        <div class="cert-titulo">
                            <?php echo htmlspecialchars($cert_item['nome_curso']); ?>
                        </div>

                        <div class="cert-info">
                            ⏱️ <?php echo $cert_item['carga_horaria']; ?> horas
                        </div>

                        <div class="cert-info">
                            📅 Concluído em <?php echo date('d/m/Y', strtotime($cert_item['data_conclusao'])); ?>
                        </div>

                        <div class="cert-codigo">
                            <?php echo htmlspecialchars($cert_item['codigo_validacao']); ?>
                        </div>

                        <div class="cert-acoes">
                            <a href="certificado.php?codigo=<?php echo $cert_item['codigo_validacao']; ?>"
                                class="btn btn-primary"
                                target="_blank">
                                📄 Visualizar
                            </a>
                            <a href="validar_certificado.php?codigo=<?php echo $cert_item['codigo_validacao']; ?>"
                                class="btn btn-secondary"
                                target="_blank">
                                🔍 Validar
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <div class="vazio">
                <p style="font-size: 48px;">📜</p>
                <h2>Você ainda não possui certificados</h2>
                <p>Complete seus cursos para receber certificados!</p>
                <br>
                <a href="../curso_online/cursos_online.php" class="btn btn-primary" style="display: inline-block; padding: 15px 30px;">
                    Ver Cursos Disponíveis
                </a>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>