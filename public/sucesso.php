
<?php
session_start();

// If there is no success message, redirect to the homepage
if (!isset($_SESSION['sucesso'])) {
    header('Location: index.php');
    exit();
}

$mensagem_sucesso = $_SESSION['sucesso'];
unset($_SESSION['success']);

require_once '../templates/header.php';
?>

<main class="container mt-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-success p-5">
                <h1 class="alert-heading"><i class="fas fa-check-circle"></i> Sucesso!</h1>
                <p><?php echo htmlspecialchars($mensagem_sucesso); ?></p>
                <hr>
                <p class="mb-0">Pode continuar a navegar na nossa loja.</p>
                <a href="index.php" class="btn btn-primary mt-3">Voltar à Página Inicial</a>
            </div>
        </div>
    </div>
</main>

<?php
require_once '../templates/footer.php';
?>