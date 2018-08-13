(function($){$(document).ready(function(){
    

    // ****************************** //
    // Collection
    // ****************************** //

    var collections = new Object();

    $('[data-collection]').each(function(){
        var collection_name = $(this).data('collection');
        var collection_type = $(this).data('type');
        var collection_loop = $(this).data('loop');

        // Add the collection reference to the Collections object
        if ( undefined === collections[collection_name] ) {
            collections[collection_name] = new Object();
        }
        if ( undefined === collections[collection_name][collection_type] ) {
            collections[collection_name][collection_type] = $(this);
        }
        if ( undefined === collections[collection_name]['items'] ) {
            collections[collection_name]['items'] = 1;
        }
        if ( undefined === collections[collection_name]['loop'] && undefined != collection_loop) {
            collections[collection_name]['loop'] = collection_loop;
        }
    });

    $.each(collections, function(index, collection){
        // on load
        for (var i=0; i<collection.loop; i++) {
            collection_add_item(collection);
        }

        // on click
        collection.control.click(function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            collection_add_item(collection);
        });
    });

    function collection_add_item(collection) {

        collection.container.append(render_ppmci(collection.prototype, {
            number: collection.items
        }));

        var actions = collection.container.find('.ppm-collection-item-actions');
        var deleteBtn = collection.container.find('[data-action="delete"]');

        // Display / Hide actions
        (collection.items > 1) ? actions.removeClass('hidden') : actions.addClass('hidden');
        
        deleteBtn.click(function(e){
            e.preventDefault();
            e.stopImmediatePropagation();

            item = $(this).parents('.ppm-collection-item');
            item.remove();
        });

        collection.items++;
    }

    // render PPM Collection Item
    function render_ppmci(prototype, data) {
        
        var clone = prototype.clone();
        var html = clone.html();

        $.each(data, function(key, value){
            var regex = new RegExp('{{'+key+'}}', "g");
            html = html.replace(regex, value);
        });

        return html;
    }


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