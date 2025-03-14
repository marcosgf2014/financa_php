$(document).ready(function() {
    // Configuração da máscara de moeda
    $('.money').maskMoney({
        prefix: 'R$ ',
        thousands: '.',
        decimal: ',',
        allowZero: true
    });

    // Atualização dinâmica dos tipos de serviço
    $('#tipo').change(function() {
        var tipo = $(this).val();
        var options = '';
        
        if (tipo === 'entrada') {
            options = `
                <option value="Frete">Frete</option>
                <option value="Guinchada">Guinchada</option>
                <option value="Extra">Extra</option>
            `;
        } else if (tipo === 'saida') {
            options = `
                <option value="Carro">Carro</option>
                <option value="Diversos">Diversos</option>
                <option value="Bancos">Bancos</option>
                <option value="Comida">Comida</option>
            `;
        }
        
        $('#tipo_servico').html(options);
    });
});