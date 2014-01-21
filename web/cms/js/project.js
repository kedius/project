function flashbag(type, message) {
    var id = 'flashbag-' + (new Date()).getTime();
    if (type == 'error') {
        var header = 'Ошибка';
    } else if (type == 'notice') {
        var header = 'Внимание';
    } else if (type == 'success') {
        var header = 'Выполнено';
    }
    var modal = '<div id="'+id+'" class="flashbag '+type+'">\n\t<strong>'+header+'</strong>\n\t<ul>\n';
    for (i in message) {
        modal+= '\t\t<li>'+message[i]+'</li>\n';
    }
    modal+= '\t</ul>\n</div>';
    $('#modal-place').append(modal);
    $('#'+id).show('slow');
    setTimeout('removeFlashbug(\''+id+'\')', 7000);
}

function removeFlashbug(id) {
    $('#'+id).hide('slow', function() {
        $(this).remove();
    })
}


function rateUp(ID, type, item) {
    $.ajax({
        type: "POST",
        url: asset+'/ajax/rate',
        data: {type: type, id: ID, rate: 'up'},
        success: function() {
            var items = $(item).parent();
            items.children('#rating').text(Number(items.children('#rating').text()) + 1);
            if (items.children('#down').hasClass('rated')) {
                items.children('#down').removeClass('rated');
            } else {
                $(item).addClass('rated');
                $(item).removeAttr('onclick');
            }
            items.children('#down').attr('onclick', 'rateDown('+ID+', \''+type+'\', this)');
        },
        dataType: 'json'
    });
}

function rateDown(ID, type, item) {
    $.ajax({
        type: "POST",
        url: asset+'/ajax/rate',
        data: {type: type, id: ID, rate: 'down'},
        success: function() {
            var items = $(item).parent();
            items.children('#rating').text(Number(items.children('#rating').text()) - 1);
            if (items.children('#up').hasClass('rated')) {
                items.children('#up').removeClass('rated');
            } else {
                $(item).addClass('rated');
                $(item).removeAttr('onclick');
            }
            items.children('#up').attr('onclick', 'rateUp('+ID+', \''+type+'\', this)');
        },
        dataType: 'json'
    });
}

