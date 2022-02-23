<?php /** @noinspection PhpUndefinedFunctionInspection */
/** @noinspection SqlDialectInspection */
/** @noinspection SqlNoDataSourceInspection */
/** @noinspection SpellCheckingInspection */
/** @noinspection AutoloadingIssuesInspection */
/** @noinspection PhpUndefinedClassInspection */

/** @noinspection PhpUndefinedNamespaceInspection */

namespace Concrete\Package\EmailListSignup\Models;

use Concrete\Core\Legacy\Model;
use Loader;

/**
 * @property mixed $ip
 * @property $email
 * @property null $sID
 * @property string $confirmationHash
 * @property false|string $created
 * @method load(string $string, array $array)
 * @method Find(string $string)
 */
class EmailListSignup extends Model
{

    //Explicitly set the table name because it isn't a simple pluralization of the class name
    private $_table = 'btEmailListSignupSubmissions';

    public function Save()
    {
        //Add timestamp to all newly-created records
        // (note that we only care about the timestamp of creation, not update
        // -- if we wanted a "last updated" timestamp we could have just set <DEFTIMESTAMP/>
        // in the db.xml definition)
        $this->created = date('Y-m-d H:i:s');

        $this->confirmationHash = md5($this->created . $this->email);

        return parent::Save();
    }

    public function validate()
    {
        //NOTE: Due to some strange behavior in ADODB-active-record, this validation function will not work properly
        // unless all the record's values have been set or initialized to null.

        $error = Loader::helper('validation/error');
        if (!$this->email) {
            $error->add(t('You must enter your email address.'));
        } else if (!$this->validate_email_format()) {
            $error->add(t('Email address is not in a valid format -- please check that you entered it correctly.'));
        } else if (!$this->validate_email_unique()) {
            $error->add(t('That email address is already subscribed.'));
        }

        ////NOTE: Alternatively, we could use the built-in "form validator" class
        //// (but we're not in this case because we are mostly using custom validations instead of built-in ones).
        // $val = Loader::helper('validation/form');
        // $val->setData(array('email' => $this->email, 'moredata1' => $this->moredata1, 'moredata2' => $this->moredata2));
        // $val->addRequiredEmail('email');
        // if ($val->test() > 0) {
        // 	$error = $val->getError();
        // }

        return $error;
    }

    private function validate_email_format()
    {
        $regex = "/^\S+@\S+\.\S+$/"; //see: http://stackoverflow.com/questions/201323/what-is-the-best-regular-expression-for-validating-email-addresses#201447
        return (bool)preg_match($regex, $this->email);
    }

    private function validate_email_unique()
    {
        $db = Loader::db();
        $sql = "SELECT COUNT(*) FROM $this->_table WHERE email=? AND sID<>?";
        $params = array(
            $this->email,
            empty($this->rID) ? -1 : $this->rID, /* If this isn't a new record, don't count ourselves as a dup! */
        );
        $dupcount = $db->GetOne($sql, $params);
        return (0 === $dupcount);
    }

}