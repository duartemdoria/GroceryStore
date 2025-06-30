<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administração - Mercearia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- <link href="css/styles.css" rel="stylesheet" /> -->
</head>
<body class="sb-nav-fixed">
    <!-- Navbar-->
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">

        <a class="navbar-brand ps-3" href="dashboard.php">Painel Admin</a>

        <ul class="navbar-nav me-auto">
            <li class="nav-item">
                <a class="nav-link" href="encomendas.php">Encomendas</a>
            </li>
                <li class="nav-item">
                <a class="nav-link" href="../public/index.php">Voltar à Loja</a>
            </li>
        </ul>

        <ul class="navbar-nav ms-auto me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="logout.php">Terminar Sessão</a></li>
                </ul>
            </li>
        </ul>
    </nav>
