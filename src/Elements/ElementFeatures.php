<?php

namespace Dynamic\Elements\Features\Elements;

use DNADesign\Elemental\Models\BaseElement;
use Dynamic\Elements\Features\Model\FeatureObject;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\DBHTMLText;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

/**
 * Class PageSectionBlock.
 *
 * @method HasManyList $Features
 */
class ElementFeatures extends BaseElement
{
    /**
     * @var string
     */
    private static $icon = 'font-icon-block-banner';

    /**
     * @return string
     */
    private static $singular_name = 'Features Element';

    /**
     * @return string
     */
    private static $plural_name = 'Features Elements';

    /**
     * @var string
     */
    private static $table_name = 'ElementFeatures';

    /**
     * @var array
     */
    private static $db = [
        'Content' => 'HTMLText',
        'Alternate' => 'Boolean',
    ];

    /**
     * @var array
     */
    private static $has_many = [
        'Features' => FeatureObject::class,
    ];

    /**
     * Set to false to prevent an in-line edit form from showing in an elemental area. Instead the element will be
     * clickable and a GridFieldDetailForm will be used.
     *
     * @config
     * @var bool
     */
    private static $inline_editable = false;

    /**
     * @return \SilverStripe\Forms\FieldList
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $fields->dataFieldByName('Content')
                ->setRows(8);
            $fields->dataFieldByName('Alternate')->setTitle('Alternate images and text');

            if ($this->ID) {
                // Features
                $features = $fields->dataFieldByName('Features');
                $config = $features->getConfig();
                $config->addComponent(new GridFieldOrderableRows());
                $config->removeComponentsByType(GridFieldAddExistingAutocompleter::class);
                $config->removeComponentsByType(GridFieldDeleteAction::class);
            }
        });

        return parent::getCMSFields();
    }

    /**
     * @return mixed
     */
    public function getFeaturesList()
    {
        return $this->Features()->sort('Sort');
    }

    /**
     * @return DBHTMLText
     */
    public function getSummary()
    {
        if ($this->Features()->count() == 1) {
            $feature = 'feature';
        } else {
            $feature = 'features';
        }
        return DBField::create_field('HTMLText', $this->Features()->count() . ' ' . $feature)->Summary(20);
    }

    /**
     * @return array
     */
    protected function provideBlockSchema()
    {
        $blockSchema = parent::provideBlockSchema();
        $blockSchema['content'] = $this->getSummary();
        return $blockSchema;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return _t(__CLASS__.'.BlockType', 'Features');
    }
}
