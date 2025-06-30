<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// LOGIC TO ADD/UPDATE/REMOVE ITEMS FROM THE CART (PROCESS POST REQUESTS)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Add a product to the cart
    if (isset($_POST['id_produto'], $_POST['quantidade'])) {
        $id_produto = (int)$_POST['id_produto'];
        $quantidade_a_comprar = (int)$_POST['quantidade'];

        if ($quantidade_a_comprar > 0) {
            try {
                // Fetch the product from the database to check stock and get details
                $stmt = $pdo->prepare("SELECT nome, preco, quantidade FROM produtos WHERE id = ?");
                $stmt->execute([$id_produto]);
                $produto = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($produto && $quantidade_a_comprar <= $produto['quantidade']) {
                    // If the product is already in the cart, update the quantity
                    if (isset($_SESSION['carrinho'][$id_produto])) {
                        $_SESSION['carrinho'][$id_produto]['quantidade'] += $quantidade_a_comprar;
                    } else {
                        // Otherwise, add the product to the cart
                        $_SESSION['carrinho'][$id_produto] = [
                            'nome' => $produto['nome'],
                            'preco' => $produto['preco'],
                            'quantidade' => $quantidade_a_comprar
                        ];
                    }
                }
            } catch (PDOException $e) {
                die("Erro ao processar o produto: " . $e->getMessage());
            }
        }

        header('Location: index.php');
        exit();
    }

    // Remove a product from the cart
    if (isset($_POST['remover_id'])) {
        $id_produto_remover = (int)$_POST['remover_id'];
        unset($_SESSION['carrinho'][$id_produto_remover]);
        header('Location: carrinho.php');
        exit();
    }
}

// LOGIC TO DISPLAY THE CART PAGE (PROCESS GET REQUESTS)
$carrinho = $_SESSION['carrinho'];
$preco_total = 0;

require_once '../templates/header.php';
?>

<main class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">O seu Carrinho de Compras</h1>
            
            <?php if (empty($carrinho)): ?>
                <div class="alert alert-info" role="alert">
                    O seu carrinho está vazio. <a href="index.php" class="alert-link">Continue a comprar</a>.
                </div>
            <?php else: ?>
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Produto</th>
                            <th scope="col" class="text-center">Quantidade</th>
                            <th scope="col" class="text-end">Preço Unitário</th>
                            <th scope="col" class="text-end">Subtotal</th>
                            <th scope="col" class="text-center">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($carrinho as $id => $item): ?>
                            <?php
                                $subtotal = $item['preco'] * $item['quantidade'];
                                $preco_total += $subtotal;
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['nome']); ?></td>
                                <td class="text-center"><?php echo $item['quantidade']; ?></td>
                                <td class="text-end">€<?php echo number_format($item['preco'], 2, ',', '.'); ?></td>
                                <td class="text-end fw-bold">€<?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                                <td class="text-center">
                                    <form action="carrinho.php" method="POST" class="d-inline">
                                        <input type="hidden" name="remover_id" value="<?php echo $id; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" title="Remover item">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fs-4"><strong>Total</strong></td>
                            <td class="text-end fs-4 fw-bold"><strong>€<?php echo number_format($preco_total, 2, ',', '.'); ?></strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                <div class="d-flex justify-content-end mt-4">
                    <a href="index.php" class="btn btn-secondary me-2">Continuar a Comprar</a>
                    <a href="checkout.php" class="btn btn-success">Finalizar Compra</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
require_once '../templates/footer.php';
?>
