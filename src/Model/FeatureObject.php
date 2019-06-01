<?php

namespace Dynamic\Elements\Features\Model;

use Dynamic\BaseObject\Model\BaseElementObject;
use Dynamic\Elements\Features\Elements\ElementFeatures;
use SilverStripe\Forms\FieldList;

/**
 * Class PageSectionObject.
 *
 * @property int $Sort
 * @property int $ElementFeaturesID
 */
class FeatureObject extends BaseElementObject
{
    /**
     * @var array
     */
    private static $db = array(
        'Sort' => 'Int',
    );

    /**
     * @var array
     */
    private static $has_one = array(
        'ElementFeatures' => ElementFeatures::class,
    );

    /**
     * @var string
     */
    private static $table_name = 'FeatureObject';

    /**
     * @var array
     */
    private static $summary_fields = [
        'Summary',
    ];

    /**
     * @var string
     */
    private static $default_sort = 'Sort';

    /**
     * @return FieldList
     *
     * @throws \Exception
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function ($fields) {
            $fields->removeByName(array(
                'ElementFeaturesID',
                'Sort',
            ));

            $fields->dataFieldByName('Image')
                ->setFolderName('Uploads/Elements/Features');
        });

        return parent::getCMSFields();
    }

    /**
     * @return null
     */
    public function getPage()
    {
        $page = null;

        if ($this->ElementFeatures()) {
            if ($this->ElementFeatures()->hasMethod('getPage')) {
                $page = $this->ElementFeatures()->getPage();
            }
        }

        return $page;
    }

    /**
     * @return mixed
     */
    public function getSummary()
    {
        return $this->dbObject('Content')->Summary(20);
    }
}
