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
    $data = trim($_POST["data"]);
    $horario   = trim($_POST["hora"]);
    $local              = trim($_POST["endereco"]);
    $id_usuario = trim($_POST["id_usuario"]);
    $id_instrutor              = trim($_POST["instrutor"]);
    $id_curso              = trim($_POST["curso"]);



    // 2. VALIDAR OS DADOS (BUG CORRIGIDO: $noome -> $nome)
    if (empty($data) || empty($horario) || empty($local) || empty($id_usuario) || empty($id_instrutor) || empty($id_curso)) {
        $mensagem = "Por favor, preencha todos os campos obrigatórios.";
    }

    if ($id_agendamento && $data && $horario && $local && $id_usuario && $id_instrutor && $id_curso) {
        // 3. INSERIR NO BANCO DE DADOS
        try {
            // Nota: Os nomes das colunas na tabela (nome, area) devem ser usados no SQL.
            $sql = "INSERT INTO agendamento (data, horario, local, id_usuario, instrutor, curso) 
                    VALUES (:data, :horario, :local, :id_usuario, :id_instrutor, :id_curso)";
            $stmt = $pdo->prepare($sql);

            // Usando bindParam:
            $stmt->bindParam(':id_agendamento', $id_agendamento);
            $stmt->bindParam(':data', $data);
            $stmt->bindParam(':horario', $horario);
            $stmt->bindParam(':local', $local);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->bindParam(':id_instrutor', $id_instrutor);
            $stmt->bindParam(':id_curso', $id_curso);

            if ($stmt->execute()) {
                $mensagem = "Instrutor cadastrado com sucesso!";
                $sucesso = true;

                // CORREÇÃO PRG (Já existia, mas aqui está a confirmação)
                header("Location: /ProjetoTech/admin/listar_e_editar_agendamentos.php#box_formulario_cadastro");
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
    $data = $_POST["data"];
    $horario = $_POST["hora"];
    $local = $_POST["endereco"];
    $id_usuario = $_POST["id_usuario"];
    $id_instrutor = $_POST["instrutor"];
    $id_curso = $_POST["curso"];
    $id = $_POST['id_agendamento'];

    $sql = "UPDATE agendamento SET data=?, horario=?, local=?, id_instrutor=?, id_curso=? WHERE id_agendamento=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$data, $horario, $local, $id_instrutor, $id_curso, $id]);

    // CORREÇÃO PRG
    header("Location: /ProjetoTech/admin/listar_e_editar_agendamentos.php#box_formulario_cadastro");
    exit();
}

// =======================================================
// BLOCO 3: EXCLUIR INSTRUTOR (DELETE)
// =======================================================
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] ==
    'excluir'
) {
    $id = $_POST['id_agendamento'];
    $sql = "DELETE FROM usuario WHERE id_agendamento=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    // CORREÇÃO PRG
    header("Location: /ProjetoTech/admin/listar_e_editar_agendamentos.php#box_formulario_cadastro");
    exit();
}
// Buscar contatos
$sql = "SELECT * FROM agendamento";
$agendamentos = $pdo->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar e Editar Agendamentos - ProjetoTech</title>
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
            <h2>Cadastre e Edite<span> Agendamentos</span></h2>
        </div>

        <div id="right-side">
            <img src="../asstes/imagens/ChatGPT_Image_8_de_out._de_2025__22_58_56-removebg-preview.png" alt="Imagem de cadastro">
        </div>
    </section>

    <?php include 'includes/formluario_agendamento.php'; ?>

    <section id="box_formulario_cadastro">
        <h2>Listar e Editar Agendamentos:</h2>

        <table border="1">
            <tr>
                <th>ID</th>
                <th>Data</th>
                <th>Hora</th>
                <th>Endereço</th>
                <th>ID Usuário</th>
                <th>Nome Instrutor</th>
                <th>Funções</th>
            </tr>

            <?php foreach ($agendamentos as $agendamento): ?>
                <tr>
                    <td> <?php echo $agendamento['id_agendamento']; ?></td>
                    <td> <?php echo $agendamento['data']; ?></td>
                    <td> <?php echo $agendamento['horario']; ?></td>
                    <td> <?php echo $agendamento['local']; ?></td>
                    <td> <?php echo $agendamento['id_usuario']; ?></td>
                    <td> <?php echo $agendamento['id_instrutor']; ?></td>


                    <td>
                        <button
                            onclick="editContact(
                                    <?php echo $agendamento['id_agendamento']; ?>,          /* 1. ID do Agendamento */
                                    '<?php echo htmlspecialchars($agendamento['id_curso']); ?>',       /* 2. ID do Curso */
                                    '<?php echo htmlspecialchars($agendamento['id_instrutor']); ?>',   /* 3. ID do Instrutor */
                                    '<?php echo htmlspecialchars($agendamento['data']); ?>',           /* 4. Data (formato yyyy-MM-dd) */
                                    '<?php echo htmlspecialchars($agendamento['horario']); ?>',        /* 5. Horário (formato HH:mm:ss) */
                                    '<?php echo htmlspecialchars($agendamento['local']); ?>'           /* 6. Endereço/Local (String) */
                                )"
                            style="background-color: #007bff; color: white; border: none; padding: 5px 10px; cursor: pointer;">Editar</button>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir o agendamento <?php echo $agendamento['id_agendamento']; ?>?');">

                            <input type="hidden" name="id_agendamento" value="<?php echo $agendamento['id_agendamento']; ?>">

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
        function editContact(id_agendamento, id_curso, id_instrutor, data, horario, local) {

            // 1. Campos Ocultos para a Ação
            // Se 'id_agendamento' ou 'action' for o erro "null", este corrige.
            document.getElementById('id_agendamento').value = id_agendamento;
            document.getElementById('action').value = 'atualizar';

            // 2. Selects (IDs de curso e instrutor)
            document.getElementById('curso').value = id_curso;
            document.getElementById('instrutor').value = id_instrutor;

            // 3. Campos de Data e Hora (Corrigindo os erros de formato das linhas 159 e 160)
            document.getElementById('data').value = data; // Recebe yyyy-MM-dd
            document.getElementById('hora').value = horario; // Recebe HH:mm:ss

            // 4. Campo de Endereço/Local (Corrigindo o erro de Null da linha 161)
            // O ID correto é 'endereco' (conforme seu formluario_agendamento.php)
            document.getElementById('endereco').value = local;

            // 5. Botão de Submit (Se 'btn_submit' for o erro "null", este corrige)
            document.getElementById('btn_submit').textContent = 'Salvar Alterações';

            window.scrollTo(0, 0);
        }
    </script>
    </script>
</body>

</html>