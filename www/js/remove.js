$(function(){
    $('[data-remove]').prepend('<a class="remove-icon"><i class="fa fa-minus-circle"></i></a>');

    $('[data-toggle=remove]').click(function(){
        var $this = $(this);
        $this.toggleClass('active');

        $('[data-remove]').toggleClass('remove-active');
    });

    $('.remove-icon').click(function(){
        var $item =  $(this).parent();
        var removeUrl = $item.data('remove');

        $.get(removeUrl);
        $item.animate({opacity:0}, 400, function(){
            $item.remove();
        });
    });
});