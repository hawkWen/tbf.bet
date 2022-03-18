jQuery.fn.preventDoubleSubmission = function() {
    $(this).on('submit',function(e){
        var $form = $(this);

        if ($form.data('submitted') === true) {
        // Previously submitted - don't submit again
            e.preventDefault();
        } else {
        // Mark it so that the next submit can be ignored
        $form.data('submitted', true);
        }
    });

    // Keep chainability
    return this;
};

$(function () {
    renderInput();
})
function renderInput () {


        
        // var swiper7 = new Swiper('.swipercards', {
        //     effect: 'coverflow',
        //     grabCursor: true,
        //     centeredSlides: true,
        //     slidesPerView: 'auto',
        //     spaceBetween: 15,
        //     coverflowEffect: {
        //         rotate: 30,
        //         stretch: 0,
        //         depth: 80,
        //         modifier: 1,
        //         slideShadows: true,
        //     }

        // });


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
