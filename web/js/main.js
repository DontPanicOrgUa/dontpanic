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
    var $submitFormBtn = $('.form-reservation button[type="submit"]');

    function resetVisibility() {
        $greetingForm.css('display', 'block');
        $bookingForm.css('display', 'none');
        $resultForm.css('display', 'none');
    }

    function resetBookingForm() {
        $bookingForm.find('#date').html(' --.--.---');
        $bookingForm.find('#time').html(' --:--');
        $bookingForm.find('#players').html('');
        $bookingForm.find('#price').html('');
        $bookingForm.find('input').val('');
        $bookingForm.find('select[name=players]').children('option:not(:first)').remove();
        $bookingForm.find('select[name=players]').val(playersTrans);
        $bookingForm.find('.custom-danger').removeClass('custom-danger');
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
            $bookingForm.find('select[name=players]').append($('<option>', {value: this.price, text: this.players}));
        });

        $('.form-reservation > div:visible').fadeOut(1000, function () {
            $bookingForm.fadeIn(1000);
        });
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

    $bookingForm.find('select[name=players]').change(function () {
        $bookingForm.find('#price').html($(this).val());
    });

    function collectBookingData() {
        var booking = {
            dateTime: $bookingForm.find('#date').html() + ' ' + $bookingForm.find('#time').html(),
            price: $bookingForm.find('#price').html(),
            name: $bookingForm.find('input[name=name]').val(),
            lastName: $bookingForm.find('input[name=lastName]').val(),
            email: $bookingForm.find('input[name=email]').val(),
            phone: $bookingForm.find('input[name=phone]').val(),
            players: $bookingForm.find('select[name=players] :selected').html(),
            discount: $bookingForm.find('input[name=discount]').val(),
            bookedBy: 0 // index 0 equals 'customer'
        };
        return booking;
    }

    function validateBookingForm(data) {
        var hasError = false;
        if (!data.dateTime) {
            alert('Got no date, please try again later or contact the administrator.');
            hasError = true;
        }
        if (!data.name) {
            $bookingForm.find('input[name=name]').addClass('custom-danger', 1000);
            hasError = true;
        }
        if (!data.lastName) {
            $bookingForm.find('input[name=lastName]').addClass('custom-danger', 1000);
            hasError = true;
        }
        if (!validateEmail(data.email)) {
            $bookingForm.find('input[name=email]').addClass('custom-danger', 1000);
            hasError = true;
        }
        if (!validatePhone(data.phone)) {
            $bookingForm.find('input[name=phone]').addClass('custom-danger', 1000);
            hasError = true;
        }
        if (!data.players || !data.price) {
            $bookingForm.find('select[name=players]').addClass('custom-danger', 1000);
            hasError = true;
        }
        return hasError;
    }

    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    function validatePhone(phone) {
        var re = /^\+[0-9\-\(\)]{3,21}$/;
        return re.test(phone);
    }

    function sendNewGameData(bookingData) {
        $.ajax({
            type: "POST",
            url: adminGamesAddRoute,
            data: {bookingData}
        }).done(function () {
            // resetBookingModal();
            window.location.replace(adminRoomsScheduleRoute);
        }).fail(function () {
            alert('Something went wrong, please contact the administrator.')
        });
    }

    $submitFormBtn.click(function (e) {
        e.preventDefault();
        $bookingForm.find('.custom-danger').removeClass('custom-danger');
        if (validateBookingForm(collectBookingData())) {
            // send
        }
    });

    $bookingForm.find('input').focusin(function () {
        $(this).removeClass('custom-danger', 2000);
    });

    $bookingForm.find('select').focusin(function () {
        $(this).removeClass('custom-danger', 2000);
    })
});

