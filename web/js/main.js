/*global $*/
$(function () {
    'use strict';

    // подключение слайдера
    $('.flexslider').flexslider({
        animation: "slide",
        directionNav: false
    });

    // меню
    var menu = $('.main-menu');
    $('.cmn-toggle-switch').click(function (e) {
        e.preventDefault();
        if (menu.css('right') === '-300px') {
            menu.animate({right: 0}, 500);
            $('.open-menu').css('display', 'block');
            $('.cmn-toggle-switch').addClass('active');
        } else {
            menu.animate({right: '-300px'}, 500);
            $('.open-menu').css('display', 'none');
            $('.cmn-toggle-switch').removeClass('active');
        }
    });

    //скрываем меню, если клик не на нем
    $(document).mouseup(function (e) {
        if (menu.css('right') === '0px' && !menu.is(e.target) && menu.has(e.target).length === 0) {
            menu.animate({right: '-300px'}, 500);
            $('.open-menu').css('display', 'none');
            $('.cmn-toggle-switch').removeClass('active');
        }
    });

    //список городов
    var cityList = $('.city-list');
    var cityListItem = $('.city-list li a');
    var location = $('.location');
    var city = $('.city');
    location.click(function (e) {
        e.preventDefault();
        if (cityList.css('display') === 'none') {
            cityList.slideDown();
            city.css('color', 'transparent');
        } else {
            cityList.hide();
            e.stopImmediatePropagation();
            city.css('color', '#fff');
        }
    });

    //выбор города
    cityListItem.click(function (e) {
        e.preventDefault();
        city.text($(this).text());
        cityList.hide();
        city.css('color', '#fff');
    });

    //скрываем список городов, если клик не на нем
    $(document).mouseup(function (e) {
        if (!cityList.is(e.target) && cityList.has(e.target).length === 0) {
            cityList.hide();
            city.css('color', '#fff');
        }
    });

    //получаем текущий год в футере
    var today = new Date();
    var year = today.getFullYear();
    $('.year').text(year);

    // подключение слайдера расписания
    $('.shedule-slider').flexslider({
        animation: 'slide',
        controlNav: false,
        directionNav: true,
        slideshow: false,
        animationLoop: false,
        touch: false
    });

    //сортировка
    var sortList = $('.sort-list');
    var sortListItem = $('.sort-list li a');
    var sortWrapper = $('.sort-wrapper');
    sortWrapper.click(function (e) {
        e.preventDefault();
        if (sortList.css('display') === 'none') {
            sortList.slideDown();
        } else {
            sortList.hide();
        }
    });

    //выбор сортировки
    sortListItem.click(function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        $('.sort-text').text($(this).text());
        sortList.hide();
    });

    //скрываем сортировку, если клик не на ней
    $(document).mouseup(function (e) {
        if (!sortWrapper.is(e.target) && sortWrapper.has(e.target).length === 0) {
            sortList.hide();
        }
    });

    //полифил для IE для свойства object-fill
    objectFitImages('img', {watchMQ: true});

    //галерея
    $('.fancy').fancybox();

    //звезды рейтинга
    $(".atmosphere").starRating({
        totalStars: 10,
        starSize: 16,
        useFullStars: true,
        useGradient: false,
        activeColor: 'orange',
        disableAfterRate: false,
        onHover: function (currentIndex, currentRating, $el) {
            $('.atmosphere-rating').text(currentIndex);
        },
        onLeave: function (currentIndex, currentRating, $el) {
            $('.atmosphere-rating').text(currentRating);
        }
    });
    $(".plot").starRating({
        totalStars: 10,
        starSize: 16,
        useFullStars: true,
        useGradient: false,
        activeColor: 'orange',
        disableAfterRate: false,
        onHover: function (currentIndex, currentRating, $el) {
            $('.plot-rating').text(currentIndex);
        },
        onLeave: function (currentIndex, currentRating, $el) {
            $('.plot-rating').text(currentRating);
        }
    });
    $(".service").starRating({
        totalStars: 10,
        starSize: 16,
        useFullStars: true,
        useGradient: false,
        activeColor: 'orange',
        disableAfterRate: false,
        onHover: function (currentIndex, currentRating, $el) {
            $('.service-rating').text(currentIndex);
        },
        onLeave: function (currentIndex, currentRating, $el) {
            $('.service-rating').text(currentRating);
        }
    });

    //////////////////////////////////////////////////////////////////////////////////////
    // booking ///////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////

    var $greetingForm = $('.form-reservation .greeting-form');
    var $bookingForm = $('.form-reservation .booking-form');
    var $resultForm = $('.form-reservation .result-form');

    function resetBookingForm() {
        $greetingForm.css('display', 'block');
        $bookingForm.css('display', 'none');
        $resultForm.css('display', 'none');
        $bookingForm.find('#date').html(' --.--.---');
        $bookingForm.find('#time').html(' --:--');
        $bookingForm.find('#price').html('0');
        $bookingForm.find('input').val('');
        $bookingForm.find('#players').children('option:not(:first)').remove();
        $bookingForm.find('#players').val('Players*');
    }

    function scrollTo(target) {
        $('html, body').animate({
            scrollTop: target.offset().top
        }, 1000);
    }

    function buildBookingForm($target) {
        $bookingForm.find('#date').html(' ' + $target.data('date-time').split(' ')[0]);
        $bookingForm.find('#time').html(' ' + $target.data('date-time').split(' ')[1]);
        $.each($target.data('prices'), function () {
            $bookingForm.find('#players').append(
                $('</option>', {
                    text: this.players,
                    value: this.price
                })
            );
        });
        $greetingForm.fadeOut('2000');
        $bookingForm.fadeIn('2000');
    }

    $('.cell').closest('td').click(function (e) {
        e.preventDefault();
        if ($(this).find('.cell').hasClass('cell-expired')) {
            return;
        }
        resetBookingForm();
        buildBookingForm($(this));
        scrollTo($('section.form-reservation'));
    });
});

