$(function () {
    var bookingModal = $('#bookingModal');

    $('.book-game').click(function (e) {
        e.preventDefault();
        var pricesList = $(this).closest('td').find('.hidden-prices').html();
        bookingModal.find('.price-list').html(pricesList);
    });

    bookingModal.find('button.btn-primary').click(function () {
        var date = bookingModal.find('.game-date span').html();
        var time = bookingModal.find('.game-time span').html();
        var price = bookingModal.find('input[name=price]:checked').val();
        console.log(date);
        console.log(time);
        console.log(price);
    })
});
