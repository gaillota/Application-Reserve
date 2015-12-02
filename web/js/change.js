$(function() {
    var changeQuantity = function() {
        var $this = $(this);
        var formerValue = $this.val();
        $this.select();
        $this.val('');

        var done = function() {
            var $changeUrl = $this.data('change');
            var value = $this.val().trim();

            if(value == formerValue || value === '') {
                $this.val(formerValue);
                return;
            }

            var $spinner = $this.siblings('.fa-spinner');
            $spinner.show();
            $.get($changeUrl, { quantity: value })
                .always(function() {
                    $spinner.hide();
            });
        };

        $this.blur(function() {
            done();
        });

        $this.keypress(function(e) {
            if (e.which == 13 || e.keyCode == 13) {
                $this.blur();
            }
        });
    };

    $('[data-change]').focus(changeQuantity);
});