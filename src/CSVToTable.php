<?php
namespace towardstudio\csvtotable;

use Craft;
use craft\base\Plugin;
use craft\base\Model;
use craft\events\RegisterUrlRulesEvent;
use craft\events\RegisterTemplateRootsEvent;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterCpNavItemsEvent;
use craft\services\Fields;
use craft\helpers\UrlHelper;
use craft\web\UrlManager;
use craft\web\twig\variables\Cp;
use craft\web\View;

use towardstudio\csvtotable\models\Settings;
use towardstudio\csvtotable\fields\CSVToTableField;
use towardstudio\csvtotable\twigextensions\CsvToTableTwigExtension;

use yii\base\Event;

/**
 * @author    towardstudio
 * @package   CSVToTable
 * @since     1.0.0
 *
 */
class CSVToTable extends Plugin
{
    public static ?CSVToTable $plugin;

    public bool $hasCpSection = true;
    public bool $hasCpSettings = true;
    public static ?Settings $settings;

    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();
        self::$plugin = $this;
        self::$settings = $this->getSettings();

        Craft::setAlias("@bar", __DIR__);

        $this->_registerControlPanel();
        $this->_registerFieldTypes();
        $this->_registerExtensions();

        // Handler: UrlManager::EVENT_REGISTER_CP_URL_RULES
        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function (
            RegisterUrlRulesEvent $event
        ) {
            Craft::debug("UrlManager::EVENT_REGISTER_CP_URL_RULES", __METHOD__);
            // Register our Control Panel routes
            $event->rules = array_merge($event->rules, $this->customAdminCpRoutes());
        });

        Event::on(Cp::class, Cp::EVENT_REGISTER_CP_NAV_ITEMS, function (
            RegisterCpNavItemsEvent $event
        ) {
            if (Craft::$app->config->general->devMode) {
                return;
            }
            $csvPlugin = array_search("csvtotable", array_column($event->navItems, "url"));
            if ($csvPlugin === false) {
                return;
            }
            unset($event->navItems[$csvPlugin]);
        });

        Craft::info(
            Craft::t("csvtotable", "{name} plugin loaded", [
                "name" => $this->name,
            ]),
            __METHOD__
        );
    }

    /**
     * Registers the field type provided by this plugin.
     * @param RegisterComponentTypesEvent $event The event.
     * @return void
     */
    public function registerFieldTypes(RegisterComponentTypesEvent $event)
    {
        $event->types[] = CSVToTableField::class;
    }

    // Rename the Control Panel Item & Add Sub Menu
    public function getCpNavItem(): ?array
    {
        // Set additional information on the nav item
        $item = parent::getCpNavItem();

        $item["label"] = "CSV Table";
        $item["icon"] = "@bar/icon/table.svg";

        // Create SubNav
        $subNavs = [];
        $subNavs["settings"] = [
            "label" => "Settings",
            "url" => "csvtotable/settings",
        ];

        $item = array_merge($item, [
            "subnav" => $subNavs,
        ]);

        return $item;
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel(): ?Model
    {
        return new Settings();
    }

    public function getSettingsResponse(): mixed
    {
        // Just redirect to the plugin settings page
        return Craft::$app->getResponse()->redirect(UrlHelper::cpUrl("csvtotable/settings"));
    }

    /**
     * Return the custom Control Panel routes
     *
     * @return array
     */
    protected function customAdminCpRoutes(): array
    {
        return [
            "csvtotable/settings" => "csvtotable/settings/plugin-settings",
            "csvtotable" => "csvtotable/settings/plugin-settings",
        ];
    }

    // Private Methods
    // =========================================================================

    private function _registerControlPanel()
    {
        Event::on(View::class, View::EVENT_REGISTER_CP_TEMPLATE_ROOTS, function (
            RegisterTemplateRootsEvent $e
        ) {
            if (is_dir($baseDir = $this->getBasePath() . DIRECTORY_SEPARATOR . "templates")) {
                $e->roots[$this->id] = $baseDir;
            }
        });
    }

    private function _registerFieldTypes()
    {
        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_TYPES, [$this, "registerFieldTypes"]);
    }

    private function _registerExtensions()
    {
        Craft::$app->view->registerTwigExtension(new CsvToTableTwigExtension());
    }
}
