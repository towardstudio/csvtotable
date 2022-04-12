<?php
namespace bluegg\csvtotable\fields;

use Craft;

use craft\fields\Assets;

use yii\db\Schema;

class CSVToTableField extends Assets
{

	public $restrictFiles = true;

	public $allowedKinds = ['excel'];

	public $allowUploads = true;

	/**
    	* @inheritdoc
    */
    protected $settingsTemplate = 'csvtotable/field/_settings';

	/**
    	* @inheritdoc
    */
    public static function displayName(): string
    {
        return Craft::t("csvtotable", "CSV Table");
    }

}
