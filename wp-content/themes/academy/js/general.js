//Theme Options
var themeElements = {
	siteWrap: '.site-wrap',
	footerWrap: '.footer-wrap',
	mainMenu: '.header-navigation',
	selectMenu: '.select-menu',
	ratingForm: '.rating-form',
	themexSlider: '.themex-slider',
	parallaxSliderClass: 'parallax-slider',
	toolTip: '.tooltip',
	toolTipWrap: '.tooltip-wrap',
	tooltipSwitch: '.switch-button',
	button: '.button',
	submitButton: '.submit-button',
	printButton: '.print-button',
	facebookButton: '.facebook-button',
	toggleTitle: '.toggle-title',
	toggleContent: '.toggle-content',
	toggleElement: '.toggle-element',
	toggleContainer: '.toggle-container',
	accordionContainer: '.accordion',
	tabsContainer: '.tabs-container',
	tabsTitles: '.tabs',
	tabsPane: '.pane',
	playerContainer: '.jp-container',
	playerSource: '.jp-source a',
	player: '.jp-jplayer',
	playerFullscreen: '.jp-screen-option',
	placeholderFields: '.popup-form input',
	userImageUploader: '.user-image-uploader',
	popupContainer: '.popup-container',
	googleMap: '.google-map-container',
	woocommercePrice: '.product-price',
	woocommerceTotal: 'tr.order-total',
	widgetTitle: '.widget-title',
	ajaxForm: '.ajax-form',
};

//DOM Loaded
jQuery(document).ready(function($) {
	
	//Dropdown Menu
	$(themeElements.mainMenu).find('li').hoverIntent(
		function() {
			var menuItem=$(this);
			menuItem.parent('ul').css('overflow','visible');			
			menuItem.children('ul').slideToggle(200, function() {
				menuItem.addClass('hover');
			});
		},
		function() {
			var menuItem=$(this);
			menuItem.children('ul').slideToggle(200, function() {
				menuItem.removeClass('hover');
			});
		}
	);
	
	//Select Menu
	$(themeElements.selectMenu).find('select').fadeTo(0, 0);
	$(themeElements.selectMenu).find('span').text($(themeElements.selectMenu).find('option:eq(0)').text());
	$(themeElements.selectMenu).find('option').each(function() {
		if(window.location.href==$(this).val()) {
			$(themeElements.selectMenu).find('span').text($(this).text());
			$(this).attr('selected','selected');
		}
	});
	
	$(themeElements.selectMenu).find('select').change(function() {
		window.location.href=$(this).find('option:selected').val();
		$(themeElements.selectMenu).find('span').text($(this).find('option:selected').text());
	});

	//Course Rating
	$(themeElements.ratingForm).each(function() {
		var rating=$(this).children('div'),
			form=$(this).children('form');
			
		rating.raty({
			score: rating.data('score'),
			readOnly: rating.data('readonly'),
			hints   : ['', '', '', '', ''],
			noRatedMsg : '',
			click: function(score, evt) {
				form.find('.rating').val(score);
				form.submit();
			}
		});
	});
	
	//Audio and Video
	$(themeElements.playerContainer).bind('contextmenu', function() {
		return false;
	}); 

	if($(themeElements.playerContainer).length) {
		$(themeElements.playerContainer).each(function() {
			var mediaPlayer=$(this);
			var suppliedExtensions='';
			var suppliedMedia=new Object;
			
			mediaPlayer.find(themeElements.playerSource).each(function() {
				var mediaLink=$(this).attr('href');
				var mediaExtension=$(this).attr('href').split('.').pop();
				
				if(mediaExtension=='webm') {
					mediaExtension='webmv';
				}
				
				if(mediaExtension=='mp4') {
					mediaExtension='m4v';
				}
				
				suppliedMedia[mediaExtension]=mediaLink;				
				suppliedExtensions+=mediaExtension;
				
				if(!$(this).is(':last-child')) {
					suppliedExtensions+=',';
				}
			});
		
			mediaPlayer.find(themeElements.player).jPlayer({
				ready: function () {
					$(this).jPlayer('setMedia', suppliedMedia);
				},
				swfPath: 'js/jplayer/Jplayer.swf',
				supplied: suppliedExtensions,
				cssSelectorAncestor: '#'+mediaPlayer.attr('id'),
				solution: 'html, flash',
				wmode: 'window'
			});		
			
			mediaPlayer.show();
		});		
		
		$(themeElements.playerFullscreen).click(function() {
			$('body').toggleClass('fullscreen-video');
		});
	}	
	
	//Sliders
	$(themeElements.themexSlider).each(function() {
		var sliderOptions= {
			speed: parseInt($(this).find('.slider-speed').val()),
			pause: parseInt($(this).find('.slider-pause').val()),
			effect: $(this).find('.slider-effect').val()
		};
		
		$(this).themexSlider(sliderOptions);
	});
	
	//Tooltips
	$(themeElements.toolTip).click(function(e) {
		var tooltipButton=$(this).children(themeElements.button);
		if(!tooltipButton.hasClass('active')) {
			var tipCloud=$(this).find(themeElements.toolTipWrap).eq(0);
			if(!tipCloud.hasClass('active')) {
				tooltipButton.addClass('active');
				$(themeElements.toolTipWrap).hide();
				tipCloud.addClass('active').fadeIn(200);
			}
		}
		
		return false;
	});
	
	$(themeElements.tooltipSwitch).click(function() {
		var tipCloud=$(this).parent();
		while(!tipCloud.is(themeElements.toolTipWrap)) {
			tipCloud=tipCloud.parent();
		}
		
		tipCloud.fadeOut(200, function() {
			$(this).next(themeElements.toolTipWrap).addClass('active').fadeIn(200);
		});
		
		return false;
	});
	
	$('body').click(function() {
		$(themeElements.toolTipWrap).fadeOut(200, function() {
			$(this).removeClass('active');
		});
		$(themeElements.toolTipWrap).parent().children(themeElements.button).removeClass('active');
	});
	
	//Placeholders
	$('input, textarea').each(function(){
		if($(this).attr('placeholder')) {
			$(this).placeholder();
		}		
	});
	
	$(themeElements.placeholderFields).each(function(index, item){
		item = $(item);
		var defaultStr = item.val();
	
		item.focus(function () {
			var me = $(this);
			if(me.val() == defaultStr){
				me.val('');
			}
		});
		item.blur(function () {
			var me = $(this);			
			if(!me.val()){
				me.val(defaultStr);
			}
		});
	});
	
	//Popup
	$(themeElements.popupContainer).each(function() {
		var popup=$(this).find('.popup');

		if(popup.length) {
			$(this).find('a').each(function() {
				if(!$(this).hasClass('disabled')) {
					$(this).click(function() {
						popup.stop().hide().fadeIn(300, function() {
							window.setTimeout(function() {
								popup.stop().show().fadeOut(300);
							}, 2000);
						});
						
						return false;
					});
				}
			});
		}
	});
	
	//Toggles
	$(themeElements.accordionContainer).each(function() {
		if(!$(this).find(themeElements.toggleContainer+'.expanded').length) {
			$(this).find(themeElements.toggleContainer).eq(0).addClass('expanded').find(themeElements.toggleContent).show();
		}
	});
	
	$(themeElements.toggleTitle).live('click', function() {
		if($(this).parent().parent().hasClass('accordion') && $(this).parent().parent().find('.expanded').length) {
			if($(this).parent().hasClass('expanded')) {
				return false;
			}
			$(this).parent().parent().find('.expanded').find(themeElements.toggleContent).slideUp(200, function() {
				$(this).parent().removeClass('expanded');			
			});
		}
		
		$(this).parent().find(themeElements.toggleContent).slideToggle(200, function(){
			$(this).parent().toggleClass('expanded');		
		});
	});
	
	if(window.location.hash && $(window.location.hash).length) {
		$(window.location.hash).each(function() {
			var item=$(this);
			
			if(item.parent().hasClass('children')) {
				item=$(this).parent().parent();
			}
			
			item.addClass('expanded');
		});
	}
	
	$(themeElements.toggleElement).each(function() {
		var element=$(this);
		
		if(element.data('class')) {
			var toggles=$('.'+element.data('class'));
			
			if(toggles.length) {
				toggles.find('*').hide();				
				element.click(function() {
					toggles.find('*').slideToggle(200);
					setTimeout(function() {
						element.toggleClass('expanded');
					}, 200);
				});
			}
		}
	});
	
	//Tabs
	$(themeElements.tabsContainer).each(function() {		
		var tabs=$(this);
		
		//show current pane
		if(window.location.hash && tabs.find(window.location.hash+'-tab').length) {
			var currentPane=tabs.find(window.location.hash+'-tab');
			currentPane.show();
			$(window).scrollTop(tabs.offset().top);
			tabs.find(themeElements.tabsTitles).find('li').eq(currentPane.index()).addClass('current');
		} else {
			tabs.find(themeElements.tabsPane).eq(0).show().addClass('current');
			tabs.find(themeElements.tabsTitles).find('li').eq(0).addClass('current');
		}
		
		tabs.find('.tabs li').click(function() {
			//set tab link
			window.location.href=$(this).find('a').attr('href');
			
			//set active state to tab
			tabs.find('.tabs li').removeClass('current');
			$(this).addClass('current');
			
			//show current tab
			tabs.find('.pane').hide();
			tabs.find('.pane:eq('+$(this).index()+')').show();

			return false;
		});
	});	
	
	//AJAX Form
	$(themeElements.ajaxForm).each(function() {
		var form=$(this);
		
		form.submit(function() {
			var message=form.find('.message'),
				loader=form.find('.form-loader'),
				button=form.find(themeElements.submitButton);
				
			var data={
					action: form.find('.action').val(),
					nonce: form.find('.nonce').val(),
					data: form.serialize(),
				}
						
			button.addClass('disabled');
			loader.show();
			message.slideUp(300);
			
			$.post(form.attr('action'), data, function(response) {
				if($('.redirect', response).length) {
					if($('.redirect', response).attr('href')) {
						window.location.href=$('.redirect',response).attr('href');
					} else {
						window.location.reload();
					}
				} else {
					loader.hide();
					button.removeClass('disabled');
					message.html(response).slideDown(300);
				}
			});
			
			return false;
		});
	});
	
	//Submit Button
	$(themeElements.submitButton).not('.disabled').click(function() {
		var form=$($(this).attr('href'));
		
		if(!form.length || !form.is('form')) {
			form=$(this).parent();
			while(!form.is('form')) {
				form=form.parent();
			}
		}
			
		form.submit();		
		return false;
	});
	
	$('input').keypress(function (e) {
		var form=$(this).parent();
	
		if (e.which==13) {
			e.preventDefault();
			
			while(!form.is('form')) {
				form=form.parent();
			}
			
			
			form.submit();
		}
	});
	
	//Print Button
	$(themeElements.printButton).click(function() {
		window.print();
		return false;
	});
	
	//Facebook Button
	$(themeElements.facebookButton).click(function() {
		var redirect=$(this).attr('href');
		
		if(typeof(FB)!='undefined') {
			FB.login(function(response) {
				if (response.authResponse) {
					window.location.href=redirect;
				}
			}, {
				scope: 'email',
			});
		}
		
		return false;
	});
	
	//Image Uploader
	$(themeElements.userImageUploader).find('input[type="file"]').change(function() {
		var form=$(this).parent();
		
		while(!form.is('form')) {
			form=form.parent();
		}
		
		form.submit();
	});
	
	//Google Map
	$(themeElements.googleMap).each(function() {
		var container=$(this);		
		var position = new google.maps.LatLng(container.find('.map-latitude').val(), container.find('.map-longitude').val());
		var myOptions = {
		  zoom: parseInt(container.find('.map-zoom').val()),
		  center: position,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var map = new google.maps.Map(
			document.getElementById('google-map'),
			myOptions);
	 
		var marker = new google.maps.Marker({
			position: position,
			map: map,
			title:container.find('.map-description').val()
		});  
	 
		var infowindow = new google.maps.InfoWindow({
			content: container.find('.map-description').val()
		});
	 
		google.maps.event.addListener(marker, 'click', function() {
			infowindow.open(map,marker);
		});
	});
	
	//WooCommerce
	$('body').bind('updated_checkout', function() {
		var total=$(themeElements.woocommerceTotal).find('.amount');
		
		if(total.length) {
			$(themeElements.woocommercePrice).find('.amount').each(function() {
				if(!$(this).parent().is('del')) {
					$(this).text(total.text());
				}
			});
		}
	});
	
	//Window Loaded
	$(window).bind('load resize', function() {

	});
	
	//IE Detector
	if ( $.browser.msie ) {
		$('body').addClass('ie');
	}
	
	//DOM Elements
	$('p:empty').remove();
	$('h1,h2,h3,h4,h5,h6,blockquote').prev('br').remove();
	
	$(themeElements.widgetTitle).each(function() {
		if($(this).text()=='') {
			$(this).remove();
		}
	});
	
	$('ul, ol').each(function() {
		if($(this).css('list-style-type')!='none') {
			$(this).css('padding-left', '1em');
			$(this).css('text-indent', '-1em');
		}
	});
});