<?php

class EasebuzzPaymentValidationAPIModuleFrontController extends ModuleFrontController {

    public function postProcess() {

        $cart = new Cart((int) Cart::getCartIdByOrderId(Tools::getValue('txnid')));

        if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 ||
                $cart->id_address_invoice == 0 || !$this->module->active)
            $this->returnError('Invalid cart');

        $customer = new Customer($cart->id_customer);
        if (!Validate::isLoadedObject($customer))
            $this->returnError('Invalid customer');


        $txnid = Tools::getValue('txnid');
        //Update Log
        $where = "`order_id` = $txnid";
        Db::getInstance()->update('ease_buzz_debug', array(
            'response_debug_at' => pSQL(date("YmdHis", time())),
            'response_body' => pSQL(json_encode($_POST)),
                ), $where);

        $key = Configuration::get('EASEBUZZ_API_CRED_ID');
        $salt = Configuration::get('EASEBUZZ_API_CRED_SALT');
        $status = Tools::getValue('status');
        $udf1 = Tools::getValue('udf1');
        $udf2 = Tools::getValue('udf2');
        $udf3 = Tools::getValue('udf3');
        $udf4 = Tools::getValue('udf4');
        $udf5 = Tools::getValue('udf5');
        $email = Tools::getValue('email');
        $firstname = Tools::getValue('firstname');
        $productinfo = Tools::getValue('productinfo');
        $amount = Tools::getValue('amount');

        $key = Tools::getValue('key');
        $responcehase = Tools::getValue('hash');

        if (Tools::getValue('status') == 'success') {
            $responce_info = $salt . '|' . $status . '||||||' . $udf5 . '|' . $udf4 . '|' . $udf3 . '|' . $udf2 . '|' . $udf1 . '|' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
            $hase = hash('SHA512', $responce_info);

            if ($hase == $responcehase) {

                $shop = new Shop(Configuration::get('PS_SHOP_DEFAULT'));
                $return_url = Tools::getShopProtocol() . $shop->domain . $shop->getBaseURI();

                $currency = new Currency((int) $cart->id_currency);
                $total_paid = Tools::getValue('amount');

                $successtatus = Configuration::get('PS_OS_PAYMENT');
                $order = new Order($txnid);
                $order->setCurrentState($successtatus);

                $this->returnUrl($return_url . 'index.php?controller=order-confirmation&id_cart=' . $cart->id . '&id_module=' . $this->module->id . '&id_order=' . $cart->id . '&key=' . $customer->secure_key);
            } else {
                die('Error : Encryption does not match!!');
            }
        } elseif (Tools::getValue('status') == 'userCancelled') {
            //userCancelled
            $cancel_status = Configuration::get('PS_OS_CANCELED');

            $order = new Order($txnid);
            $order->setCurrentState($cancel_status);

            $this->returnUrl($return_url . 'index.php?controller=order-confirmation&id_cart=' . $cart->id . '&id_module=' . $this->module->id . '&id_order=' . $cart->id . '&key=' . $customer->secure_key);
        } else {
            //failure

            $failstatus = Configuration::get('PS_OS_ERROR');

            $order = new Order($txnid);
            $order->setCurrentState($failstatus);

            $this->returnUrl($return_url . 'index.php?controller=order-confirmation&id_cart=' . $cart->id . '&id_module=' . $this->module->id . '&id_order=' . $cart->id . '&key=' . $customer->secure_key);
        }
    }

    public function returnUrl($result) {

        header('Location: ' . $result);
        exit;
    }

}
