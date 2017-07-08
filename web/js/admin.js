$(function () {
    var bookingModal = $('#bookingModal');

    $('.book-btn').click(function (e) {
        e.preventDefault();
        bookingModal.find('.game-date span').html($(this).closest('td').data('date'));
        bookingModal.find('.game-time span').html($(this).closest('td').data('time'));
        var pricesList = $(this).closest('td').find('.hidden-prices').html();
        bookingModal.find('.price-list').append(pricesList);
    });

    bookingModal.find('button.btn-primary').click(function () {
        var bookingData = collectBookingData();
        if (!validateBookingModal(bookingData)) {
            showSpinner();
            sendNewGameData(bookingData);
        }
        removeHasErrorClass();
    });

    function collectBookingData() {
        var booking = {
            date: bookingModal.find('.game-date span').html(),
            time: bookingModal.find('.game-time span').html(),
            players: '',
            price: '',
            name: bookingModal.find('input[name=name]').val(),
            secondName: bookingModal.find('input[name=secondName]').val(),
            email: bookingModal.find('input[name=email]').val(),
            phone: bookingModal.find('input[name=phone]').val(),
            discount: bookingModal.find('input[name=discount]').val(),
            bookedBy: 1 // index 1 equals 'admin'
        };
        var priceJSON = bookingModal.find('input[name=price]:checked').val();
        if (priceJSON) {
            booking.players = JSON.parse(priceJSON).players;
            booking.price = JSON.parse(priceJSON).price;
        }
        return booking;
    }

    function validateBookingModal(data) {
        var hasError = false;
        if (!data.date) {
            alert('Got no date, please try again later or contact the administrator.');
            hasError = true;
        }
        if (!data.time) {
            alert('Got no date, please try again later or contact the administrator.');
            hasError = true;
        }
        if (!data.price || !data.players) {
            bookingModal.find('.input-group').has('input[name=price]').addClass('has-error', 1000);
            hasError = true;
        }
        if (!data.name) {
            bookingModal.find('.input-group').has('input[name=name]').addClass('has-error', 1000);
            hasError = true;
        }
        if (!data.secondName) {
            bookingModal.find('.input-group').has('input[name=secondName]').addClass('has-error', 1000);
            hasError = true;
        }
        if (!data.email) {
            bookingModal.find('.input-group').has('input[name=email]').addClass('has-error', 1000);
            hasError = true;
        }
        if (!data.phone) {
            bookingModal.find('.input-group').has('input[name=phone]').addClass('has-error', 1000);
            hasError = true;
        }
        return hasError;
    }

    function removeHasErrorClass() {
        setTimeout(function () {
            bookingModal.find('.input-group').has('input[name=price]').removeClass('has-error', 5000);
            bookingModal.find('.input-group').has('input[name=name]').removeClass('has-error', 5000);
            bookingModal.find('.input-group').has('input[name=secondName]').removeClass('has-error', 5000);
            bookingModal.find('.input-group').has('input[name=email]').removeClass('has-error', 5000);
            bookingModal.find('.input-group').has('input[name=phone]').removeClass('has-error', 5000);
        }, 5000);
    }

    function sendNewGameData(bookingData) {
        $.ajax({
            type: "POST",
            url: adminGamesAddRoute,
            data: {bookingData}
        }).done(function () {
            window.location.replace(adminRoomsScheduleRoute);
        }).fail(function () {
            alert('Something went wrong, please contact the administrator.')
        });
    }

    function showSpinner() {
        bookingModal.find('button.btn-primary').addClass('disabled');
        bookingModal.find('button.btn-primary i').removeClass('hidden');
    }


    var correctiveModal = $('#correctiveModal');

    $('.corrective-btn').click(function (e) {
        e.preventDefault();
        correctiveModal.find('.game-date span').html($(this).closest('td').data('date'));
        correctiveModal.find('.game-time span').html($(this).closest('td').data('time'));
    });
});

