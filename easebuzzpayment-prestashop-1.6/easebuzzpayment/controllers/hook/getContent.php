<?php

class EasebuzzPaymentGetContentController {

    public function __construct($module, $file, $path) {
        $this->file = $file;
        $this->module = $module;
        $this->context = Context::getContext();
        $this->_path = $path;
    }

    public function processConfiguration() {
        if (Tools::isSubmit('easebuzzpayment_form')) {
            Configuration::updateValue('EASEBUZZ_API_CRED_ID', Tools::getValue('EASEBUZZ_API_CRED_ID'));
            Configuration::updateValue('EASEBUZZ_API_CRED_SALT', Tools::getValue('EASEBUZZ_API_CRED_SALT'));
            Configuration::updateValue('EASEBUZZ_ENVIRONMENT', Tools::getValue('EASEBUZZ_ENVIRONMENT'));
            $this->context->smarty->assign('confirmation', 'ok');
        }
    }

    public function renderForm() {
        $options = array(
            array(
                'label' => $this->module->l('Sandbox'),
                'id' => 'easebuzz_environment_sandbox',
                'value' => 'https://testpay.easebuzz.in/pay/secure'
            ),
            array(
                'label' => $this->module->l('Production'),
                'id' => 'easebuzz_environment_production',
                'value' => 'https://pay.easebuzz.in/pay/secure',
                'checked' => 'checked'
            ),
            
        );
        $inputs = array(
            array('name' => 'EASEBUZZ_API_CRED_ID', 'label' => $this->module->l('API credentials ID'), 'type' => 'text'),
            array('name' => 'EASEBUZZ_API_CRED_SALT', 'label' => $this->module->l('API credentials SALT'), 'type' => 'text'),
            array(
                'name' => 'EASEBUZZ_ENVIRONMENT',
                'label' => $this->module->l('Environment'),
                'type' => 'radio',
                'values' => $options,
                
            )
        );

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->module->l('Easebuzz payment configuration'),
                    'icon' => 'icon-wrench'
                ),
                'input' => $inputs,
                'submit' => array('title' => $this->module->l('Save'))
            )
        );

        $helper = new HelperForm();
        $helper->table = 'easebuzzpayment';
        $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = (int) Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
        $helper->submit_action = 'easebuzzpayment_form';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->module->name . '&tab_module=' . $this->module->tab . '&module_name=' . $this->module->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => array(
                'EASEBUZZ_API_CRED_ID' => Tools::getValue('EASEBUZZ_API_CRED_ID', Configuration::get('EASEBUZZ_API_CRED_ID')),
                'EASEBUZZ_API_CRED_SALT' => Tools::getValue('EASEBUZZ_API_CRED_SALT', Configuration::get('EASEBUZZ_API_CRED_SALT')),
                'EASEBUZZ_ENVIRONMENT' => Tools::getValue('EASEBUZZ_ENVIRONMENT', Configuration::get('EASEBUZZ_ENVIRONMENT')),
            ),
            'languages' => $this->context->controller->getLanguages()
        );

        return $helper->generateForm(array($fields_form));
    }

    public function run() {
        $this->processConfiguration();
        $html_confirmation_message = $this->module->display($this->file, 'getContent.tpl');
        $html_form = $this->renderForm();
        return $html_confirmation_message . $html_form;
    }

}
