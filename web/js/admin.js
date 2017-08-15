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
            lastName: bookingModal.find('input[name=lastName]').val(),
            email: bookingModal.find('input[name=email]').val(),
            phone: bookingModal.find('input[name=phone]').val(),
            discount: bookingModal.find('input[name=discount]').val(),
            bookedBy: userRole
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
        if (!data.lastName) {
            bookingModal.find('.input-group').has('input[name=lastName]').addClass('has-error', 1000);
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
        }).done(function (data) {
            // resetBookingModal();
            window.location.replace(adminRoomsScheduleRoute);
        }).fail(function (data) {
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
                '<td><input type="number" class="form-control price" value="' + this.price + '"></td>' +
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

    ////////////////////////////////////////////////////////////////////////
    //////////////// toggle game info //////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////
    $(function () {
        $('.more').click(function () {
            $(this).next('div').slideToggle();
        });
    });

    ////////////////////////////////////////////////////////////////////////
    //////////////// modal-comment /////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////
    var $modalComment = $('#modal-comment');

    $('[data-target="#modal-comment"]').click(function () {
        $modalComment.find('.modal-body').html($(this).data('comment'));
    });

    ////////////////////////////////////////////////////////////////////////
    //////////////// modal-photo /////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////
    var $modalPhoto = $('#modal-photo');

    $('[data-target="#modal-photo"]').click(function () {
        $modalPhoto.find('.modal-body img').attr('src', $(this).data('photo'));
    });

    ////////////////////////////////////////////////////////////////////////
    //////////////// modal-view-images /////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////
    var $modalViewImages = $('#modal-view-images');

    $('[data-target="#modal-view-images"]').click(function () {
        var image = $(this).data('src');
        if ($.isArray(image)) {
            $.each(image, function (k, v) {
                $modalViewImages.find('.modal-body').append(
                    '<img src="' + v + '" style="max-width: 100%; max-height: 400px;">'
                );
            })
        } else {
            $modalViewImages.find('.modal-body').append(
                '<img src="' + image + '" style="max-width: 100%; max-height: 400px;">'
            );
        }
        // $modalViewImages.find('.modal-body');
    });

    $modalViewImages.on('hide.bs.modal', function () {
        $modalViewImages.find('img').remove();
    });

    ////////////////////////////////////////////////////////////////////////
    //////////////// preview image /////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////
    var $inputFiles = $('input[type="file"]');

    function cleanPreviews(input) {
        $(input).closest('div').find('div.previews').remove();
    }

    function buildPreviews(input) {
        if (input.files) {
            $(input).closest('div').append($('<div>', {class: 'previews'}));
            $.each(input.files, function (k, v) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $(input).closest('div').find('.previews').append(
                        $('<img>', {src: e.target.result})
                            .css('max-width', '120px')
                            .css('max-height', '100px')
                            .css('margin', '4px')
                    );
                };
                reader.readAsDataURL(v);
            });
        }
    }

    function validateImages(input) {
        var error = '';
        if (input.files) {
            if (input.files.length > 10) {
                return '\nMax number of files uploaded 10';
            }
            $.each(input.files, function (k, v) {
                var imageType = 'JPG';
                var maxSize = 1024 * 1024 * 2;
                if ($(input).attr('accept') === 'image/png') {
                    imageType = 'PNG';
                    maxSize = 1024 * 1024;
                }
                if (v.type !== $(input).attr('accept')) {
                    error += '\nFile: ' + v.name + ' ' + (v.size / 1024 / 1024).toFixed(2) + 'MB';
                    error += '\n - type should be ' + imageType;
                    $(input).closest('div.file-container').find('p:eq(0)').addClass('custom-error');
                }
                if (v.size >= maxSize) {
                    error += '\nFile: ' + v.name + ' ' + (v.size / 1024 / 1024).toFixed(2) + 'MB';
                    error += '\n - size should be less than ' + (maxSize / 1024 / 1024) + 'MB';
                    $(input).closest('div.file-container').find('p:eq(1)').addClass('custom-error');
                }
            });
        }
        return error;
    }

    function cleanErrors(input) {
        $(input).closest('div.file-container').find('.custom-error').removeClass('custom-error');
    }

    $inputFiles.change(function () {
        cleanPreviews(this);
        cleanErrors(this);
        var error = validateImages(this);
        if (error) {
            $(this).val('');
            alert(error);
        } else {
            buildPreviews(this);
        }
    });
})
;

