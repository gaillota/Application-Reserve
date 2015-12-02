$(function(){
    //$('[data-remove]').prepend('<a class="remove-icon"><i class="fa fa-minus-circle"></i></a>');
    //
    //$('[data-toggle=remove]').click(function(){
    //    var $this = $(this);
    //    $this.toggleClass('active');
    //
    //    $('[data-remove]').toggleClass('remove-active');
    //});
    //
    //$('.remove-icon').click(function(){
    //    var $item =  $(this).parent();
    //    var removeUrl = $item.data('remove');
    //
    //    $.get(removeUrl);
    //    $item.animate({opacity:0}, 400, function(){
    //        $item.remove();
    //    });
    //});
});
$(function(){
    //var $label = $('select + label');
    //$label.each(function(){
    //    var $this = $(this);
    //    $this.text($this.prev().find("option:selected").text());
    //});

    //$('select').change(function(){
    //    var $this = $(this);
    //    var $label = $this.next();
    //
    //    $label.text($this.find("option:selected").text());
    //});

    //$('.products_list li').click(function(e) {
    //    e.preventDefault();
    //
    //    $(this).children('.details').toggleClass('active');
    //});
});
$(function() {

    var changeQuantity = function() {
        var $this = $(this);
        $this.select();
        var formerValue = $this.val();
        $this.val('');

        var done = function() {
            var $changeUrl = $this.data('change');
            var value = $this.val().trim();

            if(value == formerValue || value === '') {
                $this.val(formerValue);
                return;
            } else {
                var $spinner = $this.parent('td').find('.fa-spinner');
                $spinner.show();
                $.get($changeUrl, { quantity: value })
                    .always(function() {
                        $spinner.hide();
                    });
            }
        };

        $this.blur(function() {
            done();
        });

        $this.keypress(function(e) {
            if (e.which == 13 || e.keyCode == 13) {
                $(this).blur();
            }
        });
    };

    $('[data-change]').focus(changeQuantity);
});