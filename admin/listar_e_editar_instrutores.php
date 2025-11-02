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
    $nome              = trim($_POST["nome_instrutor"]);
    $area_de_atuacao   = trim($_POST["area_de_atuacao"]);
    $sexo              = trim($_POST["sexo"]);

    // 2. VALIDAR OS DADOS (BUG CORRIGIDO: $noome -> $nome)
    if (empty($nome) || empty($area_de_atuacao) || empty($sexo)) {
        $mensagem = "Por favor, preencha todos os campos obrigatórios.";
    }

    if ($nome && $area_de_atuacao && $sexo) {
        // 3. INSERIR NO BANCO DE DADOS
        try {
            // Nota: Os nomes das colunas na tabela (nome, area) devem ser usados no SQL.
            $sql = "INSERT INTO instrutor (nome, area, sexo) 
                    VALUES (:nome, :area, :sexo)";
            $stmt = $pdo->prepare($sql);

            // Usando bindParam:
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':area', $area_de_atuacao); // Bind usando a variável do formulário
            $stmt->bindParam(':sexo', $sexo);

            if ($stmt->execute()) {
                $mensagem = "Instrutor cadastrado com sucesso!";
                $sucesso = true;

                // CORREÇÃO PRG (Já existia, mas aqui está a confirmação)
                header("Location: listar_e_editar_instrutores.php#box_formulario_cadastro");
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
    $id = $_POST['id_instrutor'];

    // CORREÇÃO: Usando os nomes corretos do formulário
    $nome = $_POST['nome_instrutor'];
    $area = $_POST['area_de_atuacao'];

    $sexo = $_POST['sexo']; // Este já estava correto, pois o nome do campo é 'sexo'

    $sql = "UPDATE instrutor SET nome=?, area=?, sexo=? WHERE id_instrutor=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $area, $sexo, $id]);

    // CORREÇÃO PRG
    header("Location: listar_e_editar_instrutores.php#box_formulario_cadastro");
    exit();
}

// =======================================================
// BLOCO 3: EXCLUIR INSTRUTOR (DELETE)
// =======================================================
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] ==
    'excluir'
) {
    $id = $_POST['id_instrutor'];
    $sql = "DELETE FROM instrutor WHERE id_instrutor=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    // CORREÇÃO PRG
    header("Location: listar_e_editar_instrutores.php#box_formulario_cadastro");
    exit();
}
// Buscar contatos
$sql = "SELECT * FROM instrutor";
$instrutor = $pdo->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar e Editar Instrutores - ProjetoTech</title>
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
            <h2>Cadastre e Edite<span> Instrutores</span></h2>
        </div>

        <div id="right-side">
            <img src="../assets/imagens/ChatGPT_Image_8_de_out._de_2025__22_58_56-removebg-preview.png" alt="Imagem de cadastro">
        </div>
    </section>

    <?php include 'includes/formulario_instrutor.php'; ?>

    <section id="box_formulario_cadastro">
        <h2>Listar e Editar Instrutores:</h2>

        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Área de atuação</th>
                <th>Sexo</th>
                <th>Funções</th>
            </tr>

            <?php foreach ($instrutor as $instrutores): ?>
                <tr>
                    <td> <?php echo $instrutores['id_instrutor']; ?></td>
                    <td> <?php echo $instrutores['nome']; ?></td>
                    <td> <?php echo $instrutores['area']; ?></td>
                    <td> <?php echo $instrutores['sexo']; ?></td>
                    <td><button onclick="editContact('<?php echo $instrutores['id_instrutor']; ?>', '<?php echo $instrutores['nome']; ?>', '<?php echo $instrutores['area']; ?>', '<?php echo $instrutores['sexo']; ?>')">Editar</button>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir o instrutor <?php echo $instrutores['nome']; ?>?');">

                            <input type="hidden" name="id_instrutor" value="<?php echo $instrutores['id_instrutor']; ?>">

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
        function editContact(id, nome, area, sexo) {
            // 1. Preenche o ID e a Ação Ocultos (informa o PHP que é uma atualização)
            document.getElementById('id_instrutor').value = id;
            document.getElementById('action').value = 'atualizar';

            // 2. Preenche os campos visíveis do formulário
            document.getElementById('nome_instrutor').value = nome;
            document.getElementById('area_de_atuacao').value = area;

            // 3. Preenche o Select (o <select> deve ser preenchido pelo value)
            document.getElementById('sexo').value = sexo;

            // 4. Altera o texto do botão para ser intuitivo para o usuário
            document.getElementById('btn_submit').textContent = 'Salvar Alterações';

            // Opcional: Rolagem para o topo para mostrar o formulário preenchido
            window.scrollTo(0, 0);
        }
    </script>
</body>

</html>