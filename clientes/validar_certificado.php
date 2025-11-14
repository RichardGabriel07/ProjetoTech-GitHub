<?php
// Não precisa de session_start() nem verificação de login, pois a página é pública.
require_once '../php/conexao.php'; // Incluir conexão com o banco

// Inicia a sessão no início do arquivo
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// 1. Recebe o código via GET (do link "Validar") ou POST (do formulário)
$codigo_validacao_input = null;
$certificado_encontrado = false;
$cert_data = [];

if (isset($_GET['codigo'])) {
    $codigo_validacao_input = htmlspecialchars($_GET['codigo']);
} elseif (isset($_POST['codigo_validacao'])) {
    $codigo_validacao_input = htmlspecialchars(trim($_POST['codigo_validacao']));
}

// 2. Lógica de busca no banco de dados
if ($codigo_validacao_input) {
    $sql = "SELECT 
                u.nome AS nome_aluno,
                c.nome_curso,
                c.duracao AS carga_horaria,
                cert.data_conclusao,
                cert.data_emissao
            FROM certificados cert
            JOIN usuario u ON cert.id_usuario = u.id_usuario
            JOIN curso c ON cert.id_curso = c.id_curso
            WHERE cert.codigo_validacao = :codigo";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['codigo' => $codigo_validacao_input]);
    $cert_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cert_data) {
        $certificado_encontrado = true;
        // Formatar as datas
        $data_conclusao = date('d/m/Y', strtotime($cert_data['data_conclusao']));
        $data_emissao = date('d/m/Y', strtotime($cert_data['data_emissao']));
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validação de Certificado - ProjetoTech</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="icon" href="../../ProjetoTech-GitHub/assets/imagens/Generated Image November 02, 2025 - 12_39AM.png" type="image/png">

    <style>
        :root {
            --ciano: #00b4d8;
            --azul-marinho: #122A3F;
            --fundo-claro: #f8f9fa;
        }

        body {
            background-color: var(--fundo-claro);
            font-family: 'Arial', sans-serif;
            color: var(--azul-marinho);
        }

        .validation-container {
            max-width: 700px;
            margin: 80px auto;
            padding: 40px;
            background: white;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .validation-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            border-radius: 5px 5px 0 0;
            background: linear-gradient(90deg, rgb(18, 42, 63), rgb(0, 180, 216));
        }


        body>h1 {
            color: var(--azul-marinho);
            text-align: center;
            margin-bottom: 30px;
            font-size: 2em;
        }

        .validation-container>h1 {
            padding: 30px 0;
            text-align: center;
        }

        /* --- Formulário --- */
        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }

        .search-form input[type="text"] {
            flex-grow: 1;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            outline: none;
            transition: border-color 0.3s;
        }

        .search-form input[type="text"]:focus {
            border-color: var(--ciano);
        }

        .btn-validate {
            background-image: linear-gradient(90deg, #122A3F 0%, #1e3a5f 50%);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s;
        }

        .btn-validate:hover {
            background-color: #00a4c8;
        }

        /* --- Resultado --- */
        .validation-result {
            margin-top: 20px;
            padding: 20px;
            border-radius: 8px;
        }

        .success-box {
            background-color: #e6ffed;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .error-box {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .validation-result p {
            margin: 10px 0;
            font-size: 1.1em;
        }

        .validation-result strong {
            color: var(--azul-marinho);
            display: inline-block;
            min-width: 150px;
            font-weight: 600;
        }

        .result-title {
            font-size: 1.5em;
            color: var(--azul-marinho);
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
        /* Hero Section - Contato */
#entre-em-contato {
    background: linear-gradient(90deg, #122A3F 0%, #1e3a5f 100%);
    color: white;
    text-align: center;
    padding: 4rem 2rem;
    margin-bottom: 3rem;
    position: relative;
    overflow: hidden;
}

#entre-em-contato::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.03)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.03)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.1;
}

#entre-em-contato h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    position: relative;
    z-index: 1;
}

#entre-em-contato p {
    font-size: 1.2rem;
    opacity: 0.9;
    position: relative;
    z-index: 1;
    max-width: 600px;
    margin: 0 auto;
}
    </style>
</head>

<body>

    <?php include('../acessos/navbar_publico.php'); ?>

    <section id="entre-em-contato">
        <div id="duvidas">
            <h2>Valide o Certificado do ProjetoTech</h2>

            <p>Verifique a autenticidade e validade do certificado de conclusão de curso.</p>
        </div>
    </section>

    <div class="validation-container">
        <h1>Validação de Certificado</h1>

        <form method="POST" class="search-form">
            <input type="text"
                name="codigo_validacao"
                placeholder="Digite o código de validação..."
                value="<?php echo $codigo_validacao_input ?: ''; ?>"
                required>
            <button type="submit" class="btn-validate">Validar</button>
        </form>

        <?php if ($codigo_validacao_input && !$certificado_encontrado): ?>
            <div class="validation-result error-box">
                <div class="result-title">Certificado Não Encontrado</div>
                <p style="text-align: center;">O código **<?php echo $codigo_validacao_input; ?>** não corresponde a nenhum certificado válido emitido pela ProjetoTech.</p>
                <p style="text-align: center; font-size: 0.9em; margin-top: 15px;">Verifique o código e tente novamente.</p>
            </div>

        <?php elseif ($certificado_encontrado): ?>
            <div class="validation-result success-box">
                <div class="result-title">Certificado Válido e Autêntico</div>

                <p><strong>Status:</strong> <span style="color: #28a745; font-weight: bold;">Autenticado</span></p>
                <hr style="border-top: 1px solid #c3e6cb; margin: 15px 0;">

                <p><strong>Aluno(a):</strong> <?php echo htmlspecialchars($cert_data['nome_aluno']); ?></p>
                <p><strong>Curso:</strong> <?php echo htmlspecialchars($cert_data['nome_curso']); ?></p>
                <p><strong>Carga Horária:</strong> <?php echo htmlspecialchars($cert_data['carga_horaria']); ?></p>
                <p><strong>Data de Conclusão:</strong> <?php echo $data_conclusao; ?></p>
                <p><strong>Data de Emissão:</strong> <?php echo $data_emissao; ?></p>
            </div>

        <?php elseif (!empty($_POST)): ?>
            <div class="validation-result error-box">
                <p style="text-align: center;">Por favor, insira um código de validação para a pesquisa.</p>
            </div>

        <?php else: ?>
            <div class="validation-result">
                <p style="text-align: center; color: #666;">Use a caixa de pesquisa acima para verificar a autenticidade de qualquer certificado emitido pela ProjetoTech.</p>
            </div>

        <?php endif; ?>
    </div>
</body>

</html>