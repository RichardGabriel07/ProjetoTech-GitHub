<?php
// 1. SEMPRE começa verificando se está logado
session_start();
require_once '../php/conexao.php';

// Se não estiver logado, manda pra tela de login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../acessos/login.php");
    exit;
}

// 🎯 CORREÇÃO CRÍTICA: DEFINIR A VARIÁVEL LOCAL COM O ID DA SESSÃO
$id_usuario = $_SESSION['id_usuario'];
// Agora sim, $id_usuario terá um valor válido (ex: 1, 4, 11, etc.)

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos Online - ProjetoTech</title>
    <link rel="icon" href="../../ProjetoTech-GitHub/assets/imagens/Generated Image November 02, 2025 - 12_39AM.png" type="image/png">

    <!-- CSS da navbar e estilo base -->
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/area_cliente.css">
    <style>
        :root {
    --primary-color: #122A3F;
    --secondary-color: #00b4d8;
    --accent-color: #0096c7;
    --text-dark: #333;
    --text-light: #666;
    --bg-light: #f8f9fa;
    --white: #ffffff;
    --border-light: rgba(0, 180, 216, 0.1);
    --shadow-light: 0 4px 20px rgba(0, 0, 0, 0.08);
    --shadow-hover: 0 12px 40px rgba(0, 180, 216, 0.15);
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

        /* Hero Section - Contato */
        #entre-em-contato {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e3a5f 100%);
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
    <!-- Navbar do site -->
    <?php include '../acessos/navbar_publico.php'; ?>

    <section id="entre-em-contato">
        <div id="duvidas">
            <h2>Cursos Online Disponíveis</h2>

            <p>Explore nossos cursos online e expanda seus conhecimentos. <br> Escolha o curso ideal para sua trajetória profissional!</p>
        </div>
    </section>

    <main>
        <?php
        // 2. Mostrar a query que vai executar
        $sql = "SELECT * FROM curso WHERE tipo_curso = 'Online' ORDER BY id_curso DESC";
        // 3. EXECUTAR a busca
        try {
            $resultado = $pdo->query($sql);

            // 4. PEGAR todos os resultados
            $cursos = $resultado->fetchAll();
        } catch (PDOException $e) {
            echo "<p style='color: red;'>❌ Erro ao buscar cursos: " . $e->getMessage() . "</p>";
        }

        // VERIFICAR: Tem cursos?
        if (count($cursos) == 0) {
            echo "<div style='background: #f8d7da; padding: 15px; margin: 20px; border-radius: 8px; color: #721c24;'>";
            echo "<h3>⚠️ Nenhum curso online disponível</h3>";
            echo "<p><strong>Possíveis causas:</strong></p>";
            echo "<ol>";
            echo "<li>O SQL ainda não foi importado no banco de dados</li>";
            echo "<li>Não existem cursos com tipo_curso = 'Online'</li>";
            echo "</ol>";
            echo "<p><strong>Solução:</strong> Importe o arquivo <code>mysql/projeto_tech_para_modificar_windsurf.sql</code> no phpMyAdmin</p>";
            echo "</div>";
            exit;
        }
        ?>


        <div class="cursos_disponiveis">
            <?php foreach ($cursos as $curso):
                // 2. Definir o ID do curso atual
                $id_curso_atual = $curso['id_curso'];

                // 3. LÓGICA DE VERIFICAÇÃO DE MATRÍCULA
                // Consulta o banco para ver se a matrícula existe
                $sql_check_matricula = "
            SELECT COUNT(id_matricula) 
            FROM matriculas_online 
            WHERE id_usuario = :id_usuario AND id_curso = :id_curso AND status = 'ativa'
        "; // Adicionei 'status = 'ativa'' para garantir que matrículas canceladas não contem.

                $stmt_check = $pdo->prepare($sql_check_matricula);
                $stmt_check->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT); // ❌ $id_usuario está vazio ou não definida!
                $stmt_check->bindParam(':id_curso', $id_curso_atual, PDO::PARAM_INT);
                $stmt_check->execute();

                $matriculado = $stmt_check->fetchColumn();

                // 4. Configurar o link e o texto do botão
                if ($matriculado > 0) {
                    // Se o usuário está matriculado (e com status 'ativa')
                    $link_destino = "meu_curso.php?id=" . $id_curso_atual;
                    $texto_botao = "▶️ Acessar Meu Curso";
                    $classe_css = "btn btn-acessar";
                } else {
                    // Se o usuário NÃO está matriculado
                    $link_destino = "matricular.php?id=" . $id_curso_atual;
                    $texto_botao = "✅ Matricular Agora";
                    $classe_css = "btn btn-matricular";
                }

                // 5. EXIBIÇÃO
            ?>
                <div class="curso">
                    <h3><?php echo $curso['nome_curso']; ?></h3>
                    <p><?php echo $curso['descricao']; ?></p>
                    <p><strong>⏱️ Duração:</strong> <?php echo $curso['duracao']; ?></p>

                    <a href="<?php echo $link_destino; ?>" class="<?php echo $classe_css; ?>">
                        <?php echo $texto_botao; ?>
                    </a>
                </div>

            <?php endforeach; ?>
        </div>

        <div class="btn-voltar">
            <a href="../clientes/area_cliente.php" class="btn">
                ← Voltar para Área do Cliente
            </a>
        </div>

    </main>
</body>

</html>