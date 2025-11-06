<?php
include '../php/conexao.php';
include '../acessos/verificar_admin.php';

$mensagem = "";
$sucesso = false;

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/* =======================================================
   BLOCO 1: ADICIONAR AGENDAMENTO (CREATE)
   ======================================================= */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] == 'incluir') {

    $data       = isset($_POST["data"]) ? $_POST["data"] : null;
    $horario    = isset($_POST["hora"]) ? $_POST["hora"] : null;
    $local      = isset($_POST["endereco"]) ? $_POST["endereco"] : null;
    $id_usuario = isset($_POST["id_usuario"]) ? $_POST["id_usuario"] : null;

    if (!$data || !$horario || !$local || !$id_usuario) {
        echo "<script>alert('Preencha todos os campos!');</script>";
        exit;
    }

    try {
        $sql = "INSERT INTO agendamento (data, horario, local, id_usuario)
                VALUES (:data, :horario, :local, :id_usuario)";
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':data'       => $data,
            ':horario'    => $horario,
            ':local'      => $local,
            ':id_usuario' => $id_usuario
        ]);

        echo "<script>
                alert('Agendamento cadastrado com sucesso!');
                window.location.href = 'listar_e_editar_agendamentos.php';
              </script>";
        exit;

    } catch (PDOException $e) {
        echo "<script>alert('ERRO SQL: " . $e->getMessage() . "');</script>";
        exit;
    }
}

/* =======================================================
   BLOCO 2: ATUALIZAR AGENDAMENTO (UPDATE)
   ======================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] == 'atualizar') {

    $data       = isset($_POST["data"]) ? $_POST["data"] : null;
    $horario    = isset($_POST["hora"]) ? $_POST["hora"] : null;
    $local      = isset($_POST["endereco"]) ? $_POST["endereco"] : null;
    $id_usuario = isset($_POST["id_usuario"]) ? $_POST["id_usuario"] : null;
    $id         = isset($_POST['id_agendamento']) ? $_POST['id_agendamento'] : null;

    $sql = "UPDATE agendamento 
            SET data=?, horario=?, local=?, id_usuario=? 
            WHERE id_agendamento=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$data, $horario, $local, $id_usuario, $id]);

    echo "<script>
            alert('Agendamento atualizado com sucesso!');
            window.location.href = 'listar_e_editar_agendamentos.php';
          </script>";
    exit;
}

/* =======================================================
   BLOCO 3: EXCLUIR AGENDAMENTO (DELETE)
   ======================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] == 'excluir') {

    $id = isset($_POST['id_agendamento']) ? $_POST['id_agendamento'] : null;

    $sql = "DELETE FROM agendamento WHERE id_agendamento=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    echo "<script>
            alert('Agendamento excluído com sucesso!');
            window.location.href = 'listar_e_editar_agendamentos.php';
          </script>";
    exit;
}

/* =======================================================
   BUSCAR LISTA
   ======================================================= */
$sql = "SELECT id_agendamento, data, horario, local, id_usuario FROM agendamento";
$agendamentos = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
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

<?php include '../admin/includes/formulario_agendamento.php'; ?>

<section id="box_formulario_cadastro">
    <h2>Listar e Editar Agendamentos:</h2>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Data</th>
            <th>Hora</th>
            <th>Endereço</th>
            <th>ID Usuário</th>
            <th>Funções</th>
        </tr>

        <?php foreach ($agendamentos as $agendamento): ?>
        <tr>
            <td><?= $agendamento['id_agendamento'] ?></td>
            <td><?= $agendamento['data'] ?></td>
            <td><?= $agendamento['horario'] ?></td>
            <td><?= $agendamento['local'] ?></td>
            <td><?= $agendamento['id_usuario'] ?></td>

            <td>

                <!-- BOTÃO EDITAR -->
                <button
                    onclick="editContact(
                        '<?= $agendamento['id_agendamento'] ?>',
                        '<?= $agendamento['id_usuario'] ?>',
                        '<?= $agendamento['data'] ?>',
                        '<?= $agendamento['horario'] ?>',
                        '<?= $agendamento['local'] ?>'
                    )"
                    style="background-color: #007bff; color: white; padding:5px 10px;">
                    Editar
                </button>

                <!-- BOTÃO EXCLUIR -->
                <form method="POST" style="display:inline;" 
                      onsubmit="return confirm('❌ Tem certeza que deseja excluir o agendamento <?= $agendamento['id_agendamento'] ?> ?');">

                    <input type="hidden" name="id_agendamento" value="<?= $agendamento['id_agendamento'] ?>">
                    <input type="hidden" name="action" value="excluir">

                    <button type="submit" 
                        style="background-color:#dc3545;color:white;padding:5px 10px;">
                        Excluir
                    </button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</section>

<script>
function editContact(id, id_usuario, data, horario, local) {

    if (!confirm("⚠️ Você realmente deseja EDITAR este agendamento?")) {
        return;
    }

    document.getElementById('id_agendamento').value = id;
    document.getElementById('id_usuario').value = id_usuario;
    document.getElementById('data').value = data;
    document.getElementById('hora').value = horario;
    document.getElementById('endereco').value = local;
    document.getElementById('action').value = 'atualizar';

    document.getElementById('btn_submit').textContent = 'Salvar Alterações';

    window.scrollTo(0, 0);
}
</script>

</body>
</html>
