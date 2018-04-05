{if $api_url ne ''}
    <div class="row">
        <div class="col-md-12">
            <p class="payment_module">
                <a href="#" id="easebuzzpayment-api-link" class="easebuzzpayment">
                    {l s='Please wait while you are being redirected to Easebuzz Payment Gatewat.' mod='easebuzzpayment'}
                </a>
            </p>
        </div>
    </div>

    <form action="{$api_url}" style="display:none" id="easebuzzpayment-api-form" method="POST">
        <input type="hidden" name="key" value="{$key}" />
        <input type="hidden" name="txnid" value="{$txnid}" />
        <input type="hidden" name="amount" value="{$amount}" />
        <input type="hidden" name="productinfo" value="{$productInfo}" />
        <input type="hidden" name="firstname" value="{$firstname}" />
        <input type="hidden" name="phone" value="{$phone}" />
        <input type="hidden" name="email" value="{$email}" />
        <input type="hidden" name="surl" value="{$surl}" />
        <input type="hidden" name="furl" value="{$furl}" />
        <input type="hidden" name="hash" value="{$hash}" />
        <input type="hidden" name="udf1" value="{$udf1}" />
        <input type="hidden" name="udf2" value="{$udf2}" />
        <input type="hidden" name="udf3" value="{$udf3}" />
        <input type="hidden" name="udf4" value="{$udf4}" />
        <input type="hidden" name="udf5" value="{$udf5}" />

        <input type="hidden" name="address1" value="{$address1}" />
        <input type="hidden" name="address2" value="{$address2}" />
        <input type="hidden" name="city" value="{$city}" />
        <input type="hidden" name="state" value="{$state}" />
        <input type="hidden" name="country" value="{$country}" />
        <input type="hidden" name="zipcode" value="{$zipcode}" />
    </form>
    <script>
        $(document).ready(function () {
            $('#easebuzzpayment-api-form').submit();
            return false;
        });
    </script>
{/if}