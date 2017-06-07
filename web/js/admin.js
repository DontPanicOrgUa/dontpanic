$(function () {

    var timepicker;
    var blankModal = $('#blank-modal');
    var priceModal = $('#price-modal');
    var blankTable = $('#blank-table');

    $('#btn-add-blank').on('click', function () {
        timepicker = initTimepicker();
        timepicker.new = true;
    });

    blankModal.on('hidden.bs.modal', function () {
        timepicker = {};
        $('input.timepicker').remove();
        blankModal.find('.modal-body').append('<input type="text" class="form-control timepicker">');
    });

    blankTable.on('click', '.blank-delete', function (e) {
        e.preventDefault();
        sendDeleteBlank(this);
    });

    blankTable.on('click', '.blank-edit', function () {
        timepicker = initTimepicker($(this).closest('.btn-group').find('button span:first').html());
        timepicker.new = false;
        timepicker.blankId = $(this).closest('tr').data('blank-id');
    });

    blankModal.on('click', '.save', function () {
        if (timepicker.new) {
            sendAddBlank(this);
            return;
        }
        sendEditBlank(this);
    });

    function initTimepicker(time = '00:00') {
        return $('input.timepicker').wickedpicker({
            now: time,
            twentyFour: true, //Display 24 hour format, defaults to false
            timeSeparator: ':',
            title: 'Select time'
        });
    }

    function sendAddBlank(btn) {
        var $btn = $(btn).button('loading');
        var ajax = $.ajax({
            url: admin_blanks_save_url,
            type: 'POST',
            data: {
                'time': timepicker.wickedpicker('time')
            }
        });
        ajax.done(function (data) {
            blankTable.find('tbody').append('<tr data-blank-id="' + data.blank.id + '"><td><div class="btn-group"><button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span>' + data.blank.time + '</span> <span class="caret"></span></button><ul class="dropdown-menu"><li><a href="#" class="fa fa-pencil blank-edit" data-toggle="modal" data-target="#blank-modal"> Edit</a></li><li><a href="#" class="fa fa-close blank-delete"> Delete</a></li></ul></div></td><td><a href="#" class="btn btn-xs btn-success fa fa-plus" data-toggle="modal" data-target="#price-modal"> add price</a></td><td><a href="#" class="btn btn-xs btn-success fa fa-plus" data-toggle="modal" data-target="#price-modal"> add price</a></td><td><a href="#" class="btn btn-xs btn-success fa fa-plus" data-toggle="modal" data-target="#price-modal"> add price</a></td><td><a href="#" class="btn btn-xs btn-success fa fa-plus" data-toggle="modal" data-target="#price-modal"> add price</a></td><td><a href="#" class="btn btn-xs btn-success fa fa-plus" data-toggle="modal" data-target="#price-modal"> add price</a></td><td><a href="#" class="btn btn-xs btn-success fa fa-plus" data-toggle="modal" data-target="#price-modal"> add price</a></td><td><a href="#" class="btn btn-xs btn-success fa fa-plus" data-toggle="modal" data-target="#price-modal"> add price</a></td></tr>');
            $btn.button('reset');
            blankModal.modal('hide');
        });
    }

    function sendEditBlank(btn) {
        var $btn = $(btn).button('loading');
        var ajax = $.ajax({
            url: admin_blanks_save_url + '/' + timepicker.blankId,
            type: 'PUT',
            data: {
                'time': timepicker.wickedpicker('time')
            }
        });
        ajax.done(function (data) {
            $('tr[data-blank-id=' + data.blank.id + ']').find('button span:first').html(data.blank.time);
            $btn.button('reset');
            blankModal.modal('hide');
        });
    }

    function sendDeleteBlank(btn) {
        if (confirm('You will not be able to recover data!')) {
            $(btn).closest('.btn-group').find('button').button('loading');
            var row = $(btn).closest('tr');
            var ajax = $.ajax({
                url: admin_blanks_save_url + '/' + row.data('blank-id'),
                type: 'DELETE'
            });
            ajax.done(function (data) {
                row.remove();
            });
        }
    }

});
