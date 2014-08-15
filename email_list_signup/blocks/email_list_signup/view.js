$(document).ready(function() {
	insertTextboxTitlesAsLabels();
});

function insertTextboxTitlesAsLabels() {
	$('.email_list_signup_container input.ccm-input-text').each(function(){
		if (this.value == '') {
			this.value = $(this).attr('title');
			$(this).addClass('in-field-label');
		}

		$(this).focus(function(){
			if(this.value == $(this).attr('title')) {
				this.value = '';
				$(this).removeClass('in-field-label');
			}
		});

		$(this).blur(function(){
			if(this.value == '') {
				$(this).addClass('in-field-label');
				this.value = $(this).attr('title');
			}
		});
	});

	$('.email_list_signup_container input.ccm-input-submit').each(function(){
		$(this).click(function(){
			$('.email_list_signup_container input.ccm-input-text').each(function(){
				if(this.value == $(this).attr('title')) {
					this.value = '';
					$(this).removeClass('in-field-label');
				}
			});
		});
	});
}
