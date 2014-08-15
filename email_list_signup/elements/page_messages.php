<?php  defined('C5_EXECUTE') or die(_("Access Denied."));
global $emailListSignupError, $emailListSignupSuccess;
?>

<?php  if (isset($emailListSignupError)): ?>
	<div class="email_list_signup_error">
		<?php  echo $emailListSignupError?>
	</div>
<?php  endif; ?>

<?php  if (isset($emailListSignupSuccess)): ?>
	<div class="email_list_signup_success">
		<?php  echo $emailListSignupSuccess?>
	</div>
<?php  endif; ?>