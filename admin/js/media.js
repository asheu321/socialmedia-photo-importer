var Select = wp.media.view.MediaFrame.Select,
Library = wp.media.controller.Library,
l10n = wp.media.view.l10n,
Post, 
InstagramBrowser;

InstagramBrowser = wp.media.View.extend({
    tagName:   'div',
    className: 'instagram-browser',
    render: function() {
        jQuery(this.el).html(wp.template('instagram'));

        jQuery.ajax({
            type: "post",
            url: SMPIAdminJs.ajaxUrl,
            data: {
                action: 'instagram_get_media',
                max_id: false
            },
            dataType: "json",
            success: function (response) {

                if ( response.status == 'success' ) {
                    if ( response.count !== 0 ) {
                        jQuery.each(response.images, function (i, a) { 
                            jQuery('.smpi-media-content').append('<div class="media-item"><img style="max-width:150px" src="'+a+'"></div>');
                        });
                        if ( response.has_next ) {
                            jQuery('.smpi-media-content').append('<div class="smpi-load-more" style="text-align:center;"><span class="button-primary">Load more</span></div>');
                        }
                    } else {
                        jQuery('.smpi-media-content').html('No images found for <strong>' + response.username + '</strong> account.');
                    }
                } else {
                    jQuery('.smpi-media-content').html(response.message);
                }
                
            }
        });

        return this;
    },
    events: {
        "submit #req-instagram":"SubmitForm"
    },
    SubmitForm: function(event) {
        event.preventDefault();
        jQuery('.smpi-small-loading').fadeIn();

        jQuery.ajax({
            type: "post",
            url: SMPIAdminJs.ajaxUrl,
            data: {
                action: 'instagram_submit_form',
                username: jQuery('#req-instagram input[type="text"]').val(),
                has_next: false,
                max_id: false
            },
            dataType: "json",
            success: function (response) {
                jQuery('.smpi-small-loading').fadeOut();
                jQuery('.smpi-media-content').html('');
                var status = response.status;
                var message = response.message;
                var images = response.images;
                if ( status == 'success' ) {
                    if ( response.count !== 0 ) {
                        jQuery.each(response.images, function (i, a) { 
                            jQuery('.smpi-media-content').append('<img style="max-width:150px" src="'+a+'">');
                        });
                    } else {
                        jQuery('.smpi-media-content').html('No images found for <strong>' + response.username + '</strong> account.');
                    }
                } else {
                    jQuery('.smpi-media-content').html(response.message);
                }
                
            }
        });
    }
});

wp.media.view.MediaFrame.Select = wp.media.view.MediaFrame.Select.extend({
    /**
     * Bind region mode event callbacks.
     *
     * @see media.controller.Region.render
     */
    bindHandlers: function() {
        this.on( 'router:create:browse', this.createRouter, this );
        this.on( 'router:render:browse', this.browseRouter, this );
        this.on( 'content:create:browse', this.browseContent, this );
        this.on( 'content:render:upload', this.uploadContent, this );
        this.on( 'toolbar:create:select', this.createSelectToolbar, this );
        this.on( 'content:render:edit-image', this.editImageContent, this );
        this.on( 'content:create:instagram', this.instagramContent, this );
    },
    /**
     * Render callback for the router region in the `browse` mode.
     *
     * @param {wp.media.view.Router} routerView
     */
    browseRouter: function( routerView ) {
        //"use strict";
        routerView.set({
            upload: {
                text:     l10n.uploadFilesTitle,
                priority: 20
            },
            browse: {
                text:     l10n.mediaLibraryTitle,
                priority: 40
            },
            instagram: {
                text:     "Instagram",
                priority: 50
            }
        });
    },
    instagramContent: function( contentRegion ) {

        this.$el.removeClass( 'hide-toolbar' );
        this.content.set(new InstagramBrowser());
        
    }
    
});

