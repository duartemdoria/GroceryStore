<?php
session_start();
require_once '../config/database.php';
require_once 'includes/auth_check.php';

// Fetches all orders, from the most recent to the oldest
try {
    $stmt = $pdo->query("SELECT id, nome_cliente, preco_total, data_encomenda FROM encomendas ORDER BY data_encomenda DESC");
    $encomendas = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erro ao ir buscar as encomendas: " . $e->getMessage());
}

require_once 'templates/header.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Gestão de Encomendas</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Todas as Encomendas</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-receipt me-1"></i>
            Lista de Encomendas Realizadas
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>ID Encomenda</th>
                        <th>Nome do Cliente</th>
                        <th>Data da Encomenda</th>
                        <th class="text-end">Preço Total</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($encomendas)): ?>
                        <tr>
                            <td colspan="5" class="text-center">Ainda não foram realizadas encomendas.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($encomendas as $encomenda): ?>
                            <tr>
                                <td>#<?php echo $encomenda['id']; ?></td>
                                <td><?php echo htmlspecialchars($encomenda['nome_cliente']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($encomenda['data_encomenda'])); ?></td>
                                <td class="text-end">€<?php echo number_format($encomenda['preco_total'], 2, ',', '.'); ?></td>
                                <td class="text-center">
                                    <a href="encomenda_detalhe.php?id=<?php echo $encomenda['id']; ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye me-1"></i> Ver Detalhes
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
require_once 'templates/footer.php';
?>
