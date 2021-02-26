<?php
/**
 * 2007-2021 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2021 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

use Delota\Prestashop\RoyalMailClickAndDrop\Services\Presta\ShippingOrderServiceFactory;
use Delota\Prestashop\RoyalMailClickAndDrop\Services\Presta\TrackingNumberServiceFactory;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

class royalmailclickanddrop extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'royalmailclickanddrop';
        $this->tab = 'shipping_logistics';
        $this->version = '0.0.1';
        $this->author = 'FuelRats';
        $this->need_instance = 0;

        /*
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('RoyalMail Click & Drop');
        $this->description = $this->l('Integrates orders with the RoyalMail Click & Drop API');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module?');

        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        if (PHP_VERSION_ID < 70200) {
            $this->_errors[] = 'Your PHP Version should be at least 7.2';

            return false;
        }

        if (extension_loaded('curl') == false) {
            $this->_errors[] = $this->l('You have to enable the cURL extension on your server to install this module');

            return false;
        }

        Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'royal_mail_order` (
              `id_order` int(11) NOT NULL,
              `id_royalmail` int(11) NOT NULL,
              PRIMARY KEY (`id_order`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;'
        );

        Configuration::updateValue('ROYALMAILCLICKANDDROP_AUTH_KEY', false);
        Configuration::updateValue('ROYALMAILCLICKANDDROP_CARRIER_ID', false);

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('actionOrderStatusUpdate') &&
            $this->registerHook('actionPaymentConfirmation');
    }

    public function uninstall()
    {
        Configuration::deleteByName('ROYALMAILCLICKANDDROP_CARRIER_ID');
        Configuration::deleteByName('ROYALMAILCLICKANDDROP_AUTH_KEY');

        Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'royal_mail_order`');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /*
         * If values have been submitted in the form, process.
         */
        if (((bool) Tools::isSubmit('submitRoyalMailClickAndDropModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        return $output . $this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitRoyalMailClickAndDropModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = [
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        return $helper->generateForm([$this->getConfigForm()]);
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return [
            'form' => [
                'legend' => [
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ],
                'input' => [
                    [
                        'type' => 'text',
                        'name' => 'ROYALMAILCLICKANDDROP_AUTH_KEY',
                        'label' => $this->l('Click & Drop Auth Key'),
                    ],
                    [
                        'type' => 'text',
                        'name' => 'ROYALMAILCLICKANDDROP_CARRIER_ID',
                        'label' => $this->l('ID of the carrier to enable this API for'),
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
        ];
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return [
            'ROYALMAILCLICKANDDROP_AUTH_KEY' => Configuration::get('ROYALMAILCLICKANDDROP_AUTH_KEY'),
            'ROYALMAILCLICKANDDROP_CARRIER_ID' => Configuration::get('ROYALMAILCLICKANDDROP_CARRIER_ID'),
        ];
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path . '/views/js/front.js');
        $this->context->controller->addCSS($this->_path . '/views/css/front.css');
    }

    public function hookActionPaymentConfirmation(array $params)
    {
        $registerOrderService = ShippingOrderServiceFactory::create();
        $registerOrderService->register($params['id_order']);
    }

    public function hookActionOrderStatusUpdate(array $params)
    {
        /** @var OrderState $newState */
        $newState = $params['newOrderStatus'];
        if (!$newState->shipped) {
            return;
        }

        $trackingService = TrackingNumberServiceFactory::create();
        $trackingService->tryRetrieveAndSet($params['id_order']);
    }
}
