function get_products(filter = ''){
	console.log('filter: '+filter);
	 jQuery.ajax({
            type : "GET",
            dataType : "json",
            url : ajaxurl,
            beforeSend: function(){
            		jQuery('.custom-loader').css('display','block');
            },
            complete: function() {
		      jQuery('.custom-loader').css('display','none');
		    },
            data : {action: "get_products",filter:filter},
            success: function(data) {
           		jQuery('#products').html(data.response);
            }
        });	
}

/* On change Filter */
jQuery(document).on('change','#products_filter',function (){
	console.log(jQuery(this).val());
	get_products(jQuery(this).val());
})