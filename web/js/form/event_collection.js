$(function() {
    $('[data-prototype]').data('index', $('table.bars tr').length-1)
        .click(function(e) {
            e.preventDefault();

            var $this = $(this);
            $this.data('index', $this.data('index')+1);
            var prototype = $this.data('prototype').replace(/__name__/g, $this.data('index'));
            var barSelect = $(prototype).find('input').addClass('form-control').wrap('<p>').parent().html();
            var deleteButton = '<button type="button" class="btn btn-danger" data-remove-widget><i class="fa fa-times"></i></button>';
            var $newRow = $('<tr><td>'+barSelect+'</td><td class="text-center">'+deleteButton+'</td></tr>');
            var $container = $('table.bars');

            $container.append($newRow);
            $newRow.find('input').focus();

            $newRow.find('[data-remove-widget]').click(function(e) {
                e.preventDefault();

                $(this).parents('tr').remove();
            });
        });

    $('[data-remove-widget]').click(function(e) {
        e.preventDefault();

        $(this).parents('tr').remove();
    });
});