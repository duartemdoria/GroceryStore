<?php
session_start();
require_once '../config/database.php';
require_once 'includes/auth_check.php';

// Fetch the 5 most recent orders
try {
    $stmt_encomendas = $pdo->query("SELECT id, nome_cliente, preco_total, data_encomenda FROM encomendas ORDER BY data_encomenda DESC LIMIT 5");
    $encomendas = $stmt_encomendas->fetchAll();
} catch (PDOException $e) {
    die("Erro ao ir buscar encomendas: " . $e->getMessage());
}

// Fetch all products
try {
    $stmt_produtos = $pdo->query("SELECT id, nome, quantidade, preco FROM produtos ORDER BY nome ASC");
    $produtos = $stmt_produtos->fetchAll();
} catch (PDOException $e) {
    die("Erro ao ir buscar produtos: " . $e->getMessage());
}

require_once 'templates/header.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Painel de Controlo</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Resumo da Loja</li>
    </ol>

    <!-- Recent Orders Section -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-receipt me-1"></i>
            Últimas Encomendas
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Data</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($encomendas)): ?>
                        <tr>
                            <td colspan="4" class="text-center">Ainda não foram realizadas encomendas.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($encomendas as $encomenda): ?>
                            <tr>
                                <td><?php echo $encomenda['id']; ?></td>
                                <td><?php echo htmlspecialchars($encomenda['nome_cliente']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($encomenda['data_encomenda'])); ?></td>
                                <td class="text-end">€<?php echo number_format($encomenda['preco_total'], 2, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Products Section -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-boxes-stacked me-1"></i>
            Gestão de Produtos
        </div>
        <div class="card-body">
            <div class="text-end mb-3">
                <a href="produtos_gerir.php" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Adicionar Novo Produto
                </a>
            </div>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th class="text-center">Stock</th>
                        <th class="text-end">Preço</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                            <td class="text-center"><?php echo $produto['quantidade']; ?></td>
                            <td class="text-end">€<?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                            <td class="text-center">
                                <a href="produtos_gerir.php?id=<?php echo $produto['id']; ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
require_once 'templates/footer.php';
?>
