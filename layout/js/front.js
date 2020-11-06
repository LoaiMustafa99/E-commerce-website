$(function () {
    
    'use strict';

    // switch Between Login & Signup

    $('.login-page h1 span').click(function () {

        $(this).addClass('active').siblings().removeClass('active');

        $('.login-page form').hide();

        $('.' + $(this).data('class')).fadeIn(100);

    });

    // Trigger The SelectBox

    $("select").selectBoxIt({
        autoWidth: false
    });

    // Hide Placeholder On Form Focus

    $('[placeholder]').focus(function () {

        $(this).attr('data-text', $(this).attr('placeholder'));

        $(this).attr('placeholder', '');

        }).blur(function () {

            $(this).attr('placeholder', $(this).attr('data-text'));

        });

        // Add Astrisk On Required Field

        $('input').each(function () {

            if ($(this).attr('required') === 'required') {

                $(this).after('<span class="asterisk">*</span');

            }

        });

    // Confirmation message On Buttton

    $('.confirm').click(function () {

        return confirm('Are You Sure?');

    });

    $('.live-name').keyup(function () {

        $('.live-preview .caption h3').text($(this).val());

    });
    $('.live-desc').keyup(function () {

        $('.live-preview .caption p').text($(this).val());

    });
    $('.live-price').keyup(function () {

        $('.live-preview  span').text( '$'+ $(this).val());

    });

});

