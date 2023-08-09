<?php
    /**
     * Payment Validation
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _validate.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
  <div class="relative massive vertical padding">
    <div class="wojo-grid">
      <!-- Razorpay -->
        <?php if (Validator::post('razorpay_payment_id')): ?>
          <div class="wojo loading segment">
            <h4 class="center aligned"><?php echo Lang::$word->HOME_POK2; ?></h4>
          </div>
        <?php endif; ?>
    </div>
  </div>
<?php if (Validator::post('razorpay_payment_id')): ?>
  <script type="text/javascript">
     // <![CDATA[
     $(document).ready(function () {
        $.ajax({
           type: 'POST',
           url: "<?php echo SITEURL;?>/gateways/razorpay/ipn.php",
           dataType: 'json',
           data: {
              "razorpay_payment_id": "<?php echo Validator::post('razorpay_payment_id');?>",
              "razorpay_signature": "<?php echo Validator::post('razorpay_signature');?>",
           }
        }).done(function (json) {
           if (json.type === "success") {
              $('main').transition("scaleOut", {
                 duration: 4000,
                 complete: function () {
                    window.location.href = '<?php echo Url::url('/dashboard', "history");?>';
                 }
              });
           }
           $.wNotice(decodeURIComponent(json.message), {
              autoclose: 12000,
              type: json.type,
              title: json.title
           });
        });
     });
     // ]]>
  </script>
<?php endif; ?>