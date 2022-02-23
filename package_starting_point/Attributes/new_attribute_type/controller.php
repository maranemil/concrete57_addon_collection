<?php /** @noinspection PhpUnused */
/** @noinspection AutoloadingIssuesInspection */
/** @noinspection PhpUndefinedClassInspection */
/** @noinspection PhpUndefinedNamespaceInspection */

/** @noinspection PhpUndefinedVariableInspection */

namespace Concrete\Package\PackageStartingPoint\Attribute\NewAttributeType;

use Concrete\Core\Utility\Service\Text as TextHelper;
use Concrete\Core\Form\Service\Form as FormHelper;
use Concrete\Core\Attribute\Controller as AttributeTypeController;

/**
 * @property $attributeValue
 * @method getAttributeValue()
 * @method field(string $string)
 */
class Controller extends AttributeTypeController
{

    /**
     * @var array
     */
    protected $searchIndexFieldDefinition = array('type' => 'string', 'options' => array('length' => 255, 'default' => null, 'notnull' => false));

    /**
     * @return void
     */
    public function form()
    {
        $formHelper = new FormHelper();
        if (is_object($this->attributeValue)) {
            $textHelper = new TextHelper();
            $value = $textHelper->entities($this->getAttributeValue()->getValue());
        }
        print $formHelper->text($this->field('value'), $value);
    }

    /**
     * @return void
     */
    public function composer()
    {
        if (is_object($this->attributeValue)) {
            $textHelper = new TextHelper();
            $value = $textHelper->entities($this->getAttributeValue()->getValue());
        }
        print $formHelper->text($this->field('value'), $value, array('class' => 'span5'));
    }


}