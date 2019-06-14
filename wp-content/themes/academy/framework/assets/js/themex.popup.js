jQuery(document).ready(function($) {
    var themexPopup={	
    	loadVals: function() {
			var shortcode=$(themexElements.shortcodeModule).find('form').children(themexElements.shortcodeModulePattern).html();
			var clones='';

    		$(themexElements.shortcodeModule).find('input, select, textarea').each(function() {
    			var id=$(this).attr('id'),
    				re=new RegExp('{{'+id+'}}','g');
    				
    			shortcode=shortcode.replace(re, $(this).val());
    		});
			
			$(themexElements.shortcodeModule).find(themexElements.shortcodeModuleClone).each(function() {
				var shortcode=$(this).children(themexElements.shortcodeModulePattern).html();
				
				$(this).find('input, select, textarea').each(function() {
					var id=$(this).attr('id'),
						re=new RegExp('{{'+id+'}}','g');
						
					shortcode=shortcode.replace(re, $(this).val());
				});
				
				clones=clones+shortcode;
			});
			
			shortcode=shortcode.replace('{{clone}}', clones);
			shortcode=shortcode.replace('="null"', '="0"');
			$(themexElements.shortcodeModuleValue).html(shortcode);
    	},
		
		resize: function() {
			$('#TB_ajaxContent').outerHeight($('#TB_window').outerHeight()-$('#TB_title').outerHeight()-2);
		},
		
    	init: function() {
    		var	themexPopup=this,
    			form=$(themexElements.shortcodeModule).find('form');
				
			//update values
			form.find('select').live('change', function() {
				themexPopup.loadVals();
			});
			
			form.find('input').live('change', function() {
				themexPopup.loadVals();
			});
			
			form.find('textarea').bind('propertychange keyup input paste', function(event){
				themexPopup.loadVals();				
			});
			
			//update clones
			form.find(themexElements.buttonClone).live('click', function() {
				themexPopup.loadVals();
				themexPopup.resize();
			});
			
			form.find(themexElements.buttonRemove).live('click', function() {
				themexPopup.loadVals();
				themexPopup.resize();
			});
			
			//send to editor
			form.live('submit', function() {
				themexPopup.loadVals();
				if(window.tinyMCE) {
					if(window.tinyMCE.majorVersion>3) {
						window.tinyMCE.execCommand('mceInsertContent', false, $(themexElements.shortcodeModuleValue).html());
					} else {
						window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, $(themexElements.shortcodeModuleValue).html());
					}
					
					tb_remove();
				}
				
				return false;
			});	
    	}
	}
	
	//init popup
	themexPopup.init();
	
	//resize popup
	$(window).resize(function() {
		themexPopup.resize();
	});
});