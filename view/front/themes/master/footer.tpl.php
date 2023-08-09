<?php
    /**
     * Footer
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: footer.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
</main>
<!-- Footer -->
<footer>
  <div class="wrapper">
    <div class="wojo-grid">
      <div class="contents">
        <div class="row big gutters">
          <div class="columns mobile-100 phone-100">
            <p
              class="wojo small white dimmed text">When you visit or interact with our sites, services or tools, we or our authorised service providers may use cookies for storing information to help provide you with a better, faster and safer experience and for marketing purposes.</p>
            <a class="logo"
              href="<?php echo SITEURL; ?>"><?php echo ($this->core->logo) ? '<img src="' . SITEURL . '/uploads/' . $this->core->logo . '" alt="' . $this->core->company . '" class="wojo image">' : null; ?></a>
            <a href="//<?php echo $this->core->social->facebook; ?>" class="wojo small transparent icon button"><i
                class="icon facebook"></i></a>
            <a href="//<?php echo $this->core->social->twitter; ?>" class="wojo small transparent icon button"><i
                class="icon twitter"></i></a>
          </div>
          <div class="columns mobile-100 phone-100">
            <div class="wojo list">
                <?php if ($this->menus): ?>
                  <a href="<?php echo Url::url("/listings"); ?>"
                    class="transparent item<?php echo(in_array("listings", $this->segments) ? " active" : " normal") ?>"><i
                      class="icon chevron right"></i><?php echo Lang::$word->BROWSE; ?></a>
                    <?php foreach ($this->menus as $row): ?>
                    <a href="<?php echo Url::url("/page", $row->slug . '/'); ?>"
                      class="transparent item<?php echo(in_array($row->slug, $this->segments) ? " active" : " normal") ?>"><i
                        class="icon chevron right"></i><?php echo $row->name; ?></a>
                    <?php endforeach; ?>
                <?php endif; ?>
              <a href="<?php echo Url::url("/packages"); ?>"
                class="transparent item<?php echo(in_array("packages", $this->segments) ? " active" : " normal") ?>"><i
                  class="icon chevron right"></i><?php echo Lang::$word->ADM_PACKAGES; ?></a>
              <a href="<?php echo Url::url("/listings"); ?>"
                class="transparent item<?php echo(in_array("search", $this->segments) ? " active" : " normal") ?>"><i
                  class="icon chevron right"></i><?php echo Lang::$word->SEARCH; ?></a>
            </div>
          </div>
          <div class="columns mobile-100 phone-100">
            <!--/* Newsletter Section Start */-->
              <?php include(THEMEBASE . "/newsletter.tpl.php"); ?>
            <!--/* Newsletter Section Ends */-->
          </div>
          <div class="columns mobile-100 phone-100">
            <p class="phone">
              <a class="inverted" href="tel:<?php echo $this->core->phone; ?>"><?php echo $this->core->phone; ?></a>
            </p>
            <p class="email">
              <a class="inverted"
                href="mailto:<?php echo $this->core->site_email; ?>"><?php echo $this->core->site_email; ?></a>
            </p>
            <div class="address">
              <p class="wojo white text"><?php echo $this->core->address; ?>
                  <?php echo $this->core->city; ?></p>
              <p class="wojo white text"><?php echo $this->core->state; ?>
                  <?php echo $this->core->zip; ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="copyright">
      <div class="wojo-grid">
        <div class="row small gutters align middle">
          <div
            class="columns phone-100">Copyright &copy;<?php echo date('Y') . ' ' . $this->core->company; ?> | Powered by
            <small>[wojo::works] v.<?php echo $this->core->wojov; ?></small></div>
          <div class="columns auto phone-100">
            <a href="<?php echo SITEURL; ?>" class="wojo small circular transparent icon button"><i
                class="icon house"></i></a>
            <a href="//validator.w3.org/check/referer" target="_blank"
              class="wojo small circular transparent icon button"><i class="icon html5"></i></a>
            <a href="<?php echo URl::url('/sitemap'); ?>" class="wojo small circular transparent icon button"><i
                class="icon diagram"></i></a>
            <a href="<?php echo SITEURL; ?>/rss.php" class="wojo small circular transparent icon button"><i
                class="icon rss"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>
<?php if (Utility::in_array_any(["listings", "search", "listing"], $this->segments)): ?>
    <?php include_once THEMEBASE . '/snippets/compare.tpl.php'; ?>
<?php endif; ?>
<?php Debug::displayInfo(); ?>
<?php if (Utility::in_array_any(["new"], $this->segments)): ?>
  <script src="<?php echo SITEURL; ?>/assets/editor/editor.js"></script>
  <script src="<?php echo SITEURL; ?>/assets/editor/alignment.js"></script>
  <script src="<?php echo SITEURL; ?>/assets/editor/fullscreen.js"></script>
<?php endif; ?>
<script src="<?php echo THEMEURL; ?>/js/carousel.js"></script>
<script src="<?php echo THEMEURL; ?>/js/lightbox.js"></script>
<script src="<?php echo THEMEURL; ?>/js/master.js"></script>
<?php if ($this->core->analytics): ?>
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $this->core->analytics; ?>"></script>
<?php endif; ?>
<script>
   // <![CDATA[
   $(document).ready(function () {
      $.Master({
         weekstart: <?php echo($this->core->weekstart);?>,
         ampm: "<?php echo !(($this->core->time_format) == "hh:mm");?>",
         url: "<?php echo FRONTVIEW;?>",
         surl: "<?php echo SITEURL;?>",
         theme: "<?php echo THEMEURL;?>",
         lang: {
            button_text: "<?php echo Lang::$word->BROWSE;?>",
            empty_text: "<?php echo Lang::$word->NOFILE;?>",
            delBtn: "<?php echo Lang::$word->DELETE_REC;?>",
            trsBtn: "<?php echo Lang::$word->MTOTRASH;?>",
            restBtn: "<?php echo Lang::$word->RFCOMPLETE;?>",
            canBtn: "<?php echo Lang::$word->CANCEL;?>",
            delMsg1: "<?php echo Lang::$word->DELCONFIRM1;?>",
            delMsg2: "<?php echo Lang::$word->DELCONFIRM2;?>",
            err: "<?php echo Lang::$word->ERROR;?>",
            err1: "<?php echo Lang::$word->FU_ERROR7;?>",
         }
      });
       <?php if($this->core->eucookie):?>
      $('body').wCookies({
         title: '&#x1F36A; <?php echo Lang::$word->EU_W_COOKIES;?>?',
         message: '<?php echo Lang::$word->EU_NOTICE;?>',
         delay: 600,
         expires: 360,
         cookieName: "WCDP_GDPR",
         link: '<?php echo Url::url("/page", "privacy-policy");?>',
         cookieTypes: [
            {
               type: '<?php echo Lang::$word->EU_PREFS;?>',
               value: 'preferences',
               description: '<?php echo Lang::$word->EU_PREFS_I;?>'
            },
            {
               type: '<?php echo Lang::$word->EU_ANALYTICS;?>',
               value: 'analytics',
               description: '<?php echo Lang::$word->EU_ANALYTICS_I;?>'
            },
            {
               type: '<?php echo Lang::$word->EU_MARKETING;?>',
               value: 'marketing',
               description: '<?php echo Lang::$word->EU_MARKETING_I;?>'
            }
         ],
         uncheckBoxes: true,
         acceptBtnLabel: '<?php echo Lang::$word->EU_ACCEPT;?>',
         advancedBtnLabel: '<?php echo Lang::$word->EU_CUSTOMISE;?>',
         moreInfoLabel: '<?php echo Lang::$word->PRIVACY;?>',
         cookieTypesTitle: '<?php echo Lang::$word->EU_SELCOOKIES;?>',
         fixedCookieTypeLabel: '<?php echo Lang::$word->EU_ESSENTIALS;?>',
         fixedCookieTypeDesc: '<?php echo Lang::$word->EU_ESSENTIALS_I;?>'
      });
       <?php endif;?>
   });
   
   <?php if($this->core->analytics):?>
   window.dataLayer = window.dataLayer || [];

   function gtag() {
      dataLayer.push(arguments);
   }

   gtag('js', new Date());
   gtag('config', '<?php echo $this->core->analytics;?>', {
      client_storage: '<?php echo ($this->core->eucookie and Session::cookieinArray("analytics", "WCDP_cookieControlPrefs", true)) ? "granted" : "none";?>',
      ad_storage: '<?php echo ($this->core->eucookie and Session::cookieinArray("analytics", "WCDP_cookieControlPrefs", true)) ? "granted" : "denied";?>',
      analytics_storage: '<?php echo ($this->core->eucookie and Session::cookieinArray("analytics", "WCDP_cookieControlPrefs", true)) ? "granted" : "denied";?>',
   });
   <?php endif;?>
   // ]]>
</script>
<?php if (count($this->segments) == 1 and $this->segments[0] == "dashboard" and App::Auth()->is_User()): ?>
  <script src="https://js.stripe.com/v3/"></script>
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<?php endif; ?>
</body></html>