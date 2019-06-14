(function() {
	if(tinymce.majorVersion>3) {
		var shortcodes=[];
		
		for(var id in themexShortcodes) {
			shortcodes.push({
				value: id,
				text: themexShortcodes[id],
				onclick: function() {
					tb_show(themexTitle, themexURI+'templates/popup.php?width=500&shortcode='+this.value());
				}				
			});
		}
		
		tinymce.PluginManager.add('themex_shortcode', function( editor, url ) {
			editor.addButton( 'themex_shortcode', {
				title: themexTitle,
				type: 'menubutton',
				icon: 'icon themex-shortcode-icon',
				menu: shortcodes,
			});
		});
	} else {
		tinymce.create('tinymce.plugins.themex_shortcode', {
			init: function (ed, url) {
				ed.addCommand('themex_popup', function (a, params) {
					tb_show(themexTitle, themexURI+'templates/popup.php?width=500&shortcode='+params.identifier);
				});
			},
			
			createControl: function (button, e) {
				if (button=='themex_shortcode') {
					var a = this;
						
					button = e.createMenuButton('themex_shortcode', {
						title: themexTitle,
						image: themexURI+'assets/images/icons/icon-shortcode.png',
						icons: false
					});
					
					button.onRenderMenu.add(function (c, b) {
						for(var id in themexShortcodes) {
							var name=themexShortcodes[id];
							a.addWithPopup(b, name, id);
						}
					});
					
					return button;
				}
				
				return null;
			},
			
			addWithPopup: function (ed, title, id) {
				ed.add({
					title: title,
					onclick: function () {
						tinyMCE.activeEditor.execCommand('themex_popup', false, {
							title: title,
							identifier: id
						})
					}
				});
			},
			
			addImmediate: function ( ed, title, shortcode) {
				ed.add({
					title: title,
					onclick: function () {
						tinyMCE.activeEditor.execCommand('mceInsertContent', false, shortcode);
					}
				})
			},
		});
		
		tinymce.PluginManager.add('themex_shortcode', tinymce.plugins.themex_shortcode);	
	}
})();