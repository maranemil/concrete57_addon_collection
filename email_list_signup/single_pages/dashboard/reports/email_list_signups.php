<?php  defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

<form method="post" action="<?php  echo $this->action('download_signups'); ?>">
<div class="ccm-dashboard-header-buttons">
	<button class="btn btn-default" type="submit"><?=t('Download List (.csv)')?></button>
</div>
</form>

<table class="table table-striped">
	<tr>
		<th><?=t('Email')?></th>
		<th><?=t('IP Address')?></th>
		<th><?=t('Created')?></th>
		<th><?=t('Confirmed')?></th>
	</tr>
	<?php  foreach ($signups as $signup): ?>
	<tr>
		<td><?php  echo htmlentities($signup->email); ?></td>
		<td><?php  echo $signup->ip; ?></td>
		<td><?php  echo $signup->created; ?></td>
		<td><?php  echo $signup->confirmed; ?></td>
	</tr>
	<?php  endforeach; ?>
</table>