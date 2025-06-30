<?php
session_start();
require_once '../config/database.php';

// If the administrator is already logged in, redirect to the control panel
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit();
}

$erro_login = '';

// Processes the login form when submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_utilizador = trim($_POST['nome_utilizador']);
    $palavra_passe = trim($_POST['palavra_passe']);

    if (empty($nome_utilizador) || empty($palavra_passe)) {
        $erro_login = 'Ambos os campos são de preenchimento obrigatório.';
    } else {
        try {
            // Search for the user in the database
            $stmt = $pdo->prepare("SELECT id, palavra_passe FROM utilizadores WHERE nome_utilizador = ?");
            $stmt->execute([$nome_utilizador]);
            $admin = $stmt->fetch();

            // Check if the user exists and if the password is correct
            if ($admin && password_verify($palavra_passe, $admin['palavra_passe'])) {
                // Successful login: store the admin ID in the session
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_nome'] = $nome_utilizador;
                
                // Redirect to the control panel
                header('Location: dashboard.php');
                exit();
            } else {
                $erro_login = 'Nome de utilizador ou palavra-passe incorretos.';
            }
        } catch (PDOException $e) {
            $erro_login = 'Ocorreu um erro no servidor. Por favor, tente novamente mais tarde.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Administração</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body>
    <div class="card login-card shadow-sm">
        <div class="card-body p-4">
            <h3 class="card-title text-center mb-4">Acesso à Administração</h3>
            
            <?php if (!empty($erro_login)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $erro_login; ?>
                </div>
            <?php endif; ?>

            <form action="index.php" method="POST">
                <div class="mb-3">
                    <label for="nome_utilizador" class="form-label">Nome de Utilizador</label>
                    <input type="text" class="form-control" id="nome_utilizador" name="nome_utilizador" required>
                </div>
                <div class="mb-3">
                    <label for="palavra_passe" class="form-label">Palavra-passe</label>
                    <input type="password" class="form-control" id="palavra_passe" name="palavra_passe" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </div>
            </form>
            <div class="text-center mt-3">
                <a href="../public/index.php">&laquo; Voltar à Loja</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
