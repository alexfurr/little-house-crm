jQuery(document).ready(function() {


        jQuery(".quote_checklist_item").change(function(){

            if (jQuery('.quote_checklist_item:checked').length == jQuery('.quote_checklist_item').length) {
               // all are checked
               jQuery( "#quote_accept_link" ).removeClass( "disabled_link" )
            }
            else
            {
                jQuery( "#quote_accept_link" ).addClass( "disabled_link" )
            }
        });
});
