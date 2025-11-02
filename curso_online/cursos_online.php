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

    <!-- CSS da navbar e estilo base -->
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/area_cliente.css">
    <style>
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
    <!-- Navbar do site -->
    <?php include '../acessos/navbar_publico.php'; ?>

    <main>
        <h1>📚 Cursos Online Disponíveis</h1>

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