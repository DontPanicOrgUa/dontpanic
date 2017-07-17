$(function () {
    var bookingModal = $('#bookingModal');
    var bookingDateTime = '';
    var bookingPrices = '';

    $('.book-btn').click(function (e) {
        e.preventDefault();
        bookingDateTime = $(this).closest('td').data('date-time');
        bookingPrices = $(this).closest('td').data('prices');
        resetBookingModal();
        buildBookingModal();
    });

    bookingModal.find('button.btn-primary').click(function () {
        var bookingData = collectBookingData();
        if (!validateBookingModal(bookingData)) {
            showSpinner(bookingModal);
            sendNewGameData(bookingData);
        }
        removeHasErrorClass(bookingModal);
    });

    function resetBookingModal() {
        bookingModal.find('i').addClass('hidden');
        bookingModal.find('button').removeClass('hidden');
        bookingModal.find('.has-error').removeClass('.has-error');
        bookingModal.find('.price-list .input-group').html('');
    }

    function buildBookingModal() {
        bookingModal.find('.game-date-time span').html(bookingDateTime);
        $.each(bookingPrices, function () {
            bookingModal.find('.price-list .input-group').append(
                '<input' +
                ' type="radio"' +
                ' name="price"' +
                ' value=\'{"players": "' + this.players + '", "price": "' + this.price + '"}\'> ' + this.players + ' / ' + this.price + ' <br>'
            );
        });
    }

    function collectBookingData() {
        var booking = {
            dateTime: bookingModal.find('.game-date-time span').html(),
            players: '',
            price: '',
            name: bookingModal.find('input[name=name]').val(),
            secondName: bookingModal.find('input[name=secondName]').val(),
            email: bookingModal.find('input[name=email]').val(),
            phone: bookingModal.find('input[name=phone]').val(),
            discount: bookingModal.find('input[name=discount]').val(),
            bookedBy: userRole // index 1 equals 'admin', 2 equals 'manager'
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
        if (!data.dateTime) {
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

    function sendNewGameData(bookingData) {
        $.ajax({
            type: "POST",
            url: adminGamesAddRoute,
            data: {bookingData}
        }).done(function () {
            resetBookingModal();
            // window.location.replace(adminRoomsScheduleRoute);
        }).fail(function () {
            alert('Something went wrong, please contact the administrator.')
        });
    }

///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////

    var correctiveModal = $('#correctiveModal');
    var correctiveDateTime = '';
    var correctivePrices = '';

    $('.corrective-btn').click(function (e) {
        e.preventDefault();
        correctiveDateTime = $(this).closest('td').data('date-time');
        correctivePrices = $(this).closest('td').data('prices');
        resetCorrectiveModal();
        buildCorrectiveModal();
    });

    function resetCorrectiveModal() {
        correctiveModal.find('table tbody').html('');
        correctiveModal.find('i').addClass('hidden');
        correctiveModal.find('button').removeClass('hidden');
        correctiveModal.find('.has-error').removeClass('.has-error');
    }

    function buildCorrectiveModal() {
        correctiveModal.find('.game-date-time span').html(correctiveDateTime);
        $.each(correctivePrices, function () {
            correctiveModal.find('table tbody').append(
                '<tr>' +
                '<td><input type="text" class="form-control players" value="' + this.players + '"></td>' +
                '<td><input type="text" class="form-control price" value="' + this.price + '"></td>' +
                '</tr>'
            );
        })
    }

    correctiveModal.find('button.btn-primary').click(function () {
        var correctiveData = collectCorrectiveData();
        if (correctiveData) {
            showSpinner(correctiveModal);
            sendNewCorrectiveData(correctiveDateTime, correctiveData);
        }
        removeHasErrorClass(correctiveModal);
    });

    correctiveModal.find('button.btn-success').click(function () {
        correctiveModal.find('table tbody').append(
            '<tr>' +
            '<td><input type="text" class="form-control players" value=""></td>' +
            '<td><input type="text" class="form-control price" value=""></td>' +
            '</tr>'
        );
    });

    function collectCorrectiveData() {
        var prices = [];
        var errors = false;
        correctiveModal.find('table tbody tr').each(function () {
            $this = $(this);
            var players = $this.find('input.players').val();
            var price = $this.find('input.price').val();
            if (players && price) {
                prices.push({
                    "players": $this.find('input.players').val(),
                    "price": $this.find('input.price').val()
                });
            } else if ((!players && price) || (players && !price)) {
                errors = true;
                $this.addClass('has-error');
            }
        });
        if (errors) {
            return false;
        }
        return prices;
    }

    function sendNewCorrectiveData(dateTime, correctiveData) {
        $.ajax({
            type: "POST",
            url: adminCorrectivesRoute,
            data: {
                dateTime: dateTime,
                data: correctiveData
            }
        }).done(function (data) {
            window.location.replace(adminRoomsScheduleRoute);
        }).fail(function (data) {
            alert('Something went wrong, please contact the administrator.')
        });
    }


    function showSpinner(modal) {
        modal.find('button').addClass('hidden');
        modal.find('i').removeClass('hidden');
        modal.find('input').attr('disabled', true);
    }

    function removeHasErrorClass(modal) {
        setTimeout(function () {
            var err = modal.find('.has-error');
            if (err) {
                err.removeClass('has-error', 5000);
            }
        }, 5000);
    }

    $('[data-target="#previewModal"]').click(function () {
        $('#previewModal .modal-body .row div').html($(this).closest('div').find('textarea').val());
    });
});

