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