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
    $nome           = trim($_POST["nome"]);
    $cpf   = trim($_POST["cpf"]);
    $email              = trim($_POST["email"]);
    $senha              = trim($_POST["senha"]);


    // 2. VALIDAR OS DADOS (BUG CORRIGIDO: $noome -> $nome)
    if (empty($nome) || empty($cpf) || empty($email) || empty($senha)) {
        $mensagem = "Por favor, preencha todos os campos obrigatórios.";
    }

    if ($nome && $cpf && $email && $senha) {
        // 3. INSERIR NO BANCO DE DADOS
        try {
            // Nota: Os nomes das colunas na tabela (nome, area) devem ser usados no SQL.
            $sql = "INSERT INTO usuario (nome, cpf, email, senha) 
        VALUES (:nome, :cpf, :email, :senha)";
            $stmt = $pdo->prepare($sql);

            // Usando bindParam:
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', password_hash($senha, PASSWORD_DEFAULT)); // Hash da senha
            if ($stmt->execute()) {
                $mensagem = "Instrutor cadastrado com sucesso!";
                $sucesso = true;

                // CORREÇÃO PRG (Já existia, mas aqui está a confirmação)
                header("Location: /ProjetoTech/admin/listar_e_editar_usuarios.php#box_formulario_cadastro");
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
    $nome = $_POST["nome"];
    $cpf = $_POST["cpf"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    $id = $_POST['id_usuario'];

    $sql = "UPDATE usuario SET nome=?, cpf=?, email=?, senha=? WHERE id_usuario=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $cpf, $email, password_hash($senha, PASSWORD_DEFAULT), $id]);

    // CORREÇÃO PRG
    header("Location: /ProjetoTech/admin/listar_e_editar_usuarios.php#box_formulario_cadastro");
    exit();
}

// =======================================================
// BLOCO 3: EXCLUIR INSTRUTOR (DELETE)
// =======================================================
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] ==
    'excluir'
) {
    $id = $_POST['id_usuario'];
    $sql = "DELETE FROM usuario WHERE id_usuario=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    // CORREÇÃO PRG
    header("Location: /ProjetoTech/admin/listar_e_editar_usuarios.php#box_formulario_cadastro");
    exit();
}
// Buscar contatos
$sql = "SELECT * FROM usuario";
$usuarios = $pdo->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar e Editar Usuarios - ProjetoTech</title>
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
            <h2>Cadastre e Edite<span> Usuarios</span></h2>
        </div>

        <div id="right-side">
            <img src="../assets/imagens/ChatGPT_Image_8_de_out._de_2025__22_58_56-removebg-preview.png" alt="Imagem de cadastro">
        </div>
    </section>

    <?php include 'includes/formulario_usuario.php'; ?>

    <section id="box_formulario_cadastro">
        <h2>Listar e Editar Usuarios:</h2>

        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nome do Usuario</th>
                <th>E-mail</th>
                <th>Senha</th>
                <th>Cpf</th>
                <th>Funções</th>
            </tr>

            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td> <?php echo $usuario['id_usuario']; ?></td>
                    <td> <?php echo $usuario['nome']; ?></td>
                    <td> <?php echo $usuario['email']; ?></td>
                    <td> <?php echo $usuario['senha']; ?></td>
                    <td> <?php echo $usuario['cpf']; ?></td>
                    <td><button onclick="editContact('<?php echo $usuario['id_usuario']; ?>', '<?php echo $usuario['nome']; ?>','<?php echo $usuario['cpf']; ?>','<?php echo $usuario['email']; ?>', '<?php echo $usuario['senha']; ?>')">Editar</button>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir o usuario <?php echo $usuario['nome']; ?>?');">

                            <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">

                            <input type="hidden" name="action" value="excluir">

                            <button type="submit" style="background-color: #dc3545; color: white; border: none; padding: 5px 10px; cursor: pointer;">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
    
        <script src="../js/script.js"></script>

    <script>
        // Localizada no final do seu arquivo de listagem, dentro da tag <script>
        function editContact(id, nome, cpf, email, senha) {
            // 1. Preenche o ID e a Ação Ocultos (informa o PHP que é uma atualização)
            document.getElementById('id_usuario').value = id;
            document.getElementById('action').value = 'atualizar';

            // 2. Preenche os campos visíveis do formulário
            document.getElementById('nome').value = nome;
            document.getElementById('cpf').value = cpf;

            // 3. Preenche o Select (o <select> deve ser preenchido pelo value)
            document.getElementById('email').value = email;
            document.getElementById('senha').value = senha;


            // 4. Altera o texto do botão para ser intuitivo para o usuário
            document.getElementById('btn_submit').textContent = 'Salvar Alterações';

            // Opcional: Rolagem para o topo para mostrar o formulário preenchido
            window.scrollTo(0, 0);
        }
    </script>
</body>

</html>