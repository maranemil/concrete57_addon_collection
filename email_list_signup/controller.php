<?php

namespace Concrete\Package\EmailListSignup;
use Package;
use BlockType;
use SinglePage;
use Loader;

defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends Package {

	protected $pkgHandle = 'email_list_signup';
	protected $appVersionRequired = '5.3.0';
	protected $pkgVersion = '1.5.5';

	public function getPackageName() {
		return t("Email List Signup");
	}

	public function getPackageDescription() {
		return t("Displays a simple form that users can submit their email address to.");
	}

	public function install() {
		$pkg = parent::install();

		// install block
		BlockType::installBlockTypeFromPackage('email_list_signup', $pkg);

		// install single page
		$confirm_signup_page = SinglePage::add('/confirm_signup', $pkg);
		$confirm_signup_page->setAttribute('exclude_nav', 1);

		//Install dashboard page
		SinglePage::add('/dashboard/reports/email_list_signups', $pkg);
	}

	public function uninstall() {
		parent::uninstall();
		$db = Loader::db();
		$db->Execute('DROP TABLE btEmailListSignup'); //Do NOT drop btEmailListSignupSubmissions -- we don't want to accidentally lose signup data!
	}

}