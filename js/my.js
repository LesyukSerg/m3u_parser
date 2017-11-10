var stop = 1;

$(document).ready(function () {
    $('.download').on('click', function(){
        $('.playlist').click();
    });

    $('.process').on('click', function(){
        if (!$(this).hasClass("disabled")) {
            stop = 0;
            $(this).addClass('disabled');
            process_start();
        }
    });

    $('.stop').on('click', function(){
        stop = 1;
    });
});