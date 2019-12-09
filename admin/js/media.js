var Select = wp.media.view.MediaFrame.Select,
Library = wp.media.controller.Library,
l10n = SMPIAdminJs.l10n,
Post, 
InstagramBrowser;

(function($,exports){

	var is_chrome	= navigator.userAgent.indexOf('Chrome') > -1
		
	exports.SMPIAdminJs = $.extend( {
		supports : {},
		view:{},
		wpUloader : false,
		getWpUploader: function( ){ 
			return SMPIAdminJs.wpUploader; 
		}
 
	}, SMPIAdminJs );

//	cheese = cheese; 

})( jQuery, wp.media );


jQuery.extend( wp.Uploader.prototype, {
    success : function( file_attachment ){
    }
});

function blobToFile(theBlob, fileName){
    //A Blob() is almost a File() - it's just missing the two properties below which we will add
    theBlob.lastModifiedDate = new Date();
    theBlob.name = fileName;
    return theBlob;
}

(function($,window,o){
	var Button = wp.media.view.Button,Modal  = wp.media.view.Modal;

    
/**
 *	Integrate into media library modal
 */
_parentFrameInitialize = wp.media.view.MediaFrame.Select.prototype.initialize

wp.media.view.InstagramBrowser = wp.media.View.extend({
    tagName:   'div',
    className: 'instagram-browser',
    image: null,
    self: null,
    initialize: function() {
        this.self = this;
    },
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
                jQuery('.media-frame').removeClass('loading');
                jQuery('.smpi-media-content').html('');
                if ( response.status == 'success' ) {
                    if ( response.count !== 0 ) {
                        jQuery.each(response.images, function (i, a) { 
                            jQuery('.smpi-media-content').append('<div class="media-item"><img style="max-width:150px" src="'+a+'"><span class="media-import-button button-primary" data-url="'+a+'">Import</span></div>');
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
        "click .media-import-button":"selectMedia"
    },
    selectMedia: function(event) {
        var Url = jQuery(event.currentTarget).data('url');
        var self = this;
        jQuery.ajax({
            type: "post",
            url: SMPIAdminJs.ajaxUrl,
            data: {
                action: 'smpi_upload_process',
                url: Url
            },
            dataType: "json",
            success: function (response) {
                self.trigger('action:uploaded:image', {id:response.id});
            }
        });

    }
    
});

wp.media.view.MediaFrame.Select = Select.extend({
    _parentInitialize: wp.media.view.MediaFrame.Select.prototype.initialize,
    initialize: function() {
        _parentFrameInitialize.apply( this, arguments );
        this.bindSMPIHandlers();
    },
    bindSMPIHandlers: function() {
        this.on( 'content:create:instagram', this.instagramContent, this );
        this.on( 'content:render:instagram', this.renderInstagramContent, this );
        //this.on( 'action:uploaded:dataimage', this.imageUploaded, this );
        frame = this;
    },
    _parentBrowseRouter: wp.media.view.MediaFrame.Select.prototype.browseRouter,
    browseRouter: function( routerView ) {
        this._parentBrowseRouter.apply(this,arguments);
        routerView.set({instagram:{
            text:     l10n.instagram,
            priority: 50
        }});
    },
    instagramContent: function( contentRegion ) {
        var state = this.state();
        this.$el.removeClass( 'hide-toolbar' );
        this.$el.addClass('loading');
        this.currentSMPIView = contentRegion.view = new wp.media.view.InstagramBrowser({
            controller: this
        });
        this.listenTo( this.currentSMPIView, 'action:uploaded:image', this.imageUploaded );
    },
    imageUploaded: function( content ) {
        var ituh = this;
        console.log(this);
        var selection = wp.media.frame.state().get('selection');
        
        attachment = wp.media.attachment(content.id);
        attachment.fetch();
        selection.add(attachment ? [attachment] : []);
        jQuery('.media-router #menu-item-browse').trigger('click');
        
    }
});

})(jQuery,window,mOxie);
