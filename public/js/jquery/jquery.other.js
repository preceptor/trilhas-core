(function($){
    $.fn.other = function() {
        return this.each(function(){
            $(this).append('<option value="add">[outro]</option>')
                   .change(function(){
                       if (this.value == 'add') {
                           $(this).after('<input name="'+this.name+'" />')
                                  .attr('disabled', true)
                                  .hide();
                       }
                   });
        });
    }
})(jQuery);