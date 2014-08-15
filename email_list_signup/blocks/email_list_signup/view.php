<?php  defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

<div class="email_list_signup_container">
	<?php  if (isset($emailListSignupError)): ?>
		<div class="email_list_signup_error_inblock">
			<?php  echo $emailListSignupError; ?>
		</div>
	<?php  endif; ?>

	<?php  if (isset($emailListSignupSuccess)): ?>
		<div class="email_list_signup_success_inblock">
			<?php  echo $emailListSignupSuccess; ?>
		</div>
	<?php  endif; ?>

	<form method="post" action="<?php  echo $this->action('submit_form'); ?>">
		<?php  echo $form->label('email', $labelFieldText); ?>
		<?php  echo $form->text('email', array('title' => $inFieldText)); ?>
		<?php  echo $form->submit('submit', $submitButtonText); ?>
	</form>
</div>