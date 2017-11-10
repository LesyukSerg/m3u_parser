/**
 * Created by seran on 26.10.2017.
 */

function uploadFile(elem) {
    var fd = new FormData(document.getElementById("load_file"));
    fd.append('ajax', 1);
    fd.append('file', elem[0].files[0]);

    $.ajax({
        url: "/process.php",
        type: "POST",
        data: fd,
        enctype: 'multipart/form-data',
        processData: false,  // tell jQuery not to process the data
        contentType: false,   // tell jQuery not to set contentType
        success: function(data) {
            location.reload();
        }
    });
}

function process_start() {
    var song = $('.song-item:visible').first();
    if (song.length) {
        song.removeClass("alert-info").addClass("alert-warning");
        process(song, 1);
    } else {
        $('.song-container').append('<div class="alert alert-success"><strong>OK</strong> All songs downloaded</div>');
    }
}

function process(song, recurs) {
    if (!stop) {
        if (!song.hasClass("alert-success")) {
            var songID = song.data('id');

            $.ajax({
                url: "/process.php",
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
        $('.process').removeClass('disabled');
        alert("Process was stopped");
    }
}