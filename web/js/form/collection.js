$(function(){
    $('[data-prototype]').data('index', $('form table.product tr').length-1)

        .click(function(e){
            e.preventDefault();

            var $this = $(this);
            $this.data('index', $this.data('index')+1);
            var prototype = $this.data('prototype').replace(/__name__/g, $this.data('index'));
            var productSelect = $(prototype).find('select#ferus_productbundle_previs_previs_products_'+$this.data('index')+'_product').addClass('form-control').wrap('<p>').parent().html();
            var quantityInput = $(prototype).find('input#ferus_productbundle_previs_previs_products_'+$this.data('index')+'_quantity').addClass('form-control').wrap('<p>').parent().html();

            //var html = $this.data('prototype').replace(/__name__/g, $this.data('index'));

            var $container = $('<tr><td>'+($this.data('index')+1)+'</td><td>'+productSelect+'</td><td>'+quantityInput+'</td><td class="text-center"><button type="button" class="btn btn-danger" data-remove-widget><i class="fa fa-remove"></i></button></td></tr>');
            $('form table.product').append($container);
            $container.find('[data-remove-widget]').click(function(e){
                e.preventDefault();
                var $this = $(this);
                $this.parents('tr').remove();
            });

            //$this.before($container);
        });

    $('[data-remove-widget]').click(function(e){
        e.preventDefault();
        var $this = $(this);
        $this.parents('tr').remove();
    });

    $('#ferus_productbundle_previs_form').on('submit', function(e) {
        e.preventDefault();
        confirmForm($(this)[0]);
    });

    function confirmForm($form)
    {
        if(confirm('Êtes-vous sûr d\'avoir fini votre prévis ?')) {
            $form.submit();
        }
    }
});
