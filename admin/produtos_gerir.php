<?php
session_start();
require_once '../config/database.php';
require_once 'includes/auth_check.php';

$page_title = 'Adicionar Produto';
$produto = [
    'id' => null,
    'nome' => '',
    'descricao' => '',
    'quantidade' => 0,
    'preco' => '',
    'imagem' => ''
];
$erros = [];

// Checks if it's in edit mode (if an ID was passed in the URL)
if (isset($_GET['id'])) {
    $page_title = 'Editar Produto';
    $id_produto = (int)$_GET['id'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
        $stmt->execute([$id_produto]);
        $produto = $stmt->fetch();

        if (!$produto) {
            header('Location: dashboard.php');
            exit();
        }
    } catch (PDOException $e) {
        die("Erro ao ir buscar o produto: " . $e->getMessage());
    }
}

// Processes the form when it is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produto = $_POST['id'] ? (int)$_POST['id'] : null;
    $nome = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING));
    $descricao = trim(filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING));
    $quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT);
    $preco = filter_input(INPUT_POST, 'preco', FILTER_VALIDATE_FLOAT);

    // Validation
    if (empty($nome)) $erros[] = 'O nome do produto é obrigatório.';
    if ($quantidade === false || $quantidade < 0) $erros[] = 'A quantidade deve ser um número inteiro válido.';
    if ($preco === false || $preco < 0) $erros[] = 'O preço deve ser um número válido.';

    // Image upload logic
    $imagem_nome = $_POST['imagem_atual'];
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/images/';
        $imagem_nome = uniqid() . '-' . basename($_FILES['imagem']['name']);
        $upload_file = $upload_dir . $imagem_nome;

        // Validate the file type and size
        $check = getimagesize($_FILES['imagem']['tmp_name']);
        if ($check !== false) {
            if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $upload_file)) {
                $erros[] = 'Ocorreu um erro ao guardar a imagem.';
            }
        } else {
            $erros[] = 'O ficheiro enviado não é uma imagem válida.';
        }
    }

    if (empty($erros)) {
        try {
            if ($id_produto) {
                // UPDATE
                $sql = "UPDATE produtos SET nome = ?, descricao = ?, quantidade = ?, preco = ?, imagem = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nome, $descricao, $quantidade, $preco, $imagem_nome, $id_produto]);
            } else {
                // INSERT
                $sql = "INSERT INTO produtos (nome, descricao, quantidade, preco, imagem) VALUES (?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nome, $descricao, $quantidade, $preco, $imagem_nome]);
            }
            header('Location: dashboard.php');
            exit();
        } catch (PDOException $e) {
            $erros[] = "Erro na base de dados: " . $e->getMessage();
        }
    }
    $produto = $_POST;
    $produto['imagem'] = $_POST['imagem_atual'];
}

require_once 'templates/header.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $page_title; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
    </ol>

    <div class="card mb-4">
        <div class="card-body">
            <?php if (!empty($erros)): ?>
                <div class="alert alert-danger">
                    <strong>Por favor, corrija os seguintes erros:</strong>
                    <ul>
                        <?php foreach ($erros as $erro): ?>
                            <li><?php echo $erro; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="produtos_gerir.php<?php echo $produto['id'] ? '?id='.$produto['id'] : ''; ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
                <input type="hidden" name="imagem_atual" value="<?php echo htmlspecialchars($produto['imagem']); ?>">

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome do Produto</label>
                    <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="3"><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="quantidade" class="form-label">Quantidade em Stock</label>
                        <input type="number" class="form-control" id="quantidade" name="quantidade" value="<?php echo htmlspecialchars($produto['quantidade']); ?>" required min="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="preco" class="form-label">Preço (€)</label>
                        <input type="text" class="form-control" id="preco" name="preco" value="<?php echo htmlspecialchars($produto['preco']); ?>" required placeholder="Ex: 9.99">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="imagem" class="form-label">Imagem do Produto</label>
                    <input class="form-control" type="file" id="imagem" name="imagem">
                    <?php if ($produto['imagem']): ?>
                        <div class="mt-2">
                            <small>Imagem atual:</small>
                            <img src="../assets/images/<?php echo htmlspecialchars($produto['imagem']); ?>" alt="Imagem atual" style="max-height: 80px; border-radius: 5px;">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Guardar Produto</button>
                    <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once 'templates/footer.php';
?>
