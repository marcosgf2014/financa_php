<?php
require_once 'config.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 'dashboard';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Financeiro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Controle Financeiro</a>
            <div class="navbar-nav">
                <a class="nav-link <?php echo $pagina == 'dashboard' ? 'active' : ''; ?>" href="?pagina=dashboard">Dashboard</a>
                <a class="nav-link <?php echo $pagina == 'transacoes' ? 'active' : ''; ?>" href="?pagina=transacoes">Transações</a>
                <a class="nav-link <?php echo $pagina == 'relatorios' ? 'active' : ''; ?>" href="?pagina=relatorios">Relatórios</a>
                <a class="nav-link" href="logout.php">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php
        switch($pagina) {
            case 'dashboard':
                include 'dashboard.php';
                break;
            case 'transacoes':
                include 'transacoes.php';
                break;
            case 'relatorios':
                include 'relatorios.php';
                break;
            default:
                include 'dashboard.php';
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <script src="script.js"></script>
</body>
</html>