/*global $*/
$(function () {
    'use strict';

    function isFloat(n) {
        return Number(n) === n && n % 1 !== 0;
    }

    // подключение слайдера
    $('.flexslider').flexslider({
        animation: "slide",
        directionNav: false,
    });

    // подключение слайдера расписания
    $('.schedule-slider').flexslider({
        animation: 'slide',
        directionNav: false,
        slideshow: false,
        animationLoop: false,
        touch: false,
        keyboard: false,
        prevText: "Previous 7 days",
        nextText: "Next 7 days",
        controlNav: true,
        manualControls: ".schedule .custom-controls li"
    });

    // room photo-slides
    $('.room-slides').flexslider({
        animation: "slide",
        animationLoop: true,
        directionNav: false,
        keyboard: false,
        slideshow: true,
        randomize: true,
        controlNav: false,
    });

    $('.room-slides .custom-navigation .flex-next').on('click', function () {
        $('.room-slides').flexslider('next');
        return false;
    });
    $('.room-slides .custom-navigation .flex-prev').on('click', function () {
        $('.room-slides').flexslider('prev');
        return false;
    });

    // shares slides
    $('.shares-slider .flexslider').flexslider({
        animation: "slide",
        animationLoop: true,
        keyboard: false,
        slideshow: true,
        randomize: true,
        directionNav: false,
        controlNav: false,
        pauseOnAction: true,
        pauseOnHover: true
    });

    $('.shares-slider .custom-navigation .flex-next').on('click', function () {
        $('.shares-slider .flexslider').flexslider('next');
        return false;
    });
    $('.shares-slider .custom-navigation .flex-prev').on('click', function () {
        $('.shares-slider .flexslider').flexslider('prev');
        return false;
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
        // e.preventDefault();
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

    //////////////////////////////////////////////////////////////////////////////////////
    // booking ///////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////

    var bookingAllowed = true;
    var $greetingForm = $('.form-reservation .greeting-form');
    var $bookingForm = $('.form-reservation .booking-form');
    var $resultForm = $('.form-reservation .result-form');
    var bookingDiscount = 0;

    function resetBookingForm() {
        $bookingForm.find('#date').html(' --.--.---');
        $bookingForm.find('#time').html(' --:--');
        $bookingForm.find('#players').html('0');
        $bookingForm.find('#price').html('0');
        $bookingForm.find('#old-price').html('');
        $bookingForm.find('#discount').html('0');
        $bookingForm.find('input').val('');
        $bookingForm.find('select[name=players]').children('option:not(:first)').remove();
        $bookingForm.find('select[name=players]').val(playersTrans);
        $bookingForm.find('.custom-danger').removeClass('custom-danger');
        $bookingForm.find('.custom-success').removeClass('custom-success');
        $bookingForm.find('.btn-wrap span.fa-spinner').hide();
        $bookingForm.find('.btn-wrap button').show();
        $bookingForm.find('[disabled]').attr('disabled', false);
        $bookingForm.find('select[name=players]').children('option:first').attr('disabled', true);
        bookingDiscount = 0;
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

    function buildBookingForm($cell) {
        $bookingForm.find('#date').html($cell.data('date-time').split(' ')[0]);
        $bookingForm.find('#time').html($cell.data('date-time').split(' ')[1]);
        $.each($cell.data('prices'), function () {
            $bookingForm.find('select[name=players]').append($('<option>', {
                value: this.price,
                text: this.players,
                data: {
                    priceId: this.id
                }
            }));
        });
    }

    function collectBookingData() {
        return {
            dateTime: $bookingForm.find('#date').html() + ' ' + $bookingForm.find('#time').html(),
            // price: $bookingForm.find('#price').html(),
            name: $bookingForm.find('input[name=name]').val(),
            lastName: $bookingForm.find('input[name=lastName]').val(),
            email: $bookingForm.find('input[name=email]').val(),
            phone: $bookingForm.find('input[name=phone]').val(),
            players: $bookingForm.find('select[name=players] :selected').html(),
            price: $bookingForm.find('select[name=players] :selected').val(),
            priceId: $bookingForm.find('select[name=players] :selected').data('priceId'),
            discount: $bookingForm.find('input[name=discount]').val(),
            bookedBy: 'customer' // index 0 equals 'customer'
        };
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
        if (!data.priceId) {
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
        var re = /^\+[0-9]{9,18}$/;
        return re.test(phone);
    }

    function showThe($element) {
        $('.form-reservation > div:visible').fadeOut(1000, function () {
            $element.fadeIn(1000);
            bookingAllowed = true;
        });
    }

    function disableBookingForm() {
        bookingAllowed = false;
        $bookingForm.find('.btn-wrap span.fa-spinner').show();
        $bookingForm.find('.btn-wrap button').hide();
        $bookingForm.find('input').attr('disabled', true);
        $bookingForm.find('select').attr('disabled', true);
        $bookingForm.find('button').attr('disabled', true);
    }

    function setGameBooked(bookingData) {
        var $game = $('td[data-date-time="' + bookingData.dateTime + '"]');
        $game.find('.cell').removeClass('price-xs price-s price-m price-l');
        $game.find('.cell').addClass('cell-expired');
    }

    function sendNewGameData(bookingData) {
        disableBookingForm();
        $.ajax({
            type: "POST",
            url: gamesAddRoute,
            data: {bookingData}
        }).done(function (r) {
            bookingData.liqPayBtn = r.data.liqPay['button'];
            resetResultForm();
            buildResultForm(bookingData);
            showThe($resultForm);
            setGameBooked(bookingData);
        }).fail(function (r) {
            flashModal('error', 'Error', 'Something went wrong, please try again.');
        });
    }

    $bookingForm.find('input').focusin(function () {
        $(this).removeClass('custom-danger');
    });

    $bookingForm.find('select').focusin(function () {
        $(this).removeClass('custom-danger');
    });

    function buildResultForm(bookingData) {
        $resultForm.find('#result-form-date').html(bookingData.dateTime.split(' ')[0]);
        $resultForm.find('#result-form-time').html(bookingData.dateTime.split(' ')[1]);
        $resultForm.find('#result-form-players').html(bookingData.players);
        $resultForm.find('#result-form-price').html($("#price").html());
        $resultForm.find('#result-form-buttons').html(bookingData.liqPayBtn);
    }

    var typingTimer;
    var typingInterval = 1000;

    var $bookingDiscountInput = $bookingForm.find('input[name=discount]');
    $bookingDiscountInput.keyup(function () {
        var discount = $(this).val();
        clearTimeout(typingTimer);
        typingTimer = setTimeout(function () {
            checkDiscount(discount);
        }, typingInterval);
    });

    function checkDiscount(discount) {
        $.ajax({
            type: "POST",
            url: discountShowRoute,
            data: {
                discount: discount
            }
        }).done(function (result) {
            $bookingDiscountInput.removeClass('custom-danger');
            $bookingDiscountInput.addClass('custom-success');
            bookingDiscount = result.discount;
            showPrice();
        }).fail(function (r) {
            $bookingDiscountInput.removeClass('custom-success');
            $bookingDiscountInput.addClass('custom-danger');
            bookingDiscount = 0;
            showPrice();
        });
    }

    function calculatePrice(price) {
        var newPrice = (( ( 100 - bookingDiscount ) / 100 ) * price);
        if (isFloat(newPrice)) {
            return newPrice.toFixed(2);
        }
        return newPrice;
    }

    function showPrice() {
        var price = $bookingForm.find('select[name=players]').find(':selected').val();
        $bookingForm.find('#discount').html(bookingDiscount);
        if (!isNaN(price)) {
            $bookingForm.find('#price').html(calculatePrice(price));
            if (bookingDiscount > 0) {
                $bookingForm.find('#old-price').html(price);
            } else {
                $bookingForm.find('#old-price').html('');
            }
        }
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
        showPrice();
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

//////////////////////////////////////////////////////////////////////
/////////////////// feedback /////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
    var $feedbackFrom = $('#feedback-form');

    function initStarRating(ratingClass) {
        $feedbackFrom.find(ratingClass).starRating({
            totalStars: 10,
            starSize: 16,
            useFullStars: true,
            useGradient: false,
            activeColor: 'orange',
            disableAfterRate: false,
            onHover: function (currentIndex, currentRating, $el) {
                $(ratingClass + '-rating').text(currentIndex);
                $(ratingClass + '-rating').closest('.custom-danger').removeClass('custom-danger');
            },
            onLeave: function (currentIndex, currentRating, $el) {
                $(ratingClass + '-rating').text(currentRating);
            }
        });
        $feedbackFrom.find(ratingClass).starRating('setRating', 0);
    }

    function collectFeedbackData() {
        return {
            name: $feedbackFrom.find('input[name=name]').val(),
            email: $feedbackFrom.find('input[name=email]').val(),
            phone: $feedbackFrom.find('input[name=phone]').val(),
            comment: $feedbackFrom.find('textarea[name=comment]').val(),
            time: $feedbackFrom.find('input[name=time]').val(),
            atmosphere: $feedbackFrom.find('.atmosphere-rating').text(),
            story: $feedbackFrom.find('.story-rating').text(),
            service: $feedbackFrom.find('.service-rating').text(),
        };
    }

    function validateFeedbackForm(data) {
        var hasError = false;
        if (!data.name) {
            $feedbackFrom.find('input[name=name]').addClass('custom-danger', 1000);
            hasError = true;
        }
        if (!validateEmail(data.email)) {
            $feedbackFrom.find('input[name=email]').addClass('custom-danger', 1000);
            hasError = true;
        }
        if (!validatePhone(data.phone)) {
            $feedbackFrom.find('input[name=phone]').addClass('custom-danger', 1000);
            hasError = true;
        }
        if (!data.comment) {
            $feedbackFrom.find('textarea[name=comment]').addClass('custom-danger', 1000);
            hasError = true;
        }
        if (!validateMinutes(data.time)) {
            $feedbackFrom.find('input[name=time]').addClass('custom-danger', 1000);
            hasError = true;
        }
        if (data.atmosphere === '0') {
            $feedbackFrom.find('.atmosphere').closest('.rating-title').addClass('custom-danger', 1000);
            hasError = true;
        }
        if (data.story === '0') {
            $feedbackFrom.find('.story').closest('.rating-title').addClass('custom-danger', 1000);
            hasError = true;
        }
        if (data.service === '0') {
            $feedbackFrom.find('.service').closest('.rating-title').addClass('custom-danger', 1000);
            hasError = true;
        }
        return hasError;
    }

    function validateMinutes(minutes) {
        var re = /^[0-9]{1,7}$/;
        return re.test(minutes);
    }

    function resetFeedbackForm() {
        $feedbackFrom.find('input[name=name]').val('');
        $feedbackFrom.find('input[name=email]').val('');
        $feedbackFrom.find('input[name=phone]').val('');
        $feedbackFrom.find('textarea[name=comment]').val('');
        $feedbackFrom.find('input[name=time]').val('');
        $feedbackFrom.find('.atmosphere-rating').html('0');
        $feedbackFrom.find('.story-rating').html('0');
        $feedbackFrom.find('.service-rating').html('0');
        $feedbackFrom.find('.custom-danger').removeClass('custom-danger');
        $feedbackFrom.find('.block-layer').hide();
    }

    $feedbackFrom.find('input').focusin(function () {
        $(this).removeClass('custom-danger');
    });

    $feedbackFrom.find('textarea').focusin(function () {
        $(this).removeClass('custom-danger');
    });

    function sendNewFeedbackData(data) {
        $feedbackFrom.find('.block-layer').show();
        $.ajax({
            type: "POST",
            url: feedbacksAddRoute,
            data: {data}
        }).done(function (r) {
            resetFeedbackForm();
            $feedbackFrom.modal('hide');
            flashModal('success', 'Success', 'Feedback successfully sent.');
        }).fail(function (r) {
            flashModal('error', 'Error', 'Something went wrong, please try again.');
        });
    }

    $('a[data-target="#feedback-form"]').click(function () {
        resetFeedbackForm();
        initStarRating('.atmosphere');
        initStarRating('.story');
        initStarRating('.service');
    });

    $feedbackFrom.submit(function (e) {
        e.preventDefault();
        var data = collectFeedbackData();
        if (validateFeedbackForm(data)) {
            return;
        }
        sendNewFeedbackData(data);
    });

    function flashModal(status, title, message) {
        var $modal = $('#flash-modal');
        $modal.find('.modal-header').addClass('flash-modal-' + status);
        $modal.find('.modal-title').html(title);
        $modal.find('.modal-body').html(message);
        setTimeout(function () {
            $modal.modal('show');
        }, 400);
    }

    $('#flash-modal').on('hidden.bs.modal', function () {
        $(this).find('.flash-modal-success').removeClass('flash-modal-success');
        if ($(this).find('.modal-header').hasClass('flash-modal-error')) {
            $(this).find('.flash-modal-error').removeClass('flash-modal-error');
            window.location.reload();
        }
    });

//////////////////////////////////////////////////////////////////////
/////////////////// callback /////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
    var $callbackFrom = $('#callback-form');

    $callbackFrom.submit(function (e) {
        e.preventDefault();
        var data = collectCallbackData();
        if (validateCallbackForm(data)) {
            return;
        }
        sendNewCallbackData(data);
    });

    $('a[data-target="#callback-form"]').click(function () {
        resetFeedbackForm();
    });

    function collectCallbackData() {
        return {
            name: $callbackFrom.find('input[name=name]').val(),
            lastName: $callbackFrom.find('input[name=lastName]').val(),
            email: $callbackFrom.find('input[name=email]').val(),
            phone: $callbackFrom.find('input[name=phone]').val(),
            comment: $callbackFrom.find('textarea[name=comment]').val(),
        };
    }

    function validateCallbackForm(data) {
        var hasError = false;
        if (!data.name) {
            $callbackFrom.find('input[name=name]').addClass('custom-danger', 1000);
            hasError = true;
        }
        if (!data.lastName) {
            $callbackFrom.find('input[name=lastName]').addClass('custom-danger', 1000);
            hasError = true;
        }
        if (!validateEmail(data.email)) {
            $callbackFrom.find('input[name=email]').addClass('custom-danger', 1000);
            hasError = true;
        }
        if (!validatePhone(data.phone)) {
            $callbackFrom.find('input[name=phone]').addClass('custom-danger', 1000);
            hasError = true;
        }
        if (!data.comment) {
            $callbackFrom.find('textarea[name=comment]').addClass('custom-danger', 1000);
            hasError = true;
        }
        return hasError;
    }

    function resetCallbackForm() {
        $callbackFrom.find('input[name=name]').val('');
        $callbackFrom.find('input[name=lastName]').val('');
        $callbackFrom.find('input[name=email]').val('');
        $callbackFrom.find('input[name=phone]').val('');
        $callbackFrom.find('textarea[name=comment]').val('');
        $callbackFrom.find('.custom-danger').removeClass('custom-danger');
        $callbackFrom.find('.block-layer').hide();
    }

    $callbackFrom.find('input').focusin(function () {
        $(this).removeClass('custom-danger');
    });

    $callbackFrom.find('textarea').focusin(function () {
        $(this).removeClass('custom-danger');
    });

    function sendNewCallbackData(data) {
        $callbackFrom.find('.block-layer').show();
        $.ajax({
            type: "POST",
            url: callbacksAddRoute,
            data: {data}
        }).done(function (r) {
            resetCallbackForm();
            $callbackFrom.modal('hide');
            flashModal('success', 'Success', 'Callback successfully sent.');
        }).fail(function (r) {
            flashModal('error', 'Error', 'Something went wrong, please try again.');
        });
    }
})
;
