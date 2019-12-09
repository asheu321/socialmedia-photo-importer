
function html_template(data) {
	var html = '<div class="profile">'+
	'<div class="photo"><img src="'+data.profile_picture+'"></div>'+
	'<div class="profile-data">'+
	'<label>ID</label>'+
	data.ID+
	'<label>Username</label>'+
	data.username+
	'<label>Full Name</label>'+
	data.full_name+
	'<label>Media Count</label>'+
	data.media_count+
	'</div>'+
	'<div class="delete instagram"><span>Remove</span></div>'+
	'</div>';
	return html;
}

/**
 * Submit username
 */
jQuery(document).on('submit', '#save-instagram-username', function(e) {
	e.preventDefault();
	jQuery('.smpi-loading').fadeIn();
	var username;
	username = jQuery('#save-instagram-username input[type="text"]').val();
	if ( username == '' || username == null ) { alert('Please enter your Instagram username'); return; }

	jQuery.ajax({
		type: "post",
		url: SMPIAdminJs.ajaxUrl,
		data: {
			action: 'save_instagram_username',
			username: username
		},
		dataType: "json",
		success: function (response) {
			jQuery('.smpi-loading').fadeOut();
			if (response.status == '404') {
				alert(response.message);
			} else {
				jQuery('.hide-on-validated').fadeOut();
				jQuery('.data-result').html(html_template(response.data));
			}
		}
	});
});

/**
 * On load
 */
jQuery(document).ready( function($) {
	jQuery.ajax({
		type: "post",
		url: SMPIAdminJs.ajaxUrl,
		data: {
			action: 'check_instagram_data'
		},
		dataType: "json",
		success: function (response) {
			if (response.data != '') {
				jQuery('.hide-on-validated').fadeOut();
				jQuery('.data-result').html(html_template(response.data));
			}
		}
	});
});

/**
 * delete account from cache
 */
jQuery(document).on('click', '.delete.instagram span', function() {
	jQuery.ajax({
		type: "post",
		url: SMPIAdminJs.ajaxUrl,
		data: {
			action: 'delete_instagram_data'
		},
		dataType: "json",
		success: function (response) {
			jQuery('.hide-on-validated').fadeIn();
			jQuery('.data-result').html('');
		}
	});
});