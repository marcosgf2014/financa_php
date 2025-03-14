<?php
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$mes_atual = date('m');
$ano_atual = date('Y');

if (isset($_GET['mes']) && isset($_GET['ano'])) {
    $mes_atual = $_GET['mes'];
    $ano_atual = $_GET['ano'];
}

// Buscar totais do período
$stmt = $pdo->prepare("
    SELECT 
        tipo_servico,
        tipo,
        SUM(valor) as total
    FROM transacoes 
    WHERE usuario_id = ? 
    AND MONTH(data_transacao) = ?
    AND YEAR(data_transacao) = ?
    GROUP BY tipo, tipo_servico
    ORDER BY tipo, total DESC
");
$stmt->execute([$_SESSION['usuario_id'], $mes_atual, $ano_atual]);
$resultados = $stmt->fetchAll();
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4>Relatório Mensal</h4>
                <form method="GET" class="mb-4">
                    <input type="hidden" name="pagina" value="relatorios">
                    <div class="row">
                        <div class="col-md-4">
                            <select name="mes" class="form-select">
                                <?php
                                for ($i = 1; $i <= 12; $i++) {
                                    $selected = $i == $mes_atual ? 'selected' : '';
                                    echo "<option value='$i' $selected>" . strftime('%B', mktime(0, 0, 0, $i, 1)) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="ano" class="form-select">
                                <?php
                                $ano_inicial = 2020;
                                for ($i = $ano_inicial; $i <= date('Y'); $i++) {
                                    $selected = $i == $ano_atual ? 'selected' : '';
                                    echo "<option value='$i' $selected>$i</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="col-md-6">
                        <h5>Entradas por Tipo de Serviço</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Serviço</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($resultados as $row) {
                                    if ($row['tipo'] == 'entrada') {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['tipo_servico']) . "</td>";
                                        echo "<td>R$ " . number_format($row['total'], 2, ',', '.') . "</td>";
                                        echo "</tr>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <h5>Saídas por Tipo de Serviço</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Serviço</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($resultados as $row) {
                                    if ($row['tipo'] == 'saida') {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['tipo_servico']) . "</td>";
                                        echo "<td>R$ " . number_format($row['total'], 2, ',', '.') . "</td>";
                                        echo "</tr>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>