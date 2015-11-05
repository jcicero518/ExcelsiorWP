//
var _instance = jQuery(".slick-slider").slick('getSlick');
var _init = jQuery(".slick-slider").slick('init');
var _reinit = jQuery(".slick-slider").slick('reinit');
var _reload = jQuery(".slick-slider").slick();
var _destroy = jQuery(".slick-slider").slick('unslick');

// options
var _getoption = jQuery(".slick-slider").slick('getOption');
var _setoption = jQuery(".slick-slider").slick('option', 'value', 'refresh');
// getCurrent
// destroy
// cleanUpEvents

// buildRows - uses:
/*
_.$slider.html(newSlides);
            _.$slider.children().children().children()
                .css({
                    'width':(100 / _.options.slidesPerRow) + '%',
                    'display': 'inline-block'
                });
  */
