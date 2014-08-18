function getEntry (actionurl, entryid, username) {
	$('#tumblrentry').html('');
	$('#tumblrentry-loading').css('display', 'block');
	$.ajax({
		type: 'POST',
		url: actionurl,
		data: ({ tumblrUsername: username,
				 tumblrEntryID: entryid }),
		dataType: 'html',
		success: function(data) {
			$('#tumblrentry-loading').css('display', 'none');
			$('#tumblrentry').html(data);
		}
	});
}

