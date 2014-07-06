function addMediaForm(collectionHolder, newLinkLi, deleteLink) {
    deleteLink = typeof deleteLink !== 'undefined' ? deleteLink : true;

    var prototype = collectionHolder.data('prototype');
    var index = collectionHolder.data('index');

    var newForm = prototype.replace(/__name__/g, index);

    collectionHolder.data('index', index + 1);

    var newFormLi = $('<li class="element" id="image-upload-element-' +index+'"></li>').append(newForm);
    newLinkLi.before(newFormLi);

    if(deleteLink)
        addMediaDeleteLink(newFormLi);

    $('select.select2').select2();
    ThraceMedia.imageUpload($('#image-upload-element-' + index + ' .thrace-image-upload'));
    $('.ui-dialog').center(false);
}

function addMediaDeleteLink(mediaRemove) {
    var removeFormA = $('<a href="#" class="remove_link btn btn-logout btn-small">Eliminar</a>');

    mediaRemove.prepend(removeFormA);

    removeFormA.on('click', function(e) {
        e.preventDefault();

        if(confirm('¿Seguro que quieres eliminar este elemento?'))
            mediaRemove.remove();
    });
}

function loadMediaForm(target, addText)
{
    var addMediaLink = $('<a href="#" class="btn btn-green btn-small"><i class="fa fa-picture-o"></i> ' + addText+ '</a>');
    var newLinkLi = $('<span class="add_link"></span>').append(addMediaLink);

    var mediaCollectionHolder = $(target);

    mediaCollectionHolder.append(newLinkLi);
    mediaCollectionHolder.data('index', mediaCollectionHolder.find(':input').length);

    mediaCollectionHolder.find('li.element').each(function() {
        addMediaDeleteLink($(this));
    });

    addMediaLink.on('click', function(e) {
        e.preventDefault();
        addMediaForm(mediaCollectionHolder, newLinkLi);
    });
}

$(document).ready(function()
{
    $('select.select2').select2();
    loadMediaForm('.media', 'Añadir imagen / vídeo');
    loadMediaForm('.downloads', 'Añadir descarga');
});