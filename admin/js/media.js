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
        return this;
    },
    events: {
        "submit #req-instagram":"SubmitForm"
    },
    SubmitForm: function(event) {
        event.preventDefault();
        //console.log(jQuery('#req-instagram input[type="text"]').val());
        jQuery.ajax({
            type: "post",
            url: SMPIAdminJs.ajaxUrl,
            data: {
                action: 'instagram_submit_form',
                username: jQuery('#req-instagram input[type="text"]').val()
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
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

