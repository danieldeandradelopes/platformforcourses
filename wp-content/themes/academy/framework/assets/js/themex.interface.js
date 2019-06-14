var themexElements={
	page: '.themex-page',
	popup: '.themex-popup',
	button: '.themex-button',
	buttonSave: '.themex-save-button',
	buttonSubmit: '.themex-submit-button',
	buttonReset: '.themex-reset-button',
	buttonUpload: '.themex-upload-button',
	buttonAdd: '.themex-add-button',
	buttonClone: '.themex-clone-button',
	buttonRemove: '.themex-remove-button',
	buttonRefresh: '.themex-refresh-button',
	option: '.themex-option',
	optionImage: '.themex-select-image',
	optionColor: '.themex-colorpicker',
	optionSlider: '.themex-slider-controls',
	optionSliderValue: '.themex-slider-value',
	selectSubmit: '.themex-submit-select',
	sidebarModule: '.themex-sidebar',
	shortcodeModule: '.themex-shortcode',
	shortcodeModuleClone: '.themex-shortcode-clone',
	shortcodeModuleValue: '.themex-shortcode-value',
	shortcodeModulePattern: '.themex-shortcode-pattern',
	tabsContainer: '.themex-page',
	tabsList: '.themex-menu',
	tabsPane: '.themex-section',
}

jQuery(document).ready(function($) {

	//Options
	$(themexElements.page).find('form').submit(function() {
		return false;
	});	
	
	$(themexElements.page).find('input[type="submit"]:not(.disabled)').live('click', function() {
		var options = $(themexElements.page).find('form').serialize();
		var data = {
			action: $(this).attr('name'),
			options: options
		};
		
		if($(this).attr('name')=='themex_reset_options') {
			$(themexElements.buttonReset).addClass('disabled');
		} else {
			$(themexElements.buttonReset).removeClass('disabled');
		}
		
		$(themexElements.buttonSave).addClass('disabled');		
		$.post($(themexElements.page).find('form').attr('action'), data, function(response) {
			$(themexElements.popup).text(response).fadeIn(300);
			window.setTimeout(function() {
				$(themexElements.popup).fadeOut(300);
			}, 2000);
		});
	});
	
	$(themexElements.page).find(themexElements.option).each(function() {
		var parent=$(this).data('parent'),
			value=$(this).data('value');
			
		if(parent) {		
			parent=$('#'+parent);
			if(parent.length && ((parent.is('select') && parent.val()!=value) || (parent.is('input') && !parent.is(':checked')))) {
				$(this).hide();
			}
		}
	});
	
	$(themexElements.page).find('select, input[type="checkbox"]').change(function() {
		var value=$(this).val();
		if($(this).is('input') && !$(this).is(':checked')) {
			value='';
		}
		
		var children=$(themexElements.page).find('[data-parent="'+$(this).attr('id')+'"]'),
			visible=children.filter('[data-value="'+value+'"]'),
			hidden=children.filter('[data-value!="'+value+'"]');
			
		if(children.length) {
			visible.slideDown(300);
			hidden.slideUp(300);
		}
	});
	
	//Buttons
	$(themexElements.page).find('input, select').live('change', function(){
		$(themexElements.buttonSave).removeClass('disabled');
	});
	
	$(themexElements.page).find('input, textarea').each(function() {
		$(this).data('value', $(this).val());
		$(this).bind('propertychange keyup input paste', function(event){
			if ($(this).data('value')!=$(this).val()) {
				$(this).data('value', $(this).val());
				$(themexElements.buttonSave).removeClass('disabled');
			}
		});
	});
	
	$(themexElements.buttonAdd).live('click', function() {
		var button=$(this);
		var data = {
			action: button.data('action'),
			value: $(themexElements.page).find('#'+button.data('value')).val(),
		};
		
		if(button.data('value')) {
			$.post($(themexElements.page).find('form').attr('action'), data, function(response) {			
				if(response) {				
					$(themexElements.buttonSave).removeClass('disabled');					
					if(button.data('container')) {
						$('#'+button.data('container')).prepend(response);
						$('#'+button.data('container')).find('>*:first-child').hide().slideToggle(300);
					} else if(button.data('element')) {
						$('#'+button.data('element')).after(response);
						$('#'+button.data('element')).next('*').hide().slideToggle(300);
					}
				}
			});
		}	
		
		return false;
	});
	
	$(themexElements.buttonRemove).live('click', function() {
		var button=$(this);
		
		$('#'+button.data('element')).slideToggle(300, function() {
			$(themexElements.buttonSave).removeClass('disabled');
			$(this).remove();
		});
		
		return false;
	});
	
	$(themexElements.buttonClone).live('click', function() {
		var button=$(this),
			pane=$(button.data('element')),
			key='a'+(new Date().getTime().toString(16));
		
		if(!pane.length) {
			pane=button.parent();
		}
		
		newPane=pane.clone().attr('id', pane.attr('id').replace(button.data('value'), key)).hide();
		newPane.html(newPane.html().replace(new RegExp(button.data('value'), 'igm'), key));
		newPane.find('input[type="text"], input[type="number"], select, textarea').val('');
		newPane.find('input[type="checkbox"]').attr('checked', false);
		newPane.insertAfter(pane).slideToggle(300);
		
		return false;
	});
	
	$(themexElements.buttonSubmit).click(function() {
		var form=$(this).parent();
		
		if(!form.length || !form.is('form')) {
			form=$(this).parent();
			while(!form.is('form')) {
				form=form.parent();
			}
		}
			
		form.submit();
		return false;
	});
	
	$(themexElements.buttonRefresh).click(function() {
		location.reload();
		return false;
	});
	
	//Tabs
	$(themexElements.tabsContainer).each(function() {
		var tabsContainer=$(this);
		
		if(window.location.hash && tabsContainer.find(window.location.hash).length) {
			tabsContainer.find(window.location.hash).show();
			
			tabsContainer.find(themexElements.tabsList).find('li').each(function() {
				if($(this).find('a').attr('href')==window.location.hash) {
					$(this).addClass('current');
				}
			});
			
		} else {
			tabsContainer.find(themexElements.tabsList).find('li:eq(0)').addClass('current');
			tabsContainer.find(themexElements.tabsPane).eq(0).show();
		}
	
		tabsContainer.find(themexElements.tabsList).find('li').click(function() {
			var tabLink=$(this).find('a').attr('href');
			window.location.hash=tabLink;
			
			tabsContainer.find(themexElements.tabsList).find('li').removeClass('current');
			$(this).addClass('current');
			
			tabsContainer.find(themexElements.tabsPane).hide();
			tabsContainer.find(tabLink).show();
		
			return false;	
		});
	});
	
	//Colorpicker
	$(themexElements.optionColor).wpColorPicker({
		defaultColor: $(this).val(),
		palettes: false,
		change: function(event, ui){
			$(themexElements.buttonSave).removeClass('disabled');
		}
	});
	
	//Select
	$(themexElements.selectSubmit).change(function() {
		var form=$(this).parent();
		
		if(!form.length || !form.is('form')) {
			form=$(this).parent();
			while(!form.is('form')) {
				form=form.parent();
			}
		}
			
		form.submit();
		return false;
	});
	
	//Slider
	$(themexElements.optionSlider).each(function() {
		var slider=$(this);
		var unit=slider.parent().find('input.slider-unit').val();
		var value=parseInt(slider.parent().find('input.slider-value').val());
		var minValue=parseInt(slider.parent().find('input.slider-min').val());
		var maxValue=parseInt(slider.parent().find('input.slider-max').val());		

		slider.parent().find(themexElements.optionSliderValue).text(value+' '+unit);		
		slider.slider({
			value: value,
			min: minValue,
			max: maxValue,
			slide: function( event, ui ) {
				slider.parent().find(themexElements.optionSliderValue).text( ui.value+' '+unit );
				slider.parent().find('input.slider-value').val(ui.value);
				$(themexElements.buttonSave).removeClass('disabled');
			}
		});
	});
	
	//Select Image
	$(themexElements.optionImage).find('img').click(function(){
		$(themexElements.buttonSave).removeClass('disabled');
		$(this).parent().find('img').removeClass('current');
		$(this).addClass('current');	
		$(this).parent().find('input').val($(this).data('value'));				
	});	
	
	//Uploader
	var header_clicked = false,
		fileInput = '',
		imageInput = '';

	$(themexElements.buttonUpload).live('click', function(e) {		
		fileInput = jQuery(this).prev('input');		
		
		if(fileInput.length) {
			imageInput = fileInput.prev('img');
		}
		
		if(fileInput.length) {
			tb_show('', 'media-upload.php?post=-629834&amp;themex_uploader=1&amp;TB_iframe=true');
			header_clicked = true;
			e.preventDefault();
		}
	});

	//store original
	window.original_send_to_editor = window.send_to_editor;
	window.original_tb_remove = window.tb_remove;

	//override removing
	window.tb_remove = function() {
		header_clicked = false;
		window.original_tb_remove();
	}
	
	//send to editor
	window.send_to_editor = function(html) {
		$(themexElements.buttonSave).removeClass('disabled');
		if (header_clicked) {
			imgurl = $(html).attr('href');
			fileInput.val(imgurl);
			
			if(imageInput!='' && imageInput.length) {
				imageInput.attr('src', imgurl);
			}
			
			tb_remove();
		} else {
			window.original_send_to_editor(html);
		}		
	}
	
	//Profile
	if($('#profile-page').length) {
		$('#description').parents('tr').remove();
	}
});