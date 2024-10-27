(function() {
    tinymce.create('tinymce.plugins.ArkamLite', {
        init : function(ed, url) {

			// Add Arkam
            ed.addButton('tt_arkam_lite', {
                title : arkamLiteAdmin.mce.arkam,
                cmd : 'tt_arkam_lite_cmd',
                image :  'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA0OTQgNDk0Ij48dGl0bGU+dGhlbWllbnQ8L3RpdGxlPjxjaXJjbGUgY3g9IjI0NyIgY3k9IjI0NyIgcj0iMjQ3IiBmaWxsPSIjMDAwMDAwIi8+PHBvbHlnb24gcG9pbnRzPSIzODggMTI2IDI2OCAxMjYgMjI1IDEyNiAyMjUgMTY4IDIyNSA0MTEgMjY4IDQxMSAyNjggMTY4IDM4OCAxNjggMzg4IDEyNiIgZmlsbD0iI2ZmZiIvPjxyZWN0IHg9IjEwNiIgeT0iMTI2IiB3aWR0aD0iMTAzIiBoZWlnaHQ9IjQyIiBmaWxsPSIjZmZmIi8+PC9zdmc+'
            });

			// Arkam command
            ed.addCommand('tt_arkam_lite_cmd', function() {

            	var params = [{
					type: 'listbox',
					name: 'layout',
					label: arkamLiteAdmin.mce.layout,
					'values': [
						{text: arkamLiteAdmin.mce.grid, value: 'grid'},
						{text: arkamLiteAdmin.mce.mosaic, value: 'mosaic'},
						{text: arkamLiteAdmin.mce.block, value: 'block'},
					]
				},
				{
					type: 'listbox',
					name: 'spacing',
					label: arkamLiteAdmin.mce.spacing,
					'values': [
						{text: arkamLiteAdmin.mce.pixels_0, value: '0'},
						{text: arkamLiteAdmin.mce.pixel_1, value: '1'},
						{text: arkamLiteAdmin.mce.pixels_2, value: '2'},
						{text: arkamLiteAdmin.mce.pixels_3, value: '3'},
						{text: arkamLiteAdmin.mce.pixels_4, value: '4'},
						{text: arkamLiteAdmin.mce.pixels_5, value: '5'},
					]
				},
				{
					type: 'listbox',
					name: 'size',
					label: arkamLiteAdmin.mce.size,
					'values': [
						{text: arkamLiteAdmin.mce.small, value: 'small'},
						{text: arkamLiteAdmin.mce.medium, value: 'medium'},
						{text: arkamLiteAdmin.mce.large, value: 'large'},
					]
				},
				{
					type: 'listbox',
					name: 'color',
					label: arkamLiteAdmin.mce.color,
					'values': [
						{text: arkamLiteAdmin.mce.colored, value: 'colored'},
						{text: arkamLiteAdmin.mce.light, value: 'light'},
						{text: arkamLiteAdmin.mce.dark, value: 'dark'},
					]
				}];

				// Grab supported channels
            	var channels = JSON.parse( arkamLiteAdmin.channels );

            	// Loop throught channels
				for (var key in channels) {

					// Add channel checkbox
					params.push({
						type: 'checkbox',
						checked: false,
						name: key,
						label: channels[key],
					});
				}

				// Add Custom class field
				params.push({
					type: 'textbox',
					name: 'el_class',
					label: arkamLiteAdmin.mce.el_class
				});

				ed.windowManager.open({
					title: arkamLiteAdmin.mce.settings,
					classes: 'arkam-mce-wrap',
					body: params,

					onsubmit: function(e) {

						var output = '[arkam_lite';
						output += ' layout="'+ e.data.layout +'"';
						output += ' spacing="'+ e.data.spacing +'"';
						output += ' size="'+ e.data.size +'"';
						output += ' design="'+ e.data.design +'"';
						output += ' style="'+ e.data.style +'"';
						output += ' shape="'+ e.data.shape +'"';
						output += ' el_class="'+ e.data.el_class +'"';

						if (e.data.new_tab) {	
							output += ' new_tab="' + e.data.new_tab +'"';
						}

						if (e.data.no_follow) {	
							output += ' no_follow="' + e.data.no_follow +'"';
						}

						// Loop throught channels
						for (var key in channels) {

							// If a channel was selected, add it
							if (key in e.data && e.data[key]) {	
								output += ' '+ key +'="' + e.data[key] +'"';
							}
						}

						output += ']';

						ed.insertContent(output);
					}
				});
			});
        },
		createControl : function(n, cm) {
			return null;
		},
    });

    /* Start the buttons */
    tinymce.PluginManager.add( 'arkam_lite_script', tinymce.plugins.ArkamLite );
})();