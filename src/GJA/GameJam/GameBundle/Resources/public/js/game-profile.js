function loadSlideshow(target, startAt)
{
    var carouselElement = $(target);
    var carouselItems = carouselElement.children();
    var carousel = carouselElement.elastislide({
        current : startAt,
        minItems : 4,
        onClick: function(element, position, evt)
        {
            carouselItems.removeClass('current-img');
            changeImage(carousel, element, position);
            evt.preventDefault();

        },
        onReady: function()
        {
            carouselItems.removeClass('current-img');
            changeImage(carousel, carouselItems.eq(startAt), startAt);

        }
    });
}

function changeImage(carousel, element, position)
{
    if(element.data('type') == 2 || element.data('type') == 3)
    {
        // youtube video
        $('.image-preview').html('<iframe class="youtube" width="100%" height="300" src="//www.youtube.com/embed/' + element.data('youtube-id') + '" frameborder="0" allowfullscreen></iframe>');
    }
    else
    {
        var imagePreview = $('<img id="preview" />');
        $('.image-preview').html(imagePreview);

        imagePreview.attr('src', element.data('preview'));
    }
    element.addClass('current-img');
    carousel.setCurrent(position);
}