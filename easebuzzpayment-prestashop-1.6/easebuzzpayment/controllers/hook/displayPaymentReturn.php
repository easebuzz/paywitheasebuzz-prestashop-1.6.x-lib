<?php

class EasebuzzPaymentDisplayPaymentReturnController {

    public function __construct($module, $file, $path) {

        $this->file = $file;
        $this->module = $module;
        $this->context = Context::getContext();
        $this->_path = $path;
    }

    public function run($params) {

        if ($params['objOrder']->payment != $this->module->displayName)
            return '';

        $reference = $params['objOrder']->id;
        if (isset($params['objOrder']->reference) && !empty($params['objOrder']->reference))
            $reference = $params['objOrder']->reference;

        $total_to_pay = Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false);

        $this->context->smarty->assign(array(
            'reference' => $reference,
            'current_state' => $params['objOrder']->current_state,
            'total_to_pay' => $total_to_pay,
        ));
        if ($params['objOrder']->current_state == Configuration::get('PS_OS_PAYMENT')) {
            return $this->module->display($this->file, 'displayPaymentReturn.tpl');
        } elseif ($params['objOrder']->current_state == Configuration::get('PS_OS_CANCELED')) {
            return $this->module->display($this->file, 'displayPaymentCancelReturn.tpl');
        } else {
            return $this->module->display($this->file, 'displayPaymentErrorReturn.tpl');
        }
    }

}
