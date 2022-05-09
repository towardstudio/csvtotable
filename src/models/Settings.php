<?php
namespace bluegg\csvtotable\models;

use bluegg\csvtotable\CSVToTable;

use Craft;
use craft\base\Model;

class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */

    public $name = "CSV to Table";
    public $tableClass;
    public $theadClass;
    public $tbodyClass;
    public $trClass;
    public $thClass;
    public $tdClass;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [["tableClass", "theadClass", "tbodyClass", "trClass", "thClass", "tdClass"], "string"],
        ];
    }
}
