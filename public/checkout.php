<?php
session_start();
require_once '../config/database.php';

if (empty($_SESSION['carrinho'])) {
    header('Location: carrinho.php');
    exit();
}

$erros = [];
$nome_cliente = '';
$data_nascimento = '';
$morada = '';

// Process the form when it is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Validate form data
    $nome_cliente = trim(filter_input(INPUT_POST, 'nome_cliente', FILTER_SANITIZE_STRING));
    $data_nascimento = trim($_POST['data_nascimento']);
    $morada = trim(filter_input(INPUT_POST, 'morada', FILTER_SANITIZE_STRING));

    // Validation of empty fields
    if (empty($nome_cliente)) {
        $erros[] = 'O campo "Nome" é de preenchimento obrigatório.';
    }
    if (empty($data_nascimento)) {
        $erros[] = 'O campo "Data de Nascimento" é de preenchimento obrigatório.';
    }
    if (empty($morada)) {
        $erros[] = 'O campo "Morada" é de preenchimento obrigatório.';
    }

    // Age validation (18 years or older)
    if (!empty($data_nascimento)) {
        try {
            $data_nasc_obj = new DateTime($data_nascimento);
            $hoje = new DateTime();
            $idade = $hoje->diff($data_nasc_obj)->y;
            if ($idade < 18) {
                $erros[] = 'Tem de ter pelo menos 18 anos para fazer uma compra.';
            }
        } catch (Exception $e) {
            $erros[] = 'A data de nascimento fornecida não é válida.';
        }
    }

    // 2. If there are no validation errors, process the order
    if (empty($erros)) {
        try {
            $pdo->beginTransaction();

            // Stock verification before finalizing
            foreach ($_SESSION['carrinho'] as $id_produto => $item) {
                $stmt = $pdo->prepare("SELECT quantidade FROM produtos WHERE id = ?");
                $stmt->execute([$id_produto]);
                $stock_atual = $stmt->fetchColumn();
                if ($item['quantidade'] > $stock_atual) {
                    throw new Exception("O produto '{$item['nome']}' já não tem stock suficiente. Por favor, ajuste a sua encomenda.");
                }
            }

            // Calculate the final total price from the cart
            $preco_total = 0;
            foreach ($_SESSION['carrinho'] as $item) {
                $preco_total += $item['preco'] * $item['quantidade'];
            }

            // Insert into the `encomendas` table
            $stmt = $pdo->prepare("INSERT INTO encomendas (nome_cliente, data_nascimento, morada, preco_total) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nome_cliente, $data_nascimento, $morada, $preco_total]);
            $id_encomenda = $pdo->lastInsertId();

            // Insert into the `detalhes_encomenda` table and update stock
            $stmt_detalhes = $pdo->prepare("INSERT INTO detalhes_encomenda (id_encomenda, id_produto, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
            $stmt_update_stock = $pdo->prepare("UPDATE produtos SET quantidade = quantidade - ? WHERE id = ?");

            foreach ($_SESSION['carrinho'] as $id_produto => $item) {
                // Insert order detail
                $stmt_detalhes->execute([$id_encomenda, $id_produto, $item['quantidade'], $item['preco']]);
                // Update product stock
                $stmt_update_stock->execute([$item['quantidade'], $id_produto]);
            }

            $pdo->commit();

            // Clear the cart and display success message
            unset($_SESSION['carrinho']);
            $_SESSION['sucesso'] = "Encomenda realizada com sucesso! Obrigado pela sua preferência.";
            header('Location: sucesso.php');
            exit();

        } catch (Exception $e) {
            $pdo->rollBack();
            $erros[] = "Ocorreu um erro ao processar a sua encomenda: " . $e->getMessage();
        }
    }
}

require_once '../templates/header.php';
?>

<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <h1 class="mb-4">Finalizar Compra</h1>

            <?php if (!empty($erros)): ?>
                <div class="alert alert-danger">
                    <p class="mb-0"><strong>Por favor, corrija os seguintes erros:</strong></p>
                    <ul class="mb-0">
                        <?php foreach ($erros as $erro): ?>
                            <li><?php echo $erro; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">Os seus dados para a entrega</h5>
                    <form action="checkout.php" method="POST">
                        <div class="mb-3">
                            <label for="nome_cliente" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" id="nome_cliente" name="nome_cliente" value="<?php echo htmlspecialchars($nome_cliente); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                            <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($data_nascimento); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="morada" class="form-label">Morada Completa</label>
                            <textarea class="form-control" id="morada" name="morada" rows="3" required><?php echo htmlspecialchars($morada); ?></textarea>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">Concluir Transação</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-center mt-3">
                <a href="carrinho.php">&laquo; Voltar ao carrinho</a>
            </div>
        </div>
    </div>
</main>

<?php
require_once '../templates/footer.php';
?>
