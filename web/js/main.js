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
  
  //бронирование игры (заполняем название, время, стоимость)
//  $('#reservation').on('show.bs.modal', function (e) {
//    if ($(e.relatedTarget).hasClass('t-active')) {
//      e.preventDefault();
//    }
//    if ($('.room-title').text()) {
//      $('.room-name').text('"' + $('.room-title').text() + '"');
//    } else {
//      $('.room-name').text('"' + $(e.relatedTarget).parents('.schedule').children('h2').children('a').text() + '"');
//    }
//    $('.room-time').text('Время: ' + $(e.relatedTarget).children('.game-time').text());
////    $('.room-price').text('Стоимость: ' + $(e.relatedTarget).children('.game-price').text() + ' грн');
//    
//    $('#reservation').submit(function () {
//      $(e.relatedTarget).removeClass('t-info t-warning t-success t-danger').addClass('t-active');
//    });
//  });
  
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
    onHover: function(currentIndex, currentRating, $el){
      $('.atmosphere-rating').text(currentIndex);
    },
    onLeave: function(currentIndex, currentRating, $el){
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
    onHover: function(currentIndex, currentRating, $el){
      $('.plot-rating').text(currentIndex);
    },
    onLeave: function(currentIndex, currentRating, $el){
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
    onHover: function(currentIndex, currentRating, $el){
      $('.service-rating').text(currentIndex);
    },
    onLeave: function(currentIndex, currentRating, $el){
      $('.service-rating').text(currentRating);
    }
  });
});

