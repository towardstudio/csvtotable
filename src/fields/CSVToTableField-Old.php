<?php
namespace bluegg\csvtotable\fields;

use Craft;

use craft\base\Field;
use craft\base\ElementInterface;
use craft\fields\Assets;
use craft\helpers\Html;

use bluegg\csvtotable\elements\CSVTableElement;

use yii\db\Schema;

class CSVToTableField extends Assets
{
	/**
     * @inheritdoc
     */
    protected static function elementType(): string
    {
        return CSVTableElement::class;
    }

	/**
    	* @inheritdoc
    */
    protected $settingsTemplate = 'csvtotable/_settings';

	/**
    	* @inheritdoc
     */
    protected $inputTemplate = 'csvtotable/_input';

    /**
    	* @inheritdoc
    */
    protected $inputJsClass = 'Craft.AssetSelectInput';

	/**
    	* @inheritdoc
    */
    public static function displayName(): string
    {
        return Craft::t("csvtotable", "CSV Table");
    }

	/**
    	* @inheritdoc
    */
    public function getSettingsHtml()
    {
        $this->singleUploadLocationSource = $this->_volumeSourceToFolderSource($this->singleUploadLocationSource);
        $this->defaultUploadLocationSource = $this->_volumeSourceToFolderSource($this->defaultUploadLocationSource);

        if (is_array($this->sources)) {
            foreach ($this->sources as &$source) {
                $source = $this->_volumeSourceToFolderSource($source);
            }
        }

        return parent::getSettingsHtml();
    }

	/**
     * @inheritdoc
     */
    protected function inputHtml($value, ElementInterface $element = null): string
    {
		echo '<pre>';
			var_dump($value);
		echo '</pre>';

        try {
            return parent::inputHtml($value, $element);
        } catch (InvalidSubpathException $e) {
            return Html::tag('p', Craft::t('app', 'This field’s target subfolder path is invalid: {path}', [
                'path' => '<code>' . $this->singleUploadLocationSubpath . '</code>',
            ]), [
                'class' => ['warning', 'with-icon'],
            ]);
        } catch (InvalidVolumeException $e) {
            return Html::tag('p', $e->getMessage(), [
                'class' => ['warning', 'with-icon'],
            ]);
        }
    }

	/**
     * Convert a volume:UID source key to a folder:UID source key.
     *
     * @param mixed $sourceKey
     * @return string
     */
    private function _volumeSourceToFolderSource($sourceKey): string
    {
        if ($sourceKey && is_string($sourceKey) && strpos($sourceKey, 'volume:') === 0) {
            $parts = explode(':', $sourceKey);
            $volume = Craft::$app->getVolumes()->getVolumeByUid($parts[1]);

            if ($volume && $folder = Craft::$app->getAssets()->getRootFolderByVolumeId($volume->id)) {
                return 'folder:' . $folder->uid;
            }
        }

        return (string)$sourceKey;
    }

}
