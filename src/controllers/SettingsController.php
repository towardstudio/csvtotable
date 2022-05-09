<?php
namespace bluegg\csvtotable\controllers;

use bluegg\csvtotable\CSVToTable;
use bluegg\csvtotable\models\Settings;

use Craft;
use craft\web\Controller;

use yii\web\Response;

class SettingsController extends Controller
{
    // Protected Properties
    // =========================================================================

    protected array|int|bool $allowAnonymous = [];

    // Public Methods
    // =========================================================================

    /**
     * Plugin settings
     *
     * @param null|bool|Settings $settings
     *
     * @return Response The rendered result
     */
    public function actionPluginSettings($settings = null): Response
    {
        if ($settings === null) {
            $settings = CSVToTable::$settings;
        }

        $variables = [];

        /** @var Settings $settings */
        $templateTitle = Craft::t("csvtotable", "Settings");

        $view = Craft::$app->getView();

        // Basic variables
        $variables["fullPageForm"] = true;
        $variables["selectedSubnavItem"] = "settings";
        $variables["settings"] = $settings;

        // Render the template
        return $this->renderTemplate("csvtotable/settings", $variables);
    }

    /**
     * Saves a plugin’s settings.
     *
     * @return Response|null
     * @throws NotFoundHttpException if the requested plugin cannot be found
     */
    public function actionSavePluginSettings()
    {
        $this->requirePostRequest();
        $settings = Craft::$app->getRequest()->getBodyParam("settings");
        $plugin = CSVToTable::$plugin;

        if ($plugin === null) {
            throw new NotFoundHttpException("Plugin not found");
        }

        if (!Craft::$app->getPlugins()->savePluginSettings($plugin, $settings)) {
            $this->setFailFlash(Craft::t("app", "Couldn’t save plugin settings."));

            // Send the plugin back to the template
            Craft::$app->getUrlManager()->setRouteParams([
                "plugin" => $plugin,
            ]);

            return null;
        }

        $this->setSuccessFlash(Craft::t("app", "Plugin settings saved."));
        return $this->redirectToPostedUrl();
    }
}
