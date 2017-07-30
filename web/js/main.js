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
            $('.open-menu').show();
            $('.cmn-toggle-switch').addClass('active');
        } else {
            menu.animate({right: '-300px'}, 500);
            $('.open-menu').hide();
            $('.cmn-toggle-switch').removeClass('active');
        }
    });

    //скрываем меню, если клик не на нем
    $(document).mouseup(function (e) {
        if (menu.css('right') === '0px' && !menu.is(e.target) && menu.has(e.target).length === 0) {
            menu.animate({right: '-300px'}, 500);
            $('.open-menu').hide();
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

    var bookingAllowed = true;
    var $greetingForm = $('.form-reservation .greeting-form');
    var $bookingForm = $('.form-reservation .booking-form');
    var $resultForm = $('.form-reservation .result-form');

    function resetBookingForm() {
        $bookingForm.find('#date').html(' --.--.---');
        $bookingForm.find('#time').html(' --:--');
        $bookingForm.find('#players').html('0');
        $bookingForm.find('#price').html('0');
        $bookingForm.find('input').val('');
        $bookingForm.find('select[name=players]').children('option:not(:first)').remove();
        $bookingForm.find('select[name=players]').val(playersTrans);
        $bookingForm.find('.custom-danger').removeClass('custom-danger');
        $bookingForm.find('.btn-wrap span.fa-spinner').hide();
        $bookingForm.find('.btn-wrap button').show();
        $bookingForm.find('[disabled]').attr('disabled', false);
    }

    function resetResultForm() {
        $resultForm.find('#result-form-date').html('');
        $resultForm.find('#result-form-time').html('');
        $resultForm.find('#result-form-players').html('');
        $resultForm.find('#result-form-price').html('');
    }

    function scrollTo(target) {
        $('html, body').animate({
            scrollTop: target.offset().top
        }, 1000);
    }

    function buildBookingForm($form) {
        $bookingForm.find('#date').html($form.data('date-time').split(' ')[0]);
        $bookingForm.find('#time').html($form.data('date-time').split(' ')[1]);
        $.each($form.data('prices'), function () {
            $bookingForm.find('select[name=players]').append($('<option>', {value: this.price, text: this.players}));
        });
    }

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

    function showThe($element) {
        $('.form-reservation > div:visible').fadeOut(1000, function () {
            $element.fadeIn(1000);
            bookingAllowed = true;
        });
    }

    function disableBookingForm() {
        $bookingForm.find('.btn-wrap span.fa-spinner').show();
        $bookingForm.find('.btn-wrap button').hide();
        $bookingForm.find('input').attr('disabled', true);
        $bookingForm.find('select').attr('disabled', true);
        $bookingForm.find('button').attr('disabled', true);
    }

    function sendNewGameData(bookingData) {
        disableBookingForm();
        bookingAllowed = false;
        $.ajax({
            type: "POST",
            url: gamesAddRoute,
            data: {bookingData}
        }).done(function (r) {
            bookingData.liqPayBtn = r.liqPayBtn;
            resetResultForm();
            buildResultForm(bookingData);
            showThe($resultForm);
        }).fail(function (r) {
            alert('Something went wrong, please contact the administrator.')
        });
    }

    $bookingForm.find('input').focusin(function () {
        $(this).removeClass('custom-danger', 2000);
    });

    $bookingForm.find('select').focusin(function () {
        $(this).removeClass('custom-danger', 2000);
    });

    function buildResultForm(bookingData) {
        $resultForm.find('#result-form-date').html(bookingData.dateTime.split(' ')[0]);
        $resultForm.find('#result-form-time').html(bookingData.dateTime.split(' ')[1]);
        $resultForm.find('#result-form-players').html(bookingData.players);
        $resultForm.find('#result-form-price').html(bookingData.price);
        $resultForm.find('#result-form-buttons').html(bookingData.liqPayBtn);
    }

    ///////////////////////////////
    // Game cell clicked //////////
    ///////////////////////////////
    $('.cell').closest('td').click(function (e) {
        e.preventDefault();
        if ($(this).find('.cell').hasClass('cell-expired')) {
            return;
        }
        if (!bookingAllowed) {
            return;
        }
        resetBookingForm();
        buildBookingForm($(this));
        showThe($bookingForm);
        scrollTo($('section.form-reservation'));
    });

    ///////////////////////////////
    // Number of players clicked //
    ///////////////////////////////
    $bookingForm.find('select[name=players]').change(function () {
        $bookingForm.find('#players').html($(this).find(':selected').text());
        $bookingForm.find('#price').html($(this).val());
    });

    ///////////////////////////////
    // Booking submit clicked /////
    ///////////////////////////////
    $bookingForm.find('button[type="submit"]').click(function (e) {
        e.preventDefault();
        $bookingForm.find('.custom-danger').removeClass('custom-danger');
        var bookingData = collectBookingData();
        if (validateBookingForm(bookingData)) {
            return;
        }
        sendNewGameData(bookingData);
    });
});

