<?php
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo = $_POST['tipo'];
    $tipo_servico = $_POST['tipo_servico'];
    $descricao = $_POST['descricao'];
    $valor = str_replace(['R$', '.', ','], ['', '', '.'], $_POST['valor']);
    $forma_pagamento = $_POST['forma_pagamento'];
    $nota_fiscal = $_POST['nota_fiscal'];
    
    $stmt = $pdo->prepare("
        INSERT INTO transacoes 
        (tipo, tipo_servico, data_transacao, descricao, valor, forma_pagamento, nota_fiscal, usuario_id) 
        VALUES (?, ?, CURRENT_DATE(), ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $tipo,
        $tipo_servico,
        $descricao,
        $valor,
        $forma_pagamento,
        $nota_fiscal,
        $_SESSION['usuario_id']
    ]);
    
    $_SESSION['mensagem'] = "Transação salva com sucesso!";
    header("Location: index.php?pagina=transacoes");
    exit;
}

if (isset($_SESSION['mensagem'])) {
    echo '<div class="alert alert-success">' . $_SESSION['mensagem'] . '</div>';
    unset($_SESSION['mensagem']);
}
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4>Nova Transação</h4>
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo</label>
                            <select name="tipo" class="form-select" id="tipo" required>
                                <option value="">Selecione...</option>
                                <option value="entrada">Entrada</option>
                                <option value="saida">Saída</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo de Serviço</label>
                            <select name="tipo_servico" class="form-select" id="tipo_servico" required>
                                <option value="">Selecione o tipo primeiro...</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Descrição</label>
                            <input type="text" name="descricao" class="form-control" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Valor</label>
                            <input type="text" name="valor" class="form-control money" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Forma de Pagamento</label>
                            <select name="forma_pagamento" class="form-select" required>
                                <option value="Banco do Brasil">Banco do Brasil</option>
                                <option value="Bradesco">Bradesco</option>
                                <option value="Santander">Santander</option>
                                <option value="MP PF">MP PF</option>
                                <option value="MP PJ">MP PJ</option>
                                <option value="Dinheiro">Dinheiro</option>
                                <option value="Outros">Outros</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nota Fiscal</label>
                            <input type="text" name="nota_fiscal" class="form-control">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </form>
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
                    <th>Serviço</th>
                    <th>Descrição</th>
                    <th>Valor</th>
                    <th>Pagamento</th>
                    <th>Nota Fiscal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->prepare("
                    SELECT * FROM transacoes 
                    WHERE usuario_id = ? 
                    ORDER BY data_transacao DESC, id DESC 
                    LIMIT 10
                ");
                $stmt->execute([$_SESSION['usuario_id']]);
                $transacoes = $stmt->fetchAll();
                
                if (count($transacoes) > 0) {
                    foreach ($transacoes as $row) {
                        echo "<tr>";
                        echo "<td>" . date('d/m/Y', strtotime($row['data_transacao'])) . "</td>";
                        echo "<td>" . ucfirst($row['tipo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['tipo_servico']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['descricao']) . "</td>";
                        echo "<td>R$ " . number_format($row['valor'], 2, ',', '.') . "</td>";
                        echo "<td>" . htmlspecialchars($row['forma_pagamento']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nota_fiscal']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>Nenhuma transação encontrada</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>