/**
 * Created by seran on 26.10.2017.
 */

function uploadFile(elem) {
    var fd = new FormData(document.getElementById("load_file"));
    fd.append('ajax', 1);
    fd.append('file', elem[0].files[0]);

    $.ajax({
        url: window.location.pathname + "process.php",
        type: "POST",
        data: fd,
        enctype: 'multipart/form-data',
        processData: false,  // tell jQuery not to process the data
        contentType: false,   // tell jQuery not to set contentType
        success: function (data) {
            if (data === "1") {
                location.reload();
            } else {
                console.log(data);
            }
        }
    });
}

function process_start() {
    var total = $('.song-item').length;
    var left = $('.song-item:visible').length;
    var complete = 100 - (left / total) * 100;
    $('.progress-bar').css('width', complete + '%');

    var song = $('.song-item:visible').first();
    if (song.length) {
        song.removeClass("alert-info").addClass("alert-warning");
        process(song);
    } else {
        $('.song-container').append('<div class="alert alert-success"><strong>OK</strong> All songs downloaded. </div>');
        $.ajax({
            url: window.location.pathname + "process.php",
            type: "POST",
            data: {
                ajax: 1,
                action: 'complete'
            },
            success: function (data) {
                data = data.split("|");

                if (data[0] === "OK") {
                    $('.song-container .alert-success').append('<a target="_blank" href="' + data[2] + '">Download Album <b>' + data[1] + '</b></a>');
                } else {
                    alert(data);
                }

                $('.loader').fadeOut();
            }
        });
    }
}

function process(song) {
    if (!stop) {
        if (!song.hasClass("alert-success")) {
            var songID = song.data('id');

            $.ajax({
                url: window.location.pathname + "process.php",
                type: "POST",
                data: {
                    ajax: 1,
                    action: 'getSong',
                    song: songID
                },
                success: function (data) {
                    if (parseInt(data) > 0) {
                        song.removeClass("alert-warning").addClass("alert-success");
                        song.fadeOut(400, process_start);
                    } else {
                        alert(data);
                    }
                }
            });
        } else {
            song.removeClass("alert-warning").addClass("alert-success");
            song.fadeOut(400, process_start);
        }
    } else {
        $('.loader').fadeOut();
        $('.process').removeClass('disabled');
        alert("Process was stopped");
    }
}