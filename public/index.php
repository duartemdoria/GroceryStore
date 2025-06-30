<?php
// Inicia a sessão para podermos usar variáveis de sessão (para o carrinho, por exemplo)
session_start();

// Inclui o ficheiro de configuração da base de dados
require_once '../config/database.php';

// Inclui o cabeçalho da página
require_once '../templates/header.php';

// Vai buscar todos os produtos à base de dados que tenham stock
try {
    // Prepara a consulta SQL para selecionar produtos com quantidade > 0
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE quantidade > 0 ORDER BY nome ASC");
    $stmt->execute();
    // Vai buscar todos os produtos como um array associativo
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Em caso de erro na base de dados, mostra uma mensagem genérica
    die("Não foi possível ir buscar os produtos: " . $e->getMessage());
}
?>

<main class="container mt-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1>A nossa Mercearia</h1>
            <p class="lead">Produtos frescos e de qualidade, à distância de um clique.</p>
        </div>
    </div>

    <!-- Secção de Produtos -->
    <div class="row">
        <?php if (empty($produtos)): ?>
            <div class="col-12">
                <div class="alert alert-warning text-center" role="alert">
                    De momento não temos produtos disponíveis. Volte em breve!
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($produtos as $produto): ?>
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Imagem do Produto -->
                        <img src="../assets/images/<?php echo htmlspecialchars($produto['imagem']); ?>" 
                             class="card-img-top" 
                             alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                             onerror="this.onerror=null;this.src='../assets/images/default.jpg';">
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($produto['nome']); ?></h5>
                            <p class="card-text text-muted flex-grow-1"><?php echo htmlspecialchars($produto['descricao']); ?></p>
                            
                            <!-- Informação de Preço e Stock -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <p class="card-text fs-5 fw-bold mb-0">€<?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                                <span class="badge bg-light text-dark">Stock: <?php echo $produto['quantidade']; ?></span>
                            </div>

                            <!-- Formulário para Adicionar ao Carrinho -->
                            <form action="carrinho.php" method="POST">
                                <input type="hidden" name="id_produto" value="<?php echo $produto['id']; ?>">
                                <div class="input-group">
                                    <input type="number" name="quantidade" class="form-control" value="1" min="1" max="<?php echo $produto['quantidade']; ?>" aria-label="Quantidade">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-cart-plus me-2"></i> Adicionar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php
// Inclui o rodapé da página
require_once '../templates/footer.php';
?>
