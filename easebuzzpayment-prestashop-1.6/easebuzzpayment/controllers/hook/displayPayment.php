<?php

class EasebuzzPaymentDisplayPaymentController {

    public function __construct($module, $file, $path) {
        $this->file = $file;
        $this->module = $module;
        $this->context = Context::getContext();
        $this->_path = $path;
    }

    public function run($params) {

        // status
        $cart = $this->context->cart;
        if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active)
            Tools::redirect('index.php?controller=order&step=1');

        // Check if module is enabled
        $authorized = false;
        foreach (Module::getPaymentModules() as $module)
            if ($module['name'] == $this->module->name)
                $authorized = true;
        if (!$authorized)
            die('This payment method is not available.');


        $shop = new Shop(Configuration::get('PS_SHOP_DEFAULT'));

        $api_url =Context::getContext()->link->getModuleLink('easebuzzpayment', 'CreateOrder') ;
        $this->context->smarty->assign('api_url', $api_url);
        $this->context->smarty->assign('id_cart',$this->context->cart->id );
   
        $this->context->controller->addCSS($this->_path . 'views/css/easebuzzpayment.css', 'all');
        return $this->module->display($this->file, 'displayPayment.tpl');
    }

}
