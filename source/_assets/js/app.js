window.$ = window.jQuery = require('jquery')
require('bootstrap-sass');

$(document).scroll(function(e) {
    if ($('body').hasClass('page-home')) {
        return true;
    } else {
        var distanceY = window.pageYOffset || document.documentElement.scrollTop;
        headerScroll(distanceY, 200, document.querySelector("header"));
    }

});

function headerScroll(distanceY, shrinkOn, header) {
    if (distanceY > shrinkOn) {
        $('header').addClass('smaller');
        if ($('body').hasClass('page-blog-post')) {
            $('.jumbotron').addClass('smaller');
            $('.jumbotron-wrapper').addClass('smaller');
            $('.post-content').addClass('smaller');
        }
    } else {
        if ($('header').hasClass('smaller')) {
            $('header').removeClass('smaller');
        }
        if ($('body').hasClass('page-blog-post')) {
            if ($('.jumbotron').hasClass('smaller')) {
                $('.jumbotron').removeClass('smaller');
            }
            if ($('.jumbotron-wrapper').hasClass('smaller')) {
                $('.jumbotron-wrapper').removeClass('smaller');
            }
            if ($('.post-content').hasClass('smaller')) {
                $('.post-content').removeClass('smaller');
            }
        }
    }
}
