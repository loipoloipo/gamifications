<?php
/**
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 * @license   Addons PrestaShop license limitation
 */

/**
 * Class GamificationsInstaller
 */
class GamificationsInstaller extends AbstractGamificationsInstaller
{
    /**
     * @var Gamifications
     */
    private $module;

    /**
     * @var GamificationsDbInstaller
     */
    private $dbInstaller;

    /**
     * GamificationInstaller constructor.
     *
     * @param Gamifications $module
     */
    public function __construct(Gamifications $module)
    {
        $this->module = $module;
        $this->dbInstaller = new GamificationsDbInstaller($module);
    }

    /**
     * Install module (register hooks, install tabs & etc)
     *
     * @return bool
     */
    public function install()
    {
        if (!$this->registerHooks()) {
            return false;
        }

        if (!$this->installTabs()) {
            return false;
        }

        if (!$this->dbInstaller->install()) {
            return false;
        }

        if (!$this->installConfiguration()) {
            return false;
        }

        return true;
    }

    /**
     * Uninstall module
     *
     * @return bool
     */
    public function uninstall()
    {
        if (!$this->uninstallTabs()) {
            return false;
        }

        if (!$this->dbInstaller->uninstall()) {
            return false;
        }

        if (!$this->uninstallConfiguration()) {
            return false;
        }

        return true;
    }

    /**
     * Get module name
     *
     * @return string
     */
    protected function getModuleName()
    {
        return $this->module->name;
    }

    /**
     * Definition of tabs to install
     *
     * @return array
     */
    protected function tabs()
    {
        return [
            [
                'name' => $this->module->getTranslator()->trans('Gamification', [], 'Modules.Gamifications'),
                'parent' => 'IMPROVE',
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_MODULE_CONTROLLER,
            ],
            [
                'name' => $this->module->getTranslator()->trans('Rewards', [], 'Modules.Gamifications'),
                'parent' => Gamifications::ADMIN_GAMIFICATIONS_MODULE_CONTROLLER,
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_REWARD_CONTROLLER,
            ],
            [
                'name' => $this->module->getTranslator()->trans('Points exchange', [], 'Modules.Gamifications'),
                'parent' => Gamifications::ADMIN_GAMIFICATIONS_MODULE_CONTROLLER,
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_POINT_EXCHANGE_CONTROLLER,
            ],
            [
                'name' => $this->module->getTranslator()->trans('Activities', [], 'Modules.Gamifications'),
                'parent' => Gamifications::ADMIN_GAMIFICATIONS_MODULE_CONTROLLER,
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_ACTIVITY_CONTROLLER,
            ],
            [
                'name' => $this->module->getTranslator()->trans('Daily rewards', [], 'Modules.Gamifications'),
                'parent' => Gamifications::ADMIN_GAMIFICATIONS_ACTIVITY_CONTROLLER,
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_DAILY_REWARDS_CONTROLLER,
            ],
            [
                'name' => $this->module->getTranslator()->trans('Referral program', [], 'Modules.Gamifications'),
                'parent' => Gamifications::ADMIN_GAMIFICATIONS_ACTIVITY_CONTROLLER,
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_REFERRAL_CONTROLLER,
            ],
            [
                'name' => $this->module->getTranslator()->trans('Shopping points', [], 'Modules.Gamifications'),
                'parent' => Gamifications::ADMIN_GAMIFICATIONS_ACTIVITY_CONTROLLER,
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_SHOPPING_POINT_CONTROLLER,
            ],
            [
                'name' => $this->module->getTranslator()->trans('Stats', [], 'Modules.Gamifications'),
                'parent' => Gamifications::ADMIN_GAMIFICATIONS_MODULE_CONTROLLER,
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_STATS_CONTROLLER,
            ],
            [
                'name' => $this->module->getTranslator()->trans('Customers', [], 'Modules.Gamifications'),
                'parent' => Gamifications::ADMIN_GAMIFICATIONS_STATS_CONTROLLER,
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_CUSTOMER_CONTROLLER,
            ],
            [
                'name' => $this->module->getTranslator()->trans('Activities history', [], 'Modules.Gamifications'),
                'parent' => Gamifications::ADMIN_GAMIFICATIONS_STATS_CONTROLLER,
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_ACTIVITY_HISTORY_CONTROLLER,
            ],
            [
                'name' => $this->module->getTranslator()->trans('Preferences', [], 'Modules.Gamifications'),
                'parent' => Gamifications::ADMIN_GAMIFICATIONS_MODULE_CONTROLLER,
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_PREFERENCE_CONTROLLER,
            ],
        ];
    }

    /**
     * Register module hooks
     *
     * @return bool
     */
    private function registerHooks()
    {
        $hooks = [
            'moduleRoutes',
            'displayCustomerAccount',
            'actionObjectCustomerAddAfter',
            'actionObjectOrderAddAfter',
            'actionObjectOrderUpdateAfter',
        ];

        foreach ($hooks as $hookName) {
            if (!$this->module->registerHook($hookName)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Install default configuration
     *
     * @return bool
     */
    private function installConfiguration()
    {
        $configuration = GamificationsConfig::getDefaultConfiguration();

        foreach ($configuration as $name => $value) {
            if (!Configuration::updateValue($name, $value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Uninstall configuration
     *
     * @return bool
     */
    private function uninstallConfiguration()
    {
        $configuration = array_keys(GamificationsConfig::getDefaultConfiguration());

        foreach ($configuration as $name) {
            if (!Configuration::deleteByName($name)) {
                return false;
            }
        }

        return true;
    }
}
