<?php
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Buscar totais
$stmt = $pdo->prepare("
    SELECT 
        SUM(CASE WHEN tipo = 'entrada' THEN valor ELSE 0 END) as total_entradas,
        SUM(CASE WHEN tipo = 'saida' THEN valor ELSE 0 END) as total_saidas
    FROM transacoes 
    WHERE usuario_id = ? 
    AND MONTH(data_transacao) = MONTH(CURRENT_DATE())
    AND YEAR(data_transacao) = YEAR(CURRENT_DATE())
");
$stmt->execute([$_SESSION['usuario_id']]);
$totais = $stmt->fetch();

$saldo = $totais['total_entradas'] - $totais['total_saidas'];
?>

<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Entradas</h5>
                <p class="card-text">R$ <?php echo number_format($totais['total_entradas'], 2, ',', '.'); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-danger mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Saídas</h5>
                <p class="card-text">R$ <?php echo number_format($totais['total_saidas'], 2, ',', '.'); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white <?php echo $saldo >= 0 ? 'bg-primary' : 'bg-danger'; ?> mb-3">
            <div class="card-body">
                <h5 class="card-title">Saldo</h5>
                <p class="card-text">R$ <?php echo number_format($saldo, 2, ',', '.'); ?></p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <h4>Últimas Transações</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Tipo</th>
                    <th>Descrição</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->prepare("
                    SELECT * FROM transacoes 
                    WHERE usuario_id = ? 
                    ORDER BY data_transacao DESC 
                    LIMIT 5
                ");
                $stmt->execute([$_SESSION['usuario_id']]);
                while ($row = $stmt->fetch()) {
                    echo "<tr>";
                    echo "<td>" . date('d/m/Y', strtotime($row['data_transacao'])) . "</td>";
                    echo "<td>" . ucfirst($row['tipo']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['descricao']) . "</td>";
                    echo "<td>R$ " . number_format($row['valor'], 2, ',', '.') . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>