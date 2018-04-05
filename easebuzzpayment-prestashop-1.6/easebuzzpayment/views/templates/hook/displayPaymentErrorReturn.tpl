<div class="box">
    <p>
        <strong class="dark">{l s='Your order on %s is cancelled.' sprintf=$shop_name mod='easebuzzpayment'}</strong>
    </p>
    <br>
    <p>
        Payment for your order was failed.    </p>
    <br>
    <p>
        <strong> {l s='Order Details' mod='easebuzzpayment'}</strong><br>
        - {l s='Order Amount' mod='easebuzzpayment'} <span class="price"> <strong>{$total_to_pay}</strong></span><br>
        {if !isset($reference)}
            - {l s='Order ID' mod='easebuzzpayment'}  <strong>{$id_order}</strong>

        {else}
            - {l s='Order  Reference' mod='easebuzzpayment'}  <strong>{$reference}</strong><br>

        {/if}
    </p>


    <br>
    <p>{l s='If you have questions, comments or concerns, please contact our' mod='easebuzzpayment'} <a href="{$link->getPageLink('contact', true)|escape:'html':'UTF-8'}">{l s='expert customer support team.' mod='easebuzzpayment'}</a>.</p>
</div>