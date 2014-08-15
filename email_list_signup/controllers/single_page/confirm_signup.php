<?php  

namespace Concrete\Package\EmailListSignup\Controller\SinglePage;
use \Concrete\Core\Page\Controller\PageController;
use Loader;
use \Concrete\Package\EmailListSignup\Models\EmailListSignup;
use Block;
use UserInfo;

class ConfirmSignup extends PageController {

	public function view() {
		$email = urldecode($this->get('e'));
		$hash = urldecode($this->get('c'));
		$bID = $this->get('b');

		$signup = new EmailListSignup();

		if (!$email || !$hash || !$bID) {
			$msg = t('Error: Invalid confirmation code. Try copying the confirmation link from your email and pasting it into the address bar of your browser.');
		} else if (!$signup->load('email=? AND confirmationHash=?', array($email, $hash))) {
			$msg = t('Error: Incorrect email or code.');
		} else if (!empty($signup->confirmed)) {
			$msg = t('You have already been confirmed.');
		} else {
			$signup->confirmed = date('Y-m-d H:i:s');
			$signup->save();
			$b = Block::GetById($bID);
			$bi = $b->getInstance();
			$msg = $bi->confirmationSuccessMsg;
			$this->send_notification_email($signup->email);
		}

		$this->set('msg', $msg);
	}

	private function send_notification_email($signup_email) {
		//send email to admin with signup data
		$adminEmail = UserInfo::getByID(USER_SUPER_ID)->getUserEmail();
		$mh = Loader::helper('mail');
		$mh->to($adminEmail);
		$mh->from($adminEmail);
		$mh->addParameter('signup_email', $signup_email);
		$mh->load('signup_confirmation_admin_notify', 'email_list_signup');
		$mh->setSubject(t('['.SITE.'] New Email List Signup Confirmed'));
		@$mh->sendMail();
	}
}
