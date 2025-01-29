(function( $ ) {

    /**
     * Ajax call to add all sub-sites to the project huddle
     *
     * @type {*|jQuery|HTMLElement}
     */
    var add_sites_loader = $( 'span#ph_network_add_sites_status' );
    var remove_sites_loader = $( 'span#ph_network_remove_sites_status' );

    var add_sites = $("#add_all_subsites_to_projecthuddle2");
    var remove_sites = $("#remove_all_subsites_to_projecthuddle2");

    add_sites.on('click', function(e) {
        e.preventDefault();
        // do ajax request
        $.ajax({
            url: ph_network_vars.ajaxurl,
            method: 'POST',
            data: {
                action: 'ph_network_sub_sites',
                nonce: ph_network_vars.nonce,
                job: 'add'
            },
            beforeSend: function() {
                remove_sites_loader.removeClass('complete');
                add_sites_loader.css('display', 'inline-block').addClass('running');
                add_sites.attr('disabled', 'disabled');
            }
        })
            .done(function(data) {
                add_sites_loader.removeClass('running').addClass('complete');
                add_sites.removeAttr('disabled');
            })
            .fail(function(error) {
                console.log('error: ', error.responseJSON);
            });
    });

    /**
     * Ajax call to remove all sub-sites from the project huddle
     *
     * @type {*|jQuery|HTMLElement}
     */
    remove_sites.on('click', function(e) {
        e.preventDefault();
        // do ajax request
        $.ajax({
            url: ph_network_vars.ajaxurl,
            method: 'POST',
            data: {
                action: 'ph_network_sub_sites',
                nonce: ph_network_vars.nonce,
                job: 'remove'
            },
            beforeSend: function() {
                add_sites_loader.removeClass('complete');
                remove_sites_loader.css('display', 'inline-block').addClass('running');
                remove_sites.attr('disabled', 'disabled');
            }
        })
            .done(function(data) {
                remove_sites_loader.removeClass('running').addClass('complete');
                remove_sites.removeAttr('disabled');
            })
            .fail(function(error) {
                console.log('error: ', error.responseJSON);
        });
    });


})( jQuery );
