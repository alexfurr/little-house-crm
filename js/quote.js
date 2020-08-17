jQuery(document).ready(function() {

    // Run the initial calculation in case of page relaod / saved data
    update_quote_calculator();

    jQuery('#lh_quote_calculator').on('change keyup paste', ':input', function(e) {
        // The form has been changed. Your code here.

        update_quote_calculator();


    });
});

function update_quote_calculator()
{
    var total_cost = 0;        console.log("called123");


    // go through each element and recalculate quote price
    jQuery('input', jQuery('#lh_quote_calculator')).each(function () {
        var this_id = this.id;
        var this_value = this.value;
        var this_unit = jQuery( this ).data("unit");
        var cost = jQuery( this ).data("cost");
        var update_div = "subtotal_"+this_id;
        var this_subtotal = '';


        switch(this_unit) {
            case "fixed":
                var this_subtotal = this_value;
            break;
            case "each":
            case "sqm":
                var this_subtotal = (this_value*cost);
            break;

        }


        // Add the + before hand so we use unary
        // see https://stackoverflow.com/questions/8976627/how-to-add-two-strings-as-if-they-were-numbers
        total_cost = +total_cost + +this_subtotal;
        jQuery("#"+update_div).text("£"+this_subtotal);

    });

    // finally add the total cost
    jQuery("#quote_total").val( total_cost );
}
