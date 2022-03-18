'use strict'
$(document).ready(function () {

    /* sidebar right color scheme */
    $('.colorsettings').on('click', function () {
        $(this).toggleClass('active');
        $('.color-picker').toggleClass('active');
    })
    $('.colorsettings2').on('click', function () {
        $('.colorsettings').removeClass('active');
        $('.color-picker').removeClass('active');
    })

    /* color style picker */
    if ($.type($.cookie("stylesheet2")) != 'undefined' && $.cookie("stylesheet2") != '') {
        var currentstyle = $('#style');
        $('head').append('<link href="css/style-' + $.cookie('stylesheet2') + '.css" rel="stylesheet"  id="style">');
        setTimeout(function () {
            currentstyle.remove();
        }, 1000);

        $('.colorselect').each(function () {
            var activestyle = $(this).find('input[type="radio"]');
            if (activestyle.next('label').attr('data-title') === $.cookie("stylesheet2")) {
                activestyle.prop("checked", true).parent().addClass('active');
            }
        })

    } else {
        $('.colorselect > input[type="radio"]').prop("checked", false);
        $.cookie("stylesheet2", "", {
            expires: 1
        });
    }
    $('.colorselect > input[type="radio"]').on('click', function () {
        $('.colorselect').removeClass('active');
        var setstyle = $(this).next().attr('data-title');
        var currentstyle = $('#style');

        if ($(this).is(':checked')) {
            $.cookie("stylesheet2", setstyle, {
                expires: 1
            });
            $('head').append('<link href="css/style-' + setstyle + '.css" rel="stylesheet"  id="style">');
            setTimeout(function () {
                currentstyle.remove();
            }, 1000);

            $(this).parent().addClass('active');

        } else {
            $.cookie("stylesheet2", "", {
                expires: 1
            });
        }
    });

    /* round layout layout */
    if ($.type($.cookie("darklayout")) != 'undefined' && $.cookie("darklayout") != '') {
        $('#darklayout, #menu-darkmode').prop("checked", true);        
        $('body').addClass($.cookie("darklayout"));
    } else {
        /*$('#roundlayout2').parent().removeClass('active');
        $('#roundlayout2').prop("checked", false);
        $.removeCookie("roundlayout2", "");
        $('body').removeClass('ui-rounded');*/
    }
    $('#darklayout, #menu-darkmode').on('click', function () {
        $(this).parent().addClass('active');

        if ($(this).is(':checked')) {
            $.cookie("darklayout", 'darkmode', {
                expires: 1
            });
            $('body').addClass('darkmode');
            $('#darklayout').parent().addClass('active');
            $('#darklayout, #menu-darkmode').prop("checked", true);
            $('#menu-lightmode').prop("checked", false);
        } else {
            $('#darklayout, #menu-darkmode').prop("checked", false);
            $('#menu-lightmode').prop("checked", true);
            $.removeCookie("darklayout", "");
            $('body').removeClass('darkmode');
            $('#darklayout').parent().removeClass('active');
        }
    });
    
    $('#menu-lightmode').on('click', function () {

        if ($(this).is(':checked')) {
            $('#darklayout, #menu-darkmode').prop("checked", false);
            $('#menu-lightmode').prop("checked", true);
            $.removeCookie("darklayout", "");
            $('body').removeClass('darkmode');
            $('#darklayout').parent().removeClass('active');
        } else {
            $.cookie("darklayout", 'darkmode', {
                expires: 1
            });
            $('body').addClass('darkmode');
            $('#darklayout').parent().addClass('active');
            $('#darklayout, #menu-darkmode').prop("checked", true);
            $('#menu-lightmode').prop("checked", false);
        }
    });

    /* RTL layout layout */
    if ($.type($.cookie("rtllayout")) != 'undefined' && $.cookie("rtllayout") != '') {
        $('#rtllayout').prop("checked", true);
        $('#rtllayout').parent().addClass('active');
        $('body').addClass($.cookie("rtllayout"));
    } else {
        $('#rtllayout').parent().removeClass('active');
        $('#rtllayout').prop("checked", false);
        $.removeCookie("rtllayout", "");
    }

    $('#rtllayout').on('click', function () {
        $(this).parent().addClass('active');
        if ($(this).is(':checked')) {
            $.cookie("rtllayout", 'rtl', {
                expires: 1
            });
            $('body').addClass('rtl');
            $('#rtllayout').parent().addClass('active');

        } else {
            $.removeCookie("rtllayout", "");
            $('body').removeClass('rtl');
            $('#rtllayout').parent().removeClass('active');
        }
    });
});
