<?php /** @noinspection SpellCheckingInspection */
/** @noinspection PhpUnused */
/** @noinspection PhpUndefinedClassInspection */
/** @noinspection AutoloadingIssuesInspection */

/** @noinspection PhpUndefinedNamespaceInspection */

namespace Concrete\Package\EmailListSignup\Controller\SinglePage\Dashboard\Reports;

use Concrete\Core\Page\Controller\DashboardPageController;
use Loader;
use Concrete\Package\EmailListSignup\Models\EmailListSignup;

/**
 * @method set(string $string, $getSignups)
 */
class EmailListSignups extends DashboardPageController
{

    public $helpers = array('form');

    public function view()
    {
        $this->set('signups', $this->getSignups());
    }

    public function download_signups()
    { //In single_pages, do not prepend "action_" (unlike blocks)
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="email_list_signups.csv"');

        $signups = $this->getSignups();
        echo "Email,IP,Created,Confirmed\n";
        foreach ($signups as $signup) {
            echo "$signup->email,";
            echo "$signup->ip,";
            echo "$signup->created,";
            echo "$signup->confirmed\n";
        }

        exit;
    }

    private function getSignups()
    {
        Loader::model('email_list_signup', 'email_list_signup');
        return (new EmailListSignup())->Find('1=1 ORDER BY created DESC'); //1=1 is our phony WHERE clause that finds all records
    }
}
