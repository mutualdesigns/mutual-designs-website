/*
	Vidtok.co created by Blacc Spot Media, Inc.
	Copyright 2011 All Rights Reserved.
	Author: the Blacc Spot Media team
	www.blaccspot.com
/*---------------------------*/




/*  VIDEO URL
/*---------------------------*/
	
	jQuery(document).ready(function(e) {
        
		/*FULL URL*/
			var url = window.location.href;
			
		/*SHARE LINK*/	
			jQuery('#vidtok-share-link').val(url);
				
    });


/*  SHARE WATERMARK
/*---------------------------*/
	
	jQuery(document).ready(function(e) {
        
		jQuery('#vidtok-email').watermark('Enter email address');

				
    });	
	

/*  SHARE PANEL
/*---------------------------*/
	
	jQuery(document).ready(function(e) {
		
		/*SHARE PANEL*/
			jQuery('.vidtok-share-panel-handle').bind('click', function(){
				
				jQuery('#vidtok-share-panel').animate({
					
					left : '-30px'
					
				},600);
				
			});
			
			jQuery('#vidtok-share-panel').bind('mouseleave', function(){
		
				jQuery('#vidtok-share-panel').animate({
					
					left : '-572px'
				
				}, 400);		
				
			});			
		
		
	});



/*  VIDTOK TOOL PANEL
/*---------------------------*/
	
	jQuery(document).ready(function() {
		
		/*OPEN PANEL*/	
			jQuery('.vidtok-tool-panel-handle').bind('click', function(){
				
				jQuery('#vidtok-tool-panel').animate({
					
					right : '-360px'
					
				},600, function(){ jQuery('.vidtok-tool-panel-dialog').hide(); });
				
			});
		
		
		/*CLOSE PANEL*/
			jQuery('#vidtok-tool-panel').bind('mouseleave', function(){
		
				jQuery('#vidtok-tool-panel').animate({
					
					right : '-780px'
				
				}, 400, function(){ jQuery('.vidtok-tool-panel-dialog').hide(); });		
				
			});
			
        
		/*REFRESH*/
			jQuery('#vidtok-tools-refresh').click(function(){
				
				location.reload();
				
			});


		/*NETWORK CONNECTION*/
			jQuery('#vidtok-tools-network').bind('click', function(){
				
				jQuery('.vidtok-tool-panel-dialog-c').hide();
				
				if(!jQuery('.vidtok-tool-panel-dialog').is(':visible')){ 
				
					jQuery('.vidtok-tool-panel-dialog').toggle();
				
				}
				
				jQuery('#vidtok-dialog-network').toggle();
				
			});


		/*SETTINGS*/
			jQuery('#vidtok-tools-settings').bind('click', function(){
				
				jQuery('.vidtok-tool-panel-dialog-c').hide();
				
				if(!jQuery('.vidtok-tool-panel-dialog').is(':visible')){ 
				
					jQuery('.vidtok-tool-panel-dialog').toggle();
				
				}
				
				jQuery('#vidtok-dialog-settings').toggle();
				
			});			
			
		
    });


/*  VALIDATE EMAIL
/*---------------------------*/

	function isValidEmailAddress(email) {
		var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
		return pattern.test(email);
	}




