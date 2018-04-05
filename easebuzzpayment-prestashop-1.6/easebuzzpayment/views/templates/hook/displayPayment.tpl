{if $api_url ne ''}
    <div class="row">
        <div class="col-md-12">
            <p class="payment_module">
                <a href="#" id="easebuzzpayment-api-link" class="easebuzzpayment">
                    {l s='Pay with Easebuzz Payment' mod='easebuzzpayment'}
                </a>
            </p>
        </div>
    </div>
    <form action="{$api_url}" style="display:none" id="easebuzzpayment-api-form" method="POST">
        <input type="hidden" name="id_cart" value="{$id_cart}" />
    </form>
    <script>
        $('#easebuzzpayment-api-link').click(function () {
            $('#easebuzzpayment-api-form').submit();
            return false;
        });
    </script>
{/if}