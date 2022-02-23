<?php /** @noinspection PhpUndefinedFunctionInspection */
/** @noinspection PhpUndefinedVariableInspection */
defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

<div class="form-group">
    <?php echo $form->label('labelText', 'Form Label:'); ?>
    <?php echo $form->text('labelText', $labelText); ?>
</div>

<div class="form-group">
    <?php echo $form->label('inFieldText', 'In-Field Label:'); ?>
    <?php echo $form->text('inFieldText', $inFieldText); ?>
</div>

<div class="form-group">
    <?php echo $form->label('submitButtonText', 'Submit Button Text:'); ?>
    <?php echo $form->text('submitButtonText', $submitButtonText); ?>
</div>

<div class="form-group">
    <?php echo $form->label('submitErrorHeaderMsg', 'Submit Error Heading:'); ?>
    <?php echo $form->text('submitErrorHeaderMsg', $submitErrorHeaderMsg); ?>
</div>

<div class="form-group">
    <?php echo $form->label('submitSuccessMsg', 'Submit Success Message:'); ?>
    <?php echo $form->text('submitSuccessMsg', $submitSuccessMsg); ?>
</div>

<div class="form-group">
    <?php echo $form->label('displayMsgInBlock', 'Display Messages:'); ?>
    <?php
    $options = array(
        1 => t("Automatically (in the block's area)"),
        0 => t("Manually (elsewhere on the page)"),
    );
    echo $form->select("displayMsgInBlock", $options, $displayMsgInBlock);
    ?>
</div>

<div id="msgHelp" class="alert alert-info" style="display: none">
    <?php echo t("To display messages elsewhere on the page, your theme templates must have<br />the following line of code (in the place you want messages displayed):"); ?>
    <pre>&lt;?php Loader::packageElement('page_messages', 'email_list_signup'); ?&gt;</pre>
</div>

<script type="text/javascript">
    let refreshMsgHelpDisplay = function () {
        const displayMsgInBlock = ($('#displayMsgInBlock').val() !== '0');
        $('#msgHelp').toggle(displayMsgInBlock);
    };

    $(document).ready(function () {
        $('#displayMsgInBlock').change(refreshMsgHelpDisplay);
        refreshMsgHelpDisplay();
    });
</script>

<div class="form-group">
    <?php echo $form->label('confirmationEmailFrom', 'Confirmation Email FROM Address:'); ?>
    <?php echo $form->text('confirmationEmailFrom', $confirmationEmailFrom); ?>
</div>

<div class="form-group">
    <?php echo $form->label('confirmationEmailSubject', 'Confirmation Email SUBJECT:'); ?>
    <?php echo $form->text('confirmationEmailSubject', $confirmationEmailSubject); ?>
</div>

<div class="form-group">
    <?php echo $form->label('confirmationSuccessMsg', 'Confirmation Success Message:'); ?>
    <?php echo $form->text('confirmationSuccessMsg', $confirmationSuccessMsg); ?>
</div>