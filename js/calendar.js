
var MBM_CALENDAR = {

    //---
    wrapper_id:    'calendar_wrap',

    //---
    init: function () {
        this.add_listeners();
    },

    //---
    add_listeners: function () {
        jQuery('#' + MBM_CALENDAR.wrapper_id ).on( 'click', '.has-click-event', function ( event ) {
            MBM_CALENDAR.on_ui_event( event, this );
            event.preventDefault();
        });

    },

    //---
    on_ui_event: function ( event, element ) {
        var method = jQuery( element ).attr('data-method');
        if ( typeof MBM_CALENDAR[ method ] !== 'undefined' ) {
            MBM_CALENDAR[ method ]( event, element );
        }
    },

    // List of actual interactions
   //---
   load_cal_month: function ( event, element ) {
       var month = jQuery( element ).attr('data-month');
       var year = jQuery( element ).attr('data-year');

       jQuery.ajax({
   		type: 'POST',
   		url: mbm_ajax_params.ajaxurl,
   		data: {
   			"action"		: "draw_calendar",
   			"month"			: month,
   			"year"			: year,
   			"security"		: mbm_ajax_params.ajax_nonce
   		},
   		success: function(data)
   		{

   			document.getElementById('calendar_wrap').innerHTML = data;
   		}
   	});
   },





};


jQuery( document ).ready( function () {
    MBM_CALENDAR.init();
});
