<?php

namespace Dynamic\Elements\Features\Elements;

use DNADesign\Elemental\Models\BaseElement;
use Dynamic\Elements\Features\Model\FeatureObject;
use SilverStripe\Forms\CompositeField;
use SilverStripe\Forms\FieldGroup;
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
     * @param bool $includerelations
     * @return array
     */
    public function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels($includerelations);

        $labels['Content'] = _t(__CLASS__.'.ContentLabel', 'Intro');
        $labels['Alternate'] = _t(__CLASS__ . '.AlternateLabel', 'Alternate Layout');
        $labels['Features'] = _t(__CLASS__ . '.FeaturesLabel', 'Features');

        return $labels;
    }

    /**
     * @return \SilverStripe\Forms\FieldList
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $fields->dataFieldByName('Content')
                ->setTitle($this->fieldLabel('Content'))
                ->setRows(5);

            $alternate = $fields->dataFieldByName('Alternate');
            $fields->removeByName('Alternate');

            $fields->addFieldToTab(
                'Root.Main',
                CompositeField::create(
                    $alternate
                        ->setTitle($this->fieldLabel('Alternate'))
                        ->setDescription(_t(
                            __CLASS__ . '.AlternateDescription',
                            'alternate image and text alignment - first feature: image left, copy right; 
                                second feature: image right, copy left;'
                        ))
                )->setTitle(_t(
                    __CLASS__ . '.LayoutLabel',
                    'Layout'
                ))
            );

            if ($this->ID) {
                // Features
                $features = $fields->dataFieldByName('Features');
                $fields->removeByName('Features');

                $config = $features->getConfig();
                $config
                    ->addComponent(new GridFieldOrderableRows())
                    ->removeComponentsByType([
                        GridFieldAddExistingAutocompleter::class,
                        GridFieldDeleteAction::class
                    ]);

                $fields->addFieldToTab('Root.Main', $features);
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
        $count = $this->Features()->count();
        $label = _t(
            FeatureObject::class . '.PLURALS',
            'A Feature|{count} Features',
            [ 'count' => $count ]
        );
        return DBField::create_field('HTMLText', $label)->Summary(20);
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
