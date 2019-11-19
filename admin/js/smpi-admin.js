/**
 * Submit username
 */
jQuery(document).on('submit', '#save-instagram-username', function(e) {
	e.preventDefault();
	jQuery('.smpi-loading').fadeIn();
	jQuery.ajax({
		type: "post",
		url: SMPIAdminJs.ajaxUrl,
		data: {
			action: 'save_instagram_username',
			data: jQuery(this).serialize()
		},
		dataType: "json",
		success: function (response) {
			jQuery('.smpi-loading').fadeOut();
		}
	});
});

/**
 * Validate username
 */
jQuery(document).on('click', '#save-instagram-username .button-validate', function(e){
	var username;
	username = jQuery('#save-instagram-username input[type="text"]').val();
	if ( username == '' || username == null ) { alert('Please enter your Instagram username'); return; }

	jQuery.ajax({
		type: "post",
		url: SMPIAdminJs.ajaxUrl,
		data: {
			action: 'validate_instagram_username',
			username: username
		},
		dataType: "json",
		success: function (response) {
			
		}
	});
});