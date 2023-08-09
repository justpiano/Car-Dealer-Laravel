<?php
    /**
     * Stripe Form
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: form.tpl.php, v1.00 2022-03-20 10:12:05 gewa Exp $
     */
    
    use Stripe\PaymentIntent;
    use Stripe\Stripe;
    
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    include BASEPATH . "/gateways/stripe/vendor/autoload.php";
    Stripe::setApiKey($this->gateway->extra);
    $intent = PaymentIntent::create([
        'amount' => round($this->cart->totalprice * 100),
        'currency' => $this->gateway->extra2,
        'payment_method_types' => ['card'],
        'description' => "Membership Purchase from " . App::Core()->company,
        'setup_future_usage' => 'off_session',
    ]);
?>
<div class="wojo small form" id="stripe_form">
  <div class="form-row" style="padding:1rem .5rem 0 .5rem;box-shadow: 0 0 0 1px var(--grey-color-300);border-radius:.250rem;">
    <div class="row small gutters align middle">
      <div class="columns phone-100">
        <div id="card-element"></div>
      </div>
      <div class="columns auto phone-100">
        <button class="wojo primary inverted fluid button" id="dostripe"
          data-secret="<?php echo $intent->client_secret; ?>" name="dostripe"
          type="submit"><?php echo Lang::$word->SUBMIT; ?></button>
      </div>
    </div>
  </div>
</div>
<div role="alert" id="smsgholder" class="wojo negative text"></div>
<script type="text/javascript">
   // <![CDATA[
   const stripe = Stripe('<?php echo $this->gateway->extra3;?>');
   const elements = stripe.elements();

   const style = {
      base: {
         color: '#32325d',
         fontFamily: 'Poppins, "Helvetica Neue", Helvetica, sans-serif',
         fontSmoothing: 'antialiased',
         fontSize: '16px',
         '::placeholder': {
            color: '#aab7c4'
         }
      },
      invalid: {
         color: '#fa755a',
         iconColor: '#fa755a'
      }
   };

   const card = elements.create('card', {
      style: style
   });

   card.mount('#card-element');

   card.addEventListener('change', function (event) {
      const displayError = document.getElementById('smsgholder');
      if (event.error) {
         displayError.textContent = event.error.message;
      } else {
         displayError.textContent = '';
      }
   });

   const submitButton = document.getElementById('dostripe');
   const clientSecret = submitButton.dataset.secret;

   submitButton.addEventListener('click', function () {
      $("#stripe_form").addClass('loading');
      stripe.confirmCardPayment(clientSecret, {
         payment_method: {
            card: card
         },
         setup_future_usage: 'off_session'
      }).then(function (result) {
         if (result.error) {
            $("#smsgholder").html(result.error.message);
         } else {
            if (result.paymentIntent.status === 'succeeded') {
               $.ajax({
                  type: "post",
                  dataType: 'json',
                  url: "<?php echo SITEURL;?>/gateways/stripe/ipn.php",
                  data: {
                     processStripePayment: 1,
                     payment_method: result.paymentIntent.payment_method
                  },
                  success: function (json) {
                     $("#stripe_form").removeClass('loading');
                     if (json.type === "success") {
                        $('main').transition("scaleOut", {
                           duration: 4000,
                           complete: function () {
                              window.location.href = '<?php echo Url::url("/dashboard", "history");?>';
                           }
                        });
                     }
                     if (json.message) {
                        $.wNotice(decodeURIComponent(json.message), {
                           autoclose: 12000,
                           type: json.type,
                           title: json.title
                        });
                     }
                  }
               });
            }
         }
      });
   });
   // ]]>
</script>