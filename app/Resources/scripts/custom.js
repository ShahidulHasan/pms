jQuery(document).ready(function () {

    $("#bcl_userbundle_user_username").change(function () {
        var userName = $(this).val();

        $.ajax({
            type: "post",
            url: Routing.generate('username_check'),
            data: "userName=" + userName,
            dataType: 'json',
            success: function (msg) {
                if (msg.responseCode == 200) {
                    $('.userMessege').css('color', 'red').html(msg.user_name).fadeIn(1000);
                }
                else {
                    $('.userMessege').css('color', 'green').html(msg.user_name).fadeIn(1000);
                }
            },
            error: function () {
                alert('Script Error');
            }
        });

    });

//duplicate  email check

    $("#bcl_userbundle_user_email").change(function () {
        var email = $(this).val();

        $.ajax({
            type: "post",
            url: Routing.generate('email_check'),
            data: "email=" + email,
            dataType: 'json',
            success: function (msg) {
                if (msg.responseCode == 200) {
                    $('.emailMessege').css('color', 'red').html(msg.email_check).fadeIn(1000);
                }
                else {
                    $('.emailMessege').css('color', 'green').html(msg.email_check).fadeIn(1000);
                }
            },
            error: function () {
                alert('Script Error');
            }
        });

    });

    /* end duplicate email check*/

    /*populate price and commission on file*/

    $('#bcl_offerbundle_offer_category').change(function () {
        $('.add-more-category').show();
        var category_id = $('#bcl_offerbundle_offer_category').val();

        $.ajax({
            type: "post",
            url: Routing.generate('offer_get_price_commission'),
            data: "category_id=" + category_id,
            dataType: 'json',
            success: function (msg) {
                if (msg) {
                    $('.bcl_offerbundle_offer_price').val(msg.price);
                    $('.bcl_offerbundle_offer_commission').val(msg.commission);
                    $('.bcl_offerbundle_offer_id').val(msg.id);
                } else {
                    $('.bcl_offerbundle_offer_price').val('');
                    $('.bcl_offerbundle_offer_commission').val('');
                    $('.bcl_offerbundle_offer_id').val('');
                }
            },
            error: function () {
                alert('Script Error');
            }
        });

        function totalCalculate(e) {

            var total = ($('.bcl_offerbundle_offer_price').val() * $('.bcl_offerbundle_offer_quantity').val());
            var commission = ( $('.bcl_offerbundle_offer_commission').val() * total ) / 100;
            var line_total = parseFloat(total) + parseFloat(commission);
            $('.bcl_offerbundle_offer_lineTotal').val(parseFloat(line_total));

//            if(isNaN($('.totalGrand').val())){
//                var linetotal1 = parseFloat(0);
//                var grand_total = parseFloat(linetotal1)+parseFloat($('.bcl_filebundle_file_lineTotal').val());
//
//            }
//            else{
//                var linetotal1 = parseFloat($('.totalGrand').val());
//                var grand_total = parseFloat(linetotal1)+parseFloat($('.bcl_filebundle_file_lineTotal').val());
//
//            }
////                var grand_total = parseFloat(linetotal1)+parseFloat($('.bcl_filebundle_file_lineTotal').val());
//                $('.totalGrand').val(parseFloat(grand_total));
//
////            }

        }

        $("input").removeClass("bcl_offerbundle_offer_price");
        $("input").removeClass("bcl_offerbundle_offer_commission");
        $("input").removeClass("bcl_offerbundle_offer_quantity");
        $("input").removeClass("bcl_offerbundle_offer_lineTotal");
        $("input").removeClass("bcl_offerbundle_offer_id");

        $('.bcl_offerbundle_offer_quantity').live("click keyup", (totalCalculate));

    });

    /*end populate price and commission on file*/

    function withCat() {
        $('.category').hide();
        $('.with-cat').fadeIn(1000);
        $('.without-cat').hide();
        $('.with-pi').show();
    }

    function withOutCat() {
        $('.category').hide();
        $('.without-cat').fadeIn(1000);
        $('.with-cat').hide();
        $('.with-pi').hide();
    }

    $('.switch-right').live("click", function () {
        withCat();
    });

    $('.switch-left').live("click", function () {
        withOutCat();
    });

    $.validator.addMethod("uidValid", function(uid, element) {
        return (this.optional(element) || uid.match(/^[a-z][a-z0-9]*$/i));
    }, "Please specify a valid user id");

});