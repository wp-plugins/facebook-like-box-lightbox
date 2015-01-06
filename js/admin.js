jQuery(document).ready(function() {
  	jQuery('#save_settings').submit(function(){
      return $(this).find('input:text').val() !== "";
    });
    
    jQuery('#tab-fblike-detail').hide();
    
    jQuery('.tabs').click(function () {
      jQuery('.tab_container').hide();
      jQuery('#tab-'+jQuery(this).attr('id')).show();
        
    
    });
});