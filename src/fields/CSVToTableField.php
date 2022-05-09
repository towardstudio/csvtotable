<?php
namespace bluegg\csvtotable\fields;

use Craft;
use craft\fields\Assets;
use yii\db\Schema;

class CSVToTableField extends Assets
{
    public bool $restrictFiles = false;
    public bool $allowUploads = true;
    public ?int $maxRelations = 1;

    /**
     * @inheritdoc
     */
    protected string $settingsTemplate = "csvtotable/field/_settings";

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t("csvtotable", "CSV Table");
    }
}
