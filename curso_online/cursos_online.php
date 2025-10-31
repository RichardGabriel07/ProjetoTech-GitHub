<?php
// 1. SEMPRE começa verificando se está logado
session_start();
require_once '../php/conexao.php';

// Se não estiver logado, manda pra tela de login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../acessos/login.php");
    exit;
}
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
    <?php foreach ($cursos as $curso): ?>
        
        <div class="curso">
            <h3><?php echo $curso['nome_curso']; ?></h3>
            <p><?php echo $curso['descricao']; ?></p>
            <p><strong>⏱️ Duração:</strong> <?php echo $curso['duracao']; ?></p>
            
            <a href="matricular.php?id=<?php echo $curso['id_curso']; ?>" class="btn">
                ✅ Matricular Agora
            </a>
        </div>
        
    <?php endforeach; ?>
</div>


        <!-- Aqui vamos listar os cursos -->

    </main>
</body>

</html>