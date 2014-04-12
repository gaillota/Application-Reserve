$(function(){
    var $label = $('select + label');
    $label.each(function(){
        var $this = $(this);
        $this.text($this.prev().find("option:selected").text());
    });

    $('select').change(function(){
        var $this = $(this);
        var $label = $this.next();

        $label.text($this.find("option:selected").text());
    });
});