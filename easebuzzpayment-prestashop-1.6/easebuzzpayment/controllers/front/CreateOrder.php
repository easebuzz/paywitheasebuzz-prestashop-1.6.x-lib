<?php

class EasebuzzPaymentCreateOrderModuleFrontController extends ModuleFrontController {

    public function initContent() {
        parent::initContent();

        $cart = new Cart((int) Tools::getValue('id_cart'));
        if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active) {
            echo "You are not allowed to acceess this URL..";
            die();
        }

        $customer = new Customer($cart->id_customer);
        if (!Validate::isLoadedObject($customer)) {
            echo "You are not allowed to acceess this URL...";
            die();
        }

        $currency = $this->context->currency;

        $total = (float) $cart->getOrderTotal(true, Cart::BOTH);
        $extra_vars = array(
            '{total_to_pay}' => Tools::displayPrice($total),
        );

        $this->module->validateOrder($cart->id, Configuration::get('PS_OS_EASEBUZZ_PAYMENT'), $total, $this->module->displayName, NULL, $extra_vars, (int) $currency->id, false, $customer->secure_key);


        $salt = Configuration::get('EASEBUZZ_API_CRED_SALT');
        $shop = new Shop(Configuration::get('PS_SHOP_DEFAULT'));
        $api_url = Configuration::get('EASEBUZZ_ENVIRONMENT');

        $key = Configuration::get('EASEBUZZ_API_CRED_ID');
        $salt = Configuration::get('EASEBUZZ_API_CRED_SALT');
        $txnid = $this->module->currentOrder;
        $amount = (float) $this->context->cart->getOrderTotal(true, Cart::BOTH);
        $productInfo = 'product name';
        $firstname = $this->context->customer->firstname;
        $address = new Address($this->context->cart->id_address_delivery);
        $phone = ($address->phone) ? $address->phone : $address->phone_mobile;
        $email = $this->context->customer->email;
        $surl = Context::getContext()->link->getModuleLink('easebuzzpayment', 'validationAPI') ;
        $furl = Context::getContext()->link->getModuleLink('easebuzzpayment', 'validationAPI') ;
        $udf1 = '';
        $udf2 = '';
        $udf3 = '';
        $udf4 = '';
        $udf5 = '';
        $address1 = preg_replace('/[^A-Za-z0-9\.\,\ ]/', ' ', $address->address1);
        $address2 = preg_replace('/[^A-Za-z0-9\.\,\ ]/', ' ', $address->address2);
        $city = $address->city;
        $state = State::getNameById($address->id_state);
        $country = $address->country;
        $zipcode = $address->postcode;
        $request_Info = $key . '|' . $txnid . '|' . $amount . '|' . $productInfo . '|' . $firstname . '|' . $email . '|' . $udf1 . '|' . $udf2 . '|' . $udf3 . '|' . $udf4 . '|' . $udf5 . '|' . '||||' . '|' . $salt;
        $hash = hash('SHA512', $request_Info);

        $this->context->smarty->assign('api_url', $api_url);
        $this->context->smarty->assign('key', $key);
        $this->context->smarty->assign('txnid', $txnid);
        $this->context->smarty->assign('amount', $amount);
        $this->context->smarty->assign('productInfo', $productInfo);
        $this->context->smarty->assign('firstname', $firstname);
        $this->context->smarty->assign('phone', $phone);
        $this->context->smarty->assign('email', $email);
        $this->context->smarty->assign('surl', $surl);
        $this->context->smarty->assign('furl', $furl);
        $this->context->smarty->assign('hash', $hash);
        $this->context->smarty->assign('udf1', $udf1);
        $this->context->smarty->assign('udf2', $udf2);
        $this->context->smarty->assign('udf3', $udf3);
        $this->context->smarty->assign('udf4', $udf4);
        $this->context->smarty->assign('udf5', $udf5);

        $this->context->smarty->assign('address1', $address1);
        $this->context->smarty->assign('address2', $address2);
        $this->context->smarty->assign('city', $city);
        $this->context->smarty->assign('state', $state);
        $this->context->smarty->assign('country', $country);
        $this->context->smarty->assign('zipcode', $zipcode);

        //Insert Log
        Db::getInstance()->insert('ease_buzz_debug', array(
            'order_id' => (int) $txnid,
            'request_debug_at' => pSQL(date("YmdHis", time())),
            'response_debug_at' => pSQL('2018-06-03 00:00:00'),
            'request_body' => pSQL(json_encode($request_Info)),
            'response_body' => pSQL('response_info'),
        ));
        $this->setTemplate('displayPaymentForm.tpl');
    }

}
