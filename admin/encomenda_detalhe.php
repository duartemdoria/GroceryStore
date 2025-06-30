<?php
session_start();
require_once '../config/database.php';
require_once 'includes/auth_check.php';

// Checks if an order ID was provided
if (!isset($_GET['id'])) {
    header('Location: encomendas.php');
    exit();
}

$id_encomenda = (int)$_GET['id'];

try {
    // Fetch the main order data
    $stmt_encomenda = $pdo->prepare("SELECT * FROM encomendas WHERE id = ?");
    $stmt_encomenda->execute([$id_encomenda]);
    $encomenda = $stmt_encomenda->fetch();

    if (!$encomenda) {
        header('Location: encomendas.php');
        exit();
    }

    // Fetches the products associated with this order
    $stmt_detalhes = $pdo->prepare("
        SELECT de.quantidade, de.preco_unitario, p.nome AS nome_produto
        FROM detalhes_encomenda AS de
        JOIN produtos AS p ON de.id_produto = p.id
        WHERE de.id_encomenda = ?
    ");
    $stmt_detalhes->execute([$id_encomenda]);
    $detalhes_produtos = $stmt_detalhes->fetchAll();

} catch (PDOException $e) {
    die("Erro ao carregar detalhes da encomenda: " . $e->getMessage());
}

require_once 'templates/header.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Detalhes da Encomenda #<?php echo $encomenda['id']; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="encomendas.php">Encomendas</a></li>
        <li class="breadcrumb-item active">Detalhes da Encomenda</li>
    </ol>

    <div class="row">
        <!-- Customer Details Card -->
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    Informações do Cliente
                </div>
                <div class="card-body">
                    <p><strong>Nome:</strong> <?php echo htmlspecialchars($encomenda['nome_cliente']); ?></p>
                    <p><strong>Data de Nascimento:</strong> <?php echo date('d/m/Y', strtotime($encomenda['data_nascimento'])); ?></p>
                    <p><strong>Morada de Entrega:</strong><br><?php echo nl2br(htmlspecialchars($encomenda['morada'])); ?></p>
                </div>
            </div>
        </div>
        <!-- Purchase Details Card -->
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Informações da Compra
                </div>
                <div class="card-body">
                    <p><strong>Data da Encomenda:</strong> <?php echo date('d/m/Y H:i:s', strtotime($encomenda['data_encomenda'])); ?></p>
                    <p class="fs-4"><strong>Total da Encomenda:</strong> €<?php echo number_format($encomenda['preco_total'], 2, ',', '.'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Purchased Products Card -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-shopping-basket me-1"></i>
            Produtos Comprados
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th class="text-center">Quantidade</th>
                        <th class="text-end">Preço Unitário</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalhes_produtos as $produto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($produto['nome_produto']); ?></td>
                            <td class="text-center"><?php echo $produto['quantidade']; ?></td>
                            <td class="text-end">€<?php echo number_format($produto['preco_unitario'], 2, ',', '.'); ?></td>
                            <td class="text-end">€<?php echo number_format($produto['quantidade'] * $produto['preco_unitario'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer text-end">
            <a href="encomendas.php" class="btn btn-secondary">Voltar à Lista de Encomendas</a>
        </div>
    </div>
</div>

<?php
require_once 'templates/footer.php';
?>
