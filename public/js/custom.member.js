$(function () {
    renderInput();
})
function renderInput () {

    // $('[data-plugin-datepicker]').datepicker({
    //     format:'dd/mm/yyyy',
    //     autoclose: true,
    // }).inputmask('99/99/9999');

    // $('[data-toggle="tooltip"]').tooltip();
    // $('[input-type="select2"]').select2({
    //     allowClear: true
    // });
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

    elems.forEach(function(html) {
        var switchery = new Switchery(html);
    });

    $('[name="telephone"]').inputmask('999-9999999',{placeholder:''});
    $('[name="bank_account"]').inputmask('9999999999999',{placeholder:''});
    $('[name="account"]').inputmask('9999999999999',{placeholder:''});


    $('[input-type="money_decimal"]').inputmask("numeric", {
        radixPoint: ".",
        groupSeparator: ",",
        digits: 2,
        autoGroup: true,
        rightAlign: false,
    });

    $('[input-type="character"]').keyup(function() {
        $(this).val(function(i, value) {
           return value.replace(/[^a-z0-9]/gi, '');
        });
    });


    $('[input-type="money_decimal_3"]').inputmask("numeric", {
        radixPoint: ".",
        groupSeparator: ",",
        digits: 3,
        autoGroup: true,
        rightAlign: false,
    });


    $('[input-type="money"]').inputmask("numeric", {
        min: '0',
        groupSeparator: ",",
        autoGroup: true,
        rightAlign: false,
    })

    $('[input-type="number"]').inputmask("numeric", {
        min: '0',
        autoGroup: true,
        rightAlign: false,
    });


}
