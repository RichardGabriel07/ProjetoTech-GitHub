<?php
include '../php/conexao.php';
include '../acessos/verificar_admin.php';


// Inicialização de variáveis de mensagem
$mensagem = "";
$sucesso = false;


// =======================================================
// BLOCO 1: ADICIONAR INSTRUTOR (CREATE)
// Inclusão da checagem 'action' para evitar conflitos
// =======================================================
if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST['action']) && $_POST['action'] == 'incluir'
) {
    // 1. RECEBER OS DADOS
    $nome_curso              = trim($_POST["nome_curso"]);
    $duracao   = trim($_POST["duracao"]);
    $descricao              = trim($_POST["descricao"]);

    // 2. VALIDAR OS DADOS (BUG CORRIGIDO: $noome -> $nome)
    if (empty($nome_curso) || empty($duracao) || empty($descricao)) {
        $mensagem = "Por favor, preencha todos os campos obrigatórios.";
    }

    if ($nome_curso && $duracao && $descricao) {
        // 3. INSERIR NO BANCO DE DADOS
        try {
            // Nota: Os nomes das colunas na tabela (nome, area) devem ser usados no SQL.
            $sql = "INSERT INTO curso (nome_curso, duracao, descricao) 
                    VALUES (:nome_curso, :duracao, :descricao)";
            $stmt = $pdo->prepare($sql);

            // Usando bindParam:
            $stmt->bindParam(':nome_curso', $nome_curso);
            $stmt->bindParam(':duracao', $duracao);
            $stmt->bindParam(':descricao', $descricao);

            if ($stmt->execute()) {
                $mensagem = "Instrutor cadastrado com sucesso!";
                $sucesso = true;

                // CORREÇÃO PRG (Já existia, mas aqui está a confirmação)
                header("Location: listar_e_editar_cursos.php#box_formulario_cadastro");
                exit();
            } else {
                $mensagem = "Erro ao cadastrar: verifique os dados.";
            }
        } catch (PDOException $e) {
            $mensagem = "Erro no cadastro: " . $e->getMessage();
        }
    } else {
        $mensagem = "Preencha todos os campos obrigatórios.";
    }
}

// =======================================================
// BLOCO 2: ATUALIZAR INSTRUTOR (UPDATE)
// =======================================================
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] ==
    'atualizar'
) {
    $nome_curso= $_POST["nome_curso"];
    $duracao= $_POST["duracao"];
    $descricao= $_POST["descricao"];
    $id = $_POST['id_curso'];

    $sql = "UPDATE curso SET nome_curso=?, descricao=?, duracao=? WHERE id_curso=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome_curso, $descricao, $duracao, $id]);

    // CORREÇÃO PRG
    header("Location: listar_e_editar_cursos.php#box_formulario_cadastro");
    exit();
}

// =======================================================
// BLOCO 3: EXCLUIR INSTRUTOR (DELETE)
// =======================================================
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] ==
    'excluir'
) {
    $id = $_POST['id_curso'];
    $sql = "DELETE FROM curso WHERE id_curso=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    // CORREÇÃO PRG
    header("Location: listar_e_editar_cursos.php#box_formulario_cadastro");
    exit();
}
// Buscar contatos
$sql = "SELECT * FROM curso";
$cursos = $pdo->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar e Editar Cursos - ProjetoTech</title>
    <link rel="stylesheet" href="../css/listar_e_editar.css">
</head>

<body>
    <header>
        <div id="navbar">
            <h1>Projeto <span>Tech</span></h1>

            <nav id="navbar-li">
                <ul>
                    <li><a href="../admin/area_administrativa.php">Área admin</a></li>
                    <li id="wilma"><a href="../acessos/logout.php" id="sair">Sair</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section id="criar_conta">
        <div id="gratis">
            <h2>Cadastre e Edite<span> Cursos</span></h2>
        </div>

        <div id="right-side">
            <img src="../asstes/imagens/ChatGPT_Image_8_de_out._de_2025__22_58_56-removebg-preview.png" alt="Imagem de cadastro">
        </div>
    </section>

    <?php include 'includes/formulario_curso.php'; ?>

    <section id="box_formulario_cadastro">
        <h2>Listar e Editar Cursos:</h2>

        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nome do Curso</th>
                <th>Descrição do Curso</th>
                <th>Duração</th>
                <th>Funções</th>
            </tr>

            <?php foreach ($cursos as $curso): ?>
                <tr>
                    <td> <?php echo $curso['id_curso']; ?></td>
                    <td> <?php echo $curso['nome_curso']; ?></td>
                    <td> <?php echo $curso['descricao']; ?></td>
                    <td> <?php echo $curso['duracao']; ?></td>
                    <td><button onclick="editContact('<?php echo $curso['id_curso']; ?>', '<?php echo $curso['nome_curso']; ?>', '<?php echo $curso['duracao']; ?>', '<?php echo $curso['descricao']; ?>')">Editar</button>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir o curso <?php echo $curso['nome_curso']; ?>?');">

                            <input type="hidden" name="id_curso" value="<?php echo $curso['id_curso']; ?>">

                            <input type="hidden" name="action" value="excluir">

                            <button type="submit" style="background-color: #dc3545; color: white; border: none; padding: 5px 10px; cursor: pointer;">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <script>
        // Localizada no final do seu arquivo de listagem, dentro da tag <script>
        function editContact(id, nome_curso, duracao, descricao) {
            // 1. Preenche o ID e a Ação Ocultos (informa o PHP que é uma atualização)
            document.getElementById('id_curso').value = id;
            document.getElementById('action').value = 'atualizar';

            // 2. Preenche os campos visíveis do formulário
            document.getElementById('nome_curso').value = nome_curso;
            document.getElementById('duracao').value = duracao;

            // 3. Preenche o Select (o <select> deve ser preenchido pelo value)
            document.getElementById('descricao').value = descricao;

            // 4. Altera o texto do botão para ser intuitivo para o usuário
            document.getElementById('btn_submit').textContent = 'Salvar Alterações';

            // Opcional: Rolagem para o topo para mostrar o formulário preenchido
            window.scrollTo(0, 0);
        }
    </script>
</body>

</html>