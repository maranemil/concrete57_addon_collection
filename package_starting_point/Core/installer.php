<?php /** @noinspection SqlNoDataSourceInspection */
/** @noinspection SqlDialectInspection */
/** @noinspection DynamicInvocationViaScopeResolutionInspection */
/** @noinspection PhpUndefinedFunctionInspection */
/** @noinspection AutoloadingIssuesInspection */
/** @noinspection PhpUndefinedClassInspection */

/** @noinspection PhpUndefinedNamespaceInspection */

namespace Concrete\Package\PackageStartingPoint\Core;

use Concrete\Attribute\Select\Option;
use Concrete\Core\Database\Database;
use Concrete\Core\Foundation\Object;
use Concrete\Core\Attribute\Key\Category as AttributeKeyCategory;
use Concrete\Core\Attribute\Set as AttributeSet;
use Concrete\Core\Attribute\Type as AttributeType;
use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Page\Single as SinglePage;
use Concrete\Core\Page\Page;
use Concrete\Core\Job\Job;
use Concrete\Core\Utility\Service\Text;
use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use RuntimeException;

class Installer extends Object
{

    protected static $preInstallationRequirements
        = array(
            'someOtherPackageInstalled' => array(
                'errorResponse' => array(
                    'title'         => "'Another Package' is not installed."
                    , 'description' => "You must install 'Another Package' before you can install this package."
                )
            )
        );

    /*
     * $attributeTypes is an array of attributes that will
     * be installed by this package.
     */

    protected static $attributeTypes
        = array(//  'new_attribute_type' => array('name' => 'New Attribute Type')
        );

    protected static $attributeCategories
        = array(
            'new_category' => array('allowSets' => 1)
        );

    protected static $defaultAttributeTypeProperties
        = array(
            'default'     => array('akIsSearchable' => 1, 'akIsSearchableIndexed' => 1)
            , 'text'      => array('akIsSearchable' => 1, 'akIsSearchableIndexed' => 1)
            , 'textarea'  => array('akIsSearchable' => 1, 'akIsSearchableIndexed' => 1, 'akTextareaDisplayMode' => null, 'akTextareaDisplayModeCustomOptions' => null)
            , 'select'    => array('akIsSearchable' => 1, 'akIsSearchableIndexed' => 1, 'akSelectAllowMultipleValues' => 0, 'akSelectOptionDisplayOrder' => 'display_asc', 'akSelectAllowOtherValues' => 1)
            , 'address'   => array('akIsSearchable' => 1, 'akIsSearchableIndexed' => 1, 'akHasCustomCountries' => 0, 'akDefaultCountry' => null)
            , 'boolean'   => array('akIsSearchable' => 1, 'akIsSearchableIndexed' => 1, 'akCheckedByDefault' => 0)
            , 'date_time' => array('akIsSearchable' => 1, 'akIsSearchableIndexed' => 1, 'akDateDisplayMode' => null)
        );

    protected static $categoryAttributes
                           = array(
            'collection' => array(
                'set' => array(
                    'handle' => null
                    , 'name' => null
                    , 'keys' => array(
                        'my_text_attribute_handle'     => array('name'       => 'My Text Attribute', 'type' => 'text',
                                                                'properties' => array()
                                                                , 'options'  => array())
                        , 'my_select_attribute_handle' => array('name'       => 'My Select Attribute', 'type' => 'select',
                                                                'properties' => array()
                                                                , 'options'  => array('option 1', 'Option 2', 'Foo'))
                    )
                ),
                /*'set' => array(
                    'handle' => 'package_starting_point'
                    , 'name' => 'Package Starting Point'
                    , 'keys' => array(
                        'second_text_attribute_handle'  => array('name'       => 'Second Text Attribute', 'type' => 'text',
                                                                 'properties' => array()
                                                                 , 'options'  => array())
                        , 'my_address_attribute_handle' => array('name'       => 'My Address', 'type' => 'address',
                                                                 'properties' => array()
                                                                 , 'options'  => array())
                    )
                )*/
            )
        );
    protected static $blocks
                           = array(
            array('path' => '/dashboard/package_starting_point', 'name' => 'Package Starting Point', 'description' => 'This is a starting point for a new concrete5.7 package.')
        );
    protected static $pages
                           = array(
            array('path' => '/dashboard/package_starting_point', 'name' => 'Package Starting Point', 'description' => 'This is a starting point for a new concrete5.7 package.')
        );
    protected static $settings
                           = array(
            array('handle' => 'my_setting', 'value' => 'default value for this setting')
        );
    protected static $jobs = array('package_starting_point_job_handle');

    /**
     * @throws Exception
     */
    public static function preInstallationCheck()
    {
        $preInstallationErrors = array();
        if (isset(self::$preInstallationRequirements) && is_array(self::$preInstallationRequirements)) {
            $checks = (new self)->getInstallationCheckMethodNames();
            foreach (self::$preInstallationRequirements as $requirement => $requrementOptions) {
                if (in_array($requirement, $checks, true)) {
                    if (call_user_func(__CLASS__ . '\\Checks::' . $requirement)) {
                        $preInstallationErrors[] = array($requrementOptions['errorResponse']['title'], $requrementOptions['errorResponse']['description']);
                    }
                } else {
                    $preInstallationErrors[] = array(t('Requirement method not found.'), "The method 'Checks::" . $requirement . "' was not found.");
                }
            }
        }

        if (count($preInstallationErrors) > 0) {
            $exception = '';
            foreach ($preInstallationErrors as $preInstallationError) {
                $exception .= '<p><b>' . $preInstallationError[0] . '</b><br>' . $preInstallationError[1] . '</p>';
            }
            throw new RuntimeException($exception);
        }

        return true;
    }

    public static function install($pkg)
    {
        self::run($pkg);
    }

    public static function upgrade($pkg)
    {
        self::run($pkg);
    }

    private static function run($pkg){
        (new self)->installAttributeTypes($pkg);
        (new self)->installCategories($pkg);
        (new self)->associateAttributesTypesWithCategories($pkg);
        (new self)->installCategoryAttributeSets($pkg);
        (new self)->installCategoryAttributes($pkg);
        (new self)->installBlocks($pkg);
        (new self)->installPages($pkg);
        (new self)->installSettingsData($pkg);
        (new self)->installJobs($pkg);
        (new self)->installAdditional($pkg);
    }

    /**
     * @throws ReflectionException
     */
    private function getInstallationCheckMethodNames()
    {
        $reflection = new ReflectionClass(__CLASS__ . '\\Checks');
        $methods = $reflection->getMethods(ReflectionMethod::IS_STATIC);
        $methodNames = array();
        foreach ($methods as $method) {
            $methodNames[] = $method->name;
        }
        return $methodNames;
    }

    private function getDatabase()
    {
        return Database::getActiveConnection();
    }

    private function installAttributeTypes($pkg)
    {
        foreach (self::$attributeTypes as $attributeHandle => $meta) {
            $attributeType = AttributeType::getByHandle($attributeHandle);
            if (!$attributeType->atID) {
                AttributeType::add($attributeHandle, $meta['name'], $pkg);
            }
        }
    }

    private function installCategories($pkg)
    {
        foreach (self::$attributeCategories as $categoryHandle => $categoryOptions) {
            $attributeKeyCategory = AttributeKeyCategory::getByHandle($categoryHandle);
            if (!is_object($attributeKeyCategory)) {
                AttributeKeyCategory::add($categoryHandle, isset($categoryOptions['allowSets']) ? $categoryOptions['allowSets'] : 1, $pkg);
            }
        }
    }

    /** @noinspection PhpUnusedParameterInspection */
    private function associateAttributesTypesWithCategories($pkg)
    {
        foreach (self::$categoryAttributes as $category => $categorySets) {
            $attributeKeyCategory = AttributeKeyCategory::getByHandle($category);
            foreach ($categorySets as $categorySet) {
                foreach ($categorySet['keys'] as $categoryAttribute) {
                    $attributeType = AttributeType::getByHandle($categoryAttribute['type']);
                    if (!$attributeType->isAssociatedWithCategory($attributeKeyCategory)) {
                        $attributeKeyCategory->associateAttributeKeyType($attributeType);
                    }
                }
            }
        }
    }

    private function installCategoryAttributeSets($pkg)
    {
        foreach (self::$categoryAttributes as $category => $categorySets) {
            $attributeKeyCategory = AttributeKeyCategory::getByHandle($category);
            foreach ($categorySets as $categorySet) {
                if ($categorySet['handle']) {
                    $categoryAttributeSet = AttributeSet::getByHandle($categorySet['handle']);
                    if (!is_object($categoryAttributeSet)) {
                        /** @noinspection PhpUnusedLocalVariableInspection */
                        $categoryAttributeSet = $attributeKeyCategory->addSet($categorySet['handle'], t($categorySet['name']), $pkg);
                    }
                }
            }
        }
    }

    private function installCategoryAttributes($pkg)
    {
        $textHelper = new Text();
        foreach (self::$categoryAttributes as $categoryHandle => $categorySets) {
            $categoryClass = "\\Concrete\\Core\\Attribute\\Key\\" . $textHelper->camelcase($categoryHandle) . "Key";
            foreach ($categorySets as $categorySet) {
                foreach ($categorySet['keys'] as $categoryAttributeHandle => $meta) {
                    $attributeType = AttributeType::getByHandle($meta['type']);
                    $properties = array('akHandle' => $categoryAttributeHandle, 'akName' => t($meta['name']));
                    foreach (array('default', $meta['type']) as $type) {
                        if (isset(self::$defaultAttributeTypeProperties[$type]) && is_array(self::$defaultAttributeTypeProperties[$type])) {
                            $properties = array_merge($properties, self::$defaultAttributeTypeProperties[$type]);
                        }
                    }
                    if (isset($meta['properties']) && is_array($meta['properties'])) {
                        $properties = array_merge($properties, $meta['properties']);
                    }

                    $categoryAttribute = call_user_func($categoryClass . '::getByHandle', $categoryAttributeHandle);
                    if (!$categoryAttribute) {
                        $categoryAttribute = call_user_func($categoryClass . '::add', $attributeType, $properties, $pkg);
                    } else {
                        $categoryAttribute->update($properties);
                    }
                    if ($meta['type'] === 'select' && $meta['options']) {
                        if (!is_array($meta['options'])) {
                            $meta['options'] = array($meta['options']);
                        }
                        foreach ($meta['options'] as $option) {
                            if (!Option::getByValue($option, $categoryAttribute)) {
                                Option::add($categoryAttribute, $option);
                            }
                        }
                    }
                    $categoryAttributeSet = $categorySet['handle'] ? AttributeSet::getByHandle($categorySet['handle']) : false;
                    if ($categoryAttributeSet) {
                        $categoryAttribute->setAttributeSet($categoryAttributeSet);
                    }
                }
            }
        }
    }

    private function installBlocks($pkg)
    {
        if (isset(self::$blocks) && is_array(self::$blocks)) {
            foreach (self::$blocks as $block) {
                if (!BlockType::getByHandle($block)) {
                    BlockType::installBlockTypeFromPackage($block, $pkg);
                }
            }
        }
    }

    private function installPages($pkg)
    {
        $db = self::getDatabase();

        if (isset(self::$pages) && is_array(self::$pages)) {
            foreach (self::$pages as $page) {
                if (is_array($page) && isset($page['path'])) {
                    $existingPage = Page::getByPath($page['path']);
                    if (!$existingPage->cID) {
                        $newPage = SinglePage::add($page['path'], $pkg);
                        $pageName = $page['name'];
                        if (!$pageName) {
                            $pathParts = explode('/', $page['path']);
                            $pageName = str_replace('_', ' ', strtoupper(array_pop($pathParts)));
                        }
                        $pageDescription = $page['description'] ?: '';
                        $newPage->update(array('cName' => t($pageName), 'cDescription' => t($pageDescription)));
                        $db->query("UPDATE Pages SET cFilename=? WHERE cID = ?", array($page['path'] . '.php', $newPage->cID));
                    }
                }
            }
        }
    }

    /** @noinspection PhpUnusedParameterInspection */
    private function installSettingsData($pkg)
    {
        $db = self::getDatabase();
        foreach (self::$settings as $setting) {
            $handle = $db->GetOne("SELECT handle FROM aoPackageStartingPointSettings WHERE handle='" . $setting['handle'] . "'");
            if (!$handle) {
                $sql = "INSERT INTO aoPackageStartingPointSettings (" . implode(', ', array_keys($setting)) . ") VALUES (:" . implode(", :", array_keys($setting)) . ")";
                $db->Execute($sql, $setting);
            }
        }
    }

    private function installJobs($pkg)
    {
        foreach (self::$jobs as $job) {
            if (!Job::getByHandle($job)) {
                Job::installByPackage($job, $pkg);
            }
        }
    }

    /** @noinspection PhpUnusedParameterInspection */
    private function installAdditional($pkg)
    {
        self::getDatabase();

        /*
         * More installation procedures go here
         */
    }

}
