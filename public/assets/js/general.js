// function
function generateInputMask() {
    // https://robinherbots.github.io/Inputmask/#/documentation
    $(".input-mask-trigger").inputmask();
}

function generateTextareaAutosize() {
    $('textarea.autosize-input').textareaAutoSize();
}

function initShowMore(selector = '.show-more-container', maxHeight = 60, showMoreText = 'Show more', showLessText = showMoreText) {
    $(selector).each(function() {
        var $container = $(this);
        var $text = $container.find('.show-more-text');
        var $link = $container.find('.show-more-link');

        // Reset state
        $text.css('max-height', maxHeight + 'px');
        $link.text(showMoreText);

        $link.off('click').on('click', function() {
            if ($text.css('max-height') === maxHeight + 'px') {
                $text.css('max-height', 'none');
                $link.text(showLessText);
            } else {
                $text.css('max-height', maxHeight + 'px');
                $link.text(showMoreText);
            }
        });
    });
}

function generateScrollbar() {
    if ($(".scrollbar-container")[0]) {

        $('.scrollbar-container').each(function () {
            const ps = new PerfectScrollbar($(this)[0], {
                wheelSpeed: 2,
                wheelPropagation: false,
                minScrollbarLength: 20
            });
        });
    }
}

// event
$('.modal').on('show.bs.modal', function (e) {
    let fadeIn = $(this).data('animate-in');
    let fadeOut = $(this).data('animate-out');;
    if (fadeOut !== undefined && fadeIn !== undefined) {
        $('.modal .modal-dialog').removeClass(fadeOut);
        $('.modal .modal-dialog').addClass(fadeIn);
    }
});

$('.modal').on('hide.bs.modal', function (e) {
    let fadeIn = $(this).data('animate-in');
    let fadeOut = $(this).data('animate-out');
    if (fadeOut !== undefined && fadeIn !== undefined) {
        $('.modal .modal-dialog').removeClass(fadeIn);
        $('.modal .modal-dialog').addClass(fadeOut);
    }
});

// document ready
$( document ).ready(function() {

    setTimeout(function () {

        if ($(".scrollbar-container")[0]) {

            $('.scrollbar-container').each(function () {
                const ps = new PerfectScrollbar($(this)[0], {
                    wheelSpeed: 2,
                    wheelPropagation: false,
                    minScrollbarLength: 20
                });
            });

            const ps = new PerfectScrollbar('.scrollbar-sidebar', {
                wheelSpeed: 2,
                wheelPropagation: true,
                minScrollbarLength: 20
            });

        }

    }, 1000);

    $(".input-mask-trigger").inputmask();

    $('textarea.autosize-input').textareaAutoSize();

    initShowMore();
});
