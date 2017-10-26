/**
 * Created by seran on 26.10.2017.
 */

function uploadFile(elem) {
    //var block = elem.parent();
    //block.find('.wait_for_checking').show();
    var fd = new FormData(document.getElementById("load_file"));
    //fd.append('num', elem.attr('data-num'));
    fd.append('file', $("input[name='playlist']")[0].files[0]);

    $.ajax({
        url: window.location.href,
        type: "POST",
        data: fd,
        enctype: 'multipart/form-data',
        processData: false,  // tell jQuery not to process the data
        contentType: false   // tell jQuery not to set contentType
    }).success(function (data) {
        //block.find('.wait_for_checking').hide();
        //data = data.split('|');

        if (data[0] != 'OK') {
            alert(data);
            block.find("input[name='price_list']").val('');
        } else {
            var form = block.find('fieldset');
            form.find('a, span').remove();
            form.find('legend').html("Прайс " + elem.attr('data-num') + " загружен:");
            form.append('<a class="file-icon" href="' + data[1] + '">' + data[2] + '</a><span class="dell_price_list f-left" title="удалить">&#10006;</span>');
            form.find('.google-button').addClass('f-right');
        }
    });
}