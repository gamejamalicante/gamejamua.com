jQuery.fn.center = function (absolute) {
    return this.each(function () {
        var t = jQuery(this);

        t.css({
            position:	absolute ? 'absolute' : 'fixed',
            left:		'50%',
            zIndex:		'99'
        }).css({
            marginLeft:	'-' + (t.outerWidth() + t.innerWidth()) + 'px'
        });

        if (absolute) {
            t.css({
                marginLeft:	parseInt(t.css('marginLeft'), 10) + jQuery(window).scrollLeft()
            });
        }
    });
};