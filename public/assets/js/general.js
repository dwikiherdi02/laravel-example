// constants
const swalConfirmOpt = {
    // title: null,
    text: "",
    confirmButtonText: "Ok",
    cancelButtonText: "Batal",
}

const swalConfirmWithTitle = Swal.mixin({
    showConfirmButton: true,
    showCancelButton: true,
    customClass: {
        popup: "p-0",
        title: "swal2-title-custom m-1 pt-4 pb-0",
        htmlContainer: "border-bottom pt-0 pb-4",
        // actions: "row m-0 w-100",
        // confirmButton: "btn btn-lg btn-link font-weight-bolder text-decoration-none col-6 m-0 py-3 rounded-0",
        // cancelButton: "btn btn-lg btn-link font-weight-normal text-decoration-none col-6 m-0 py-3 rounded-0 border-right",
        actions: "d-flex justify-content-between m-0 w-100",
        confirmButton: "btn btn-lg btn-link font-weight-bolder text-uppercase text-decoration-none w-50 m-0 py-3 rounded-0",
        cancelButton: "btn btn-lg btn-link font-weight-normal text-uppercase text-decoration-none w-50 m-0 py-3 rounded-0 border-right",
        loader: "mx-auto"
    },
    buttonsStyling: false,
    reverseButtons: true,
});

const swalConfirmWithoutTitle = Swal.mixin({
    showConfirmButton: true,
    showCancelButton: true,
    customClass: {
        popup: "p-0",
        htmlContainer: "border-bottom py-4",
        // actions: "row m-0 w-100",
        // confirmButton: "btn btn-lg btn-link font-weight-bolder text-decoration-none col-6 m-0 py-3 rounded-0",
        // cancelButton: "btn btn-lg btn-link font-weight-normal text-decoration-none col-6 m-0 py-3 rounded-0 border-right"
        actions: "d-flex justify-content-between m-0 w-100",
        confirmButton: "btn btn-lg btn-link font-weight-bolder text-uppercase text-decoration-none w-50 m-0 py-3 rounded-0",
        cancelButton: "btn btn-lg btn-link font-weight-normal text-uppercase text-decoration-none w-50 m-0 py-3 rounded-0 border-right",
        loader: "mx-auto"
    },
    buttonsStyling: false,
    reverseButtons: true,
});

const swalInfomWithTitle = Swal.mixin({
    showConfirmButton: true,
    showCancelButton: false,
    customClass: {
        popup: "p-0",
        title: "swal2-title-custom m-1 pt-4 pb-0",
        htmlContainer: "border-bottom pt-0 pb-4",
        actions: "d-flex justify-content-between m-0 w-100",
        confirmButton: "btn btn-lg btn-link font-weight-bolder text-uppercase text-decoration-none w-100 m-0 py-3 rounded-0",
        loader: "mx-auto"
    },
    buttonsStyling: false,
    reverseButtons: true,
});

const swalInfomWithoutTitle = Swal.mixin({
    showConfirmButton: true,
    showCancelButton: false,
    customClass: {
        popup: "p-0",
        htmlContainer: "border-bottom py-4",
        actions: "d-flex justify-content-between m-0 w-100",
        confirmButton: "btn btn-lg btn-link font-weight-bolder text-uppercase text-decoration-none w-100 m-0 py-3 rounded-0",
        loader: "mx-auto"
    },
    buttonsStyling: false,
    reverseButtons: true,
});

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

function showConfirmAlert(opt = {}) {
    const options = { ...swalConfirmOpt, ...opt };
    if (options.title === undefined) {
        return swalConfirmWithoutTitle.fire(options);
    } else {
        return swalConfirmWithTitle.fire(options);
    }
}

function showInfoAlert(opt = {}) {
    const options = { ...swalConfirmOpt, ...opt };
    if (options.title === undefined) {
        return swalInfomWithoutTitle.fire(options);
    } else {
        return swalInfomWithTitle.fire(options);
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
