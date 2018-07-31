(function($){$(document).ready(function(){

    // ****************************** //
    // Output field
    // ****************************** //

    $('output').each(function(){

        var input = $(this).attr('for');
        var inputVal = $('#'+input).val()
        
        // alert(inputVal);
        $(this).val(inputVal);

    });
    

    // ****************************** //
    // Textaera autosize
    // ****************************** //

    autosize($('textarea.ppm-control.autosize'));

    
});}(jQuery));