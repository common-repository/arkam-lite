(function($) {
    $(document).ready(function() {

        // Box expand
        $(document).on( 'click', '.settings-arkam-lite-boxes .header, .settings-arkam-lite-boxes .handlediv', function() {
            $(this).parent().toggleClass('expanded');
        });

        // Open panel
        $(document).on('click', '.arkam-gen-btn', function(e) {
            e.preventDefault();

            var panel = $('#arkam-token-box');
            var channel = $(this).closest('.arkam-lite-box');

            panel.attr('data-channel', channel.attr('id'));
            panel.addClass('open');

            update_panel_fields( panel ); // If necessary
        });

        // Generate clicked
        $(document).on('click', '.arkam-sub-btn', function(e) {
            e.preventDefault();

            var panel = $('#arkam-token-box');

            var inputs = [];
            panel.find('input').each(function() {
                inputs.push($(this));
            });

            generate_access_token( panel.attr('data-channel'), inputs, panel );
        });

        // Clear panel fields
        function clear_panel_fields( panel ) {
            panel.find('input').each( function() {
                $(this).val('');
            });

            panel.removeClass('open');
            panel.removeAttr('data-channel');
        }

        // Update panel fields
        function update_panel_fields( panel ) {
            var inputs = [];
            var channel = panel.attr('data-channel');

            panel.find('input').each(function() {
                inputs.push($(this));
            });

            if ( channel == 'facebook' ) {
                inputs[0].closest('td').siblings('th').text('App ID');
                inputs[1].closest('td').siblings('th').text('App Secret');
            } else if ( channel == 'twitter' ) {
                inputs[0].closest('td').siblings('th').text('Consumer Key');
                inputs[1].closest('td').siblings('th').text('Consumer Secret');
            }
        }

        // Close panel
        $(document).on('click', '.arkam-lite-panel .close', function(e) {
            e.preventDefault();
            var panel = $(this).closest('.arkam-lite-panel');
            clear_panel_fields( panel );
        });

        // Generate access token
        function generate_access_token( channel, inputs, panel ) {
            if ( channel == 'facebook' ) {
                generate_facebook_access_token( channel, inputs );
            } else if ( channel == 'twitter' ) {
                generate_twitter_access_token( channel, inputs );
            }

            clear_panel_fields( panel );
        }

        // Set access token
        function set_access_token( channel, token ) {
            $('#'+ channel +'.arkam-lite-box').find('input[name="tt_arkam_lite['+ channel +'][access]"]').val(token);
        }

        // Generate facebook access token
        function generate_facebook_access_token( channel, inputs ) {

            var remote_url = 'https://graph.facebook.com/oauth/access_token?client_id='+ inputs[0].val() +'&client_secret='+ inputs[1].val() +'&grant_type=client_credentials';

            $.getJSON(remote_url, function(data) {
                set_access_token(channel, data.access_token);
            }).error(function() { alert(arkamLiteAdmin.error); });
        }

        // generate twitter access token
        function generate_twitter_access_token( channel, inputs ) {

            var credentials = btoa(inputs[0].val() +':'+ inputs[1].val());

            $.ajax({
                url : arkamLiteAdmin.ajaxurl,
                type: 'POST',
                data: {
                    'action': 'arkam_get_twitter_token',
                    '_ajax_nonce': arkamLiteAdmin.nonce,
                    'consumerKey' : inputs[0].val(),
                    'consumerSecret': inputs[1].val()
                },

                success: function(token){
                    if ( token ) {
                        set_access_token(channel, token);
                    } else {
                        alert(arkamLiteAdmin.error);
                    }
                }
            });
        }

        // Sticky sidebar
        $('.arkam-lite-sidebar').theiaStickySidebar({
            additionalMarginTop: 50
        });
    });
})( jQuery );