// function
function generateInputMask() {
    // https://robinherbots.github.io/Inputmask/#/documentation
    $(".input-mask-trigger").inputmask();
}

function generateTextareaAutosize() {
    $('textarea.autosize-input').textareaAutoSize();
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
});
