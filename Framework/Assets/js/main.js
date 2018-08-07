(function($){$(document).ready(function(){
    

    // ****************************** //
    // Collection
    // ****************************** //

    // $('[data-collection]').each(function(){
    //     console.log( $(this) );
    // });

    console.log( $('[data-collection]') );


    // ****************************** //
    // Output field
    // ****************************** //

    $('output').each(function(){

        var inputs = $(this).attr('for').split(' ');
        var output = $(this);

        outputCalculation(inputs, output);
        
        $.each(inputs, function(index, element) {
            $('#'+element).on('change', function() {
                outputCalculation(inputs, output);
            });
        });
    });

    function outputCalculation(inputs, output) 
    {
        var sum = 0;

        $.each(inputs, function(index, element) {
            var val = parseInt($('#'+element).val());
                val = isNaN(val) ? 0 : val;

            sum += val;
        });
        output.val(sum);
    }
    

    // ****************************** //
    // Textaera autosize
    // ****************************** //

    autosize($('textarea.ppm-control.autosize'));

    
});}(jQuery));