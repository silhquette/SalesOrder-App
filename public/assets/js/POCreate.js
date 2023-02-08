// init
$('#0').addClass('grid');
$('#0').removeClass('hidden');
$("#0 input").prop("disabled", false);
$("#total").prop("readonly", true);

// add Form
let formCounter = 0
$('.fa-square-plus').click(function (e) { 
    e.preventDefault();
    if (formCounter != 6) {
        formCounter++;
        $('#' + formCounter).addClass('grid');
        $('#' + formCounter).removeClass('hidden');
        $('#' + formCounter + ' input').prop("disabled", false);
    }
    amountUpdate(formCounter);
});
$('.fa-square-minus').click(function (e) { 
    e.preventDefault();
    if (formCounter != 0) {
        $('#' + formCounter).removeClass('grid');
        $('#' + formCounter).addClass('hidden');
        $('#' + formCounter + ' input').prop("disabled", true);
        $('#' + formCounter + ' input').val('');
        formCounter--;
    }
    amountUpdate(formCounter);
});

// amount Update
function amountUpdate(limit) {
    for (let index = 0; index <= limit; index++) {
        $('#' + formCounter + ' .amount').prop("readonly", true);
        $('#'+ index +' input').keyup(function (e) { 
            let qtyValue = $('#'+ index +' .quantity').val();
            let discValue = $('#'+ index +' .discount').val();
            let priceValue = $('#'+ index +' .price').val();
            $('#'+ index +' .amount').val(priceValue * qtyValue * (100 - discValue) / 100);
            totalUpdate(limit);
        });
    }
} amountUpdate(formCounter);

// total Update
function totalUpdate(limit) {
    let total = 0;
    let taxValue = parseInt($("#ppn").val());
    for (let index = 0; index <= limit; index++) {
        total += parseInt($("#" + index + " .amount").val());
        
        // set value subtotal
        $("#subtotal").val(parseInt(total))

        // set value total (total x (1 + ppn/100))
        $("#total").val(parseInt(total) * (100 + taxValue) / 100);
    }
}

$("#ppn").keyup(function (e) { 
    totalUpdate(formCounter);
});