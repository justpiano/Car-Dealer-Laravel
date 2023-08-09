<?php
    /**
     * Listing
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: listing.tpl.php", v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="wojo top padding">
  <div class="wojo-grid">
    <div class="row big gutters">
      <div class="columns screen-60 tablet-60 mobile-100 phone-100">
        <div class="wojo simple basic fitted segment">
            <?php if ($this->row->sold): ?>
              <div class="wojo bookmark"><?php echo Lang::$word->SOLD; ?></div>
            <?php endif; ?>
            <?php if ($this->gallery): ?>
              <div id="mcarousel" class="wSlider"
                data-slick='{"dots": false,"arrows":true,"asNavFor": "#scarousel", "lazyLoad": "ondemand", "slidesToShow":1, "slidesToScroll": 1}'>
                  <?php foreach ($this->gallery as $rows): ?>
                    <a class="wojo very rounded image lightbox" data-title="<?php echo $rows->title; ?>"
                      data-gallery="photos"
                      href="<?php echo UPLOADURL . '/listings/pics' . $this->row->id . '/' . $rows->photo; ?>">
                      <img src="<?php echo UPLOADURL . '/listings/pics' . $this->row->id . '/' . $rows->photo; ?>"
                        alt=""></a>
                  <?php endforeach; ?>
                  <?php unset($rows); ?>
              </div>
            <?php else: ?>
              <img src="<?php echo UPLOADURL . '/listings/' . $this->row->thumb; ?>" alt="">
            <?php endif; ?>
            <?php if ($this->gallery): ?>
              <div id="scarousel" class="wSlider mt2 small spaced"
                data-slick='{"dots": false,"arrows":false,"asNavFor": "#mcarousel", "focusOnSelect": true,"centerMode": true, "mobileFirst":true,"responsive":[{"breakpoint":1024,"settings":{"slidesToShow": 4,"slidesToScroll": 1}},{ "breakpoint": 769, "settings":{"slidesToShow": 2,"slidesToScroll": 1}},{"breakpoint": 480,"settings":{ "slidesToShow": 1,"slidesToScroll": 1}}]}'>
                  <?php foreach ($this->gallery as $rows): ?>
                    <a class="wojo very rounded color image"><img
                        src="<?php echo UPLOADURL . '/listings/pics' . $this->row->id . '/thumbs/' . $rows->photo; ?>"
                        alt=""></a>
                  <?php endforeach; ?>
                  <?php unset($rows); ?>
              </div>
            <?php endif; ?>
        </div>
        <h5 class="mt2"><?php echo Lang::$word->DESCRIPTION; ?></h5>
          <?php echo Url::out_url($this->row->body); ?>
          <?php if ($this->row->file): ?>
            <h5 class="mt2"><?php echo Lang::$word->ATTACHMENT; ?></h5>
            <a href="<?php echo UPLOADURL . '/listings/files' . $this->row->file; ?>"><img
                src="<?php echo UPLOADURL; ?>/images/pdficon.svg" class="wojo small inline image" alt=""></a>
          <?php endif; ?>
          <?php if ($this->features): ?>
            <h5 class="mt2"><?php echo Lang::$word->LST_MFET; ?></h5>
            <div class="row grid small gutters screen-3 tablet-3 mobile-2 phone-1">
                <?php foreach ($this->features as $srow): ?>
                  <div class="columns">
                    <div class="wojo icon text"><i class="primary icon asterisk"></i><?php echo $srow->name; ?></div>
                  </div>
                <?php endforeach; ?>
            </div>
          <?php endif; ?>
        <h5 class="mt2"><?php echo Lang::$word->LOCATION; ?></h5>
        <div id="map" style="height:500px" class="wojo rounded"></div>
      </div>
      <div class="columns screen-40 tablet-40 mobile-100 phone-100">
        <div class="wojo native sticky">
          <h3><?php echo $this->row->nice_title; ?></h3>
          <div class="wojo horizontal divided list">
            <div class="item"><?php echo $this->row->year; ?></div>
            <div class="item"><?php echo $this->row->category_name; ?></div>
            <div class="item"><?php echo $this->row->fuel_name; ?></div>
          </div>
          <div class="wojo relaxed divider"></div>
            <?php if ($this->row->price_sale > 0): ?>
              <h4 class="wojo basic primary text"><?php echo Utility::formatMoney($this->row->price_sale); ?></h4>
            <?php endif; ?>
          <h4
            class="wojo<?php echo ($this->row->price_sale > 0) ? " strike" : ""; ?> text mb2"><?php echo Utility::formatMoney($this->row->price); ?></h4>
          <div class="wojo basic light bg top attached segment">
            <div class="wojo small items">
              <div class="item">
                <div class="columns"><span class="wojo demi text"><?php echo Lang::$word->LST_MAKE; ?>:</span></div>
                <div class="columns"><?php echo $this->row->make_name; ?></div>
              </div>
              <div class="item">
                <div class="columns"><span class="wojo demi text"><?php echo Lang::$word->LST_MODEL; ?>:</span></div>
                <div class="columns"><?php echo $this->row->model_name; ?></div>
              </div>
              <div class="item">
                <div class="columns"><span class="wojo demi text"><?php echo Lang::$word->LST_EXTC; ?>:</span></div>
                <div class="columns"><?php echo $this->row->color_e; ?></div>
              </div>
              <div class="item">
                <div class="columns"><span class="wojo demi text"><?php echo Lang::$word->LST_INTC; ?>:</span></div>
                <div class="columns"><?php echo $this->row->color_i; ?></div>
              </div>
              <div class="item">
                <div class="columns"><span class="wojo demi text"><?php echo Lang::$word->LST_TRANS; ?>:</span></div>
                <div class="columns"><?php echo $this->row->trans_name; ?></div>
              </div>
              <div class="item">
                <div class="columns"><span class="wojo demi text"><?php echo Lang::$word->LST_COND; ?>:</span></div>
                <div class="columns"><?php echo $this->row->condition_name; ?></div>
              </div>
              <div class="item">
                <div class="columns"><span class="wojo demi text"><?php echo Lang::$word->LST_YEAR; ?>:</span></div>
                <div class="columns"><?php echo $this->row->year; ?></div>
              </div>
              <div class="item">
                <div class="columns"><span class="wojo demi text"><?php echo Lang::$word->LST_FUEL; ?>:</span></div>
                <div class="columns"><?php echo $this->row->fuel_name; ?></div>
              </div>
              <div class="item">
                <div class="columns"><span class="wojo demi text"><?php echo Lang::$word->LST_ODM; ?>:</span></div>
                <div
                  class="columns"><?php echo number_format($this->row->mileage, 0, '.', ($this->core->odometer == "km" ? "." : ",")); ?><?php echo $this->core->odometer; ?></div>
              </div>
              <div class="item">
                <div class="columns"><span class="wojo demi text"><?php echo Lang::$word->LST_DOORS; ?>:</span></div>
                <div class="columns"><?php echo $this->row->doors; ?></div>
              </div>
                <?php if ($this->row->engine): ?>
                  <div class="item">
                    <div class="columns"><span class="wojo demi text"><?php echo Lang::$word->LST_ENGINE; ?>:</span>
                    </div>
                    <div class="columns"><?php echo $this->row->engine; ?></div>
                  </div>
                <?php endif; ?>
                <?php if ($this->row->top_speed): ?>
                  <div class="item">
                    <div class="columns"><span class="wojo demi text"><?php echo Lang::$word->LST_SPEED; ?>:</span>
                    </div>
                    <div
                      class="columns"><?php echo $this->row->top_speed; ?><?php echo ($this->core->odometer == "km") ? 'km/h' : 'mph'; ?></div>
                  </div>
                <?php endif; ?>
                <?php if ($this->row->top_speed): ?>
                  <div class="item">
                    <div class="columns"><span class="wojo demi text"><?php echo Lang::$word->LST_VIN; ?>:</span></div>
                    <div class="columns"><?php echo $this->row->vin; ?></div>
                  </div>
                <?php endif; ?>
            </div>
          </div>
          <a class="wojo fluid basic primary button pnumber" data-number="<?php echo $this->location->phone; ?>"><i
              class="icon phone"></i>
            <span><?php echo substr($this->location->phone, 0, -4); ?>****-<?php echo strtolower(Lang::$word->REVEAL); ?></span></a>
          <div class="vertical margin">
            <a class="wojo fluid positive button" href="https://wa.me/1233456789"><i
                class="icon whatsapp"></i><?php echo Lang::$word->CVW; ?></a>
          </div>
          <a class="wojo fluid primary button" data-scroll="true" href="#sendMessage">
            <i class="icon envelope fill"></i>
              <?php echo Lang::$word->CL_SENDMSG; ?></a>
          <div class="mt2 center aligned">
            <a target="_blank" data-content="Share on Facebook"
              href="https://www.facebook.com/sharer/sharer.php?u=<?php echo Url::url('/listing' . $this->row->idx, $this->row->slug); ?>"
              class="wojo small primary inverted icon button"><i class="icon facebook"></i></a>
            <a data-content="Share on Twitter"
              href="https://twitter.com/home?status=<?php echo Url::url('/listing' . $this->row->idx, $this->row->slug); ?>"
              class="wojo small primary inverted icon button"><i class="icon twitter"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--send message start-->
  <div class="wojo primary inverted bg large top padding" id="sendMessage">
    <div class="wojo-grid">
      <div class="row big gutters">
        <div class="columns screen-60 tablet-60 mobile-100 phone-100">
            <?php include THEMEBASE . "/_listing_contact.tpl.php"; ?>
        </div>
        <div class="columns screen-40 tablet-40 mobile-100 phone-100">
          <div class="wojo attached basic segment">
            <h5><?php echo $this->location->name; ?></h5>
            <div class="wojo relaxed list">
              <div class="item"><i class="icon primary geo"></i>
                  <?php echo $this->location->address; ?></div>
              <a href="<?php echo Url::url("/seller", $this->location->name_slug); ?>" class="item"><i
                  class="icon car"></i>
                  <?php echo Lang::$word->HOME_MORES; ?></a>
              <div class="item"><i class="icon primary envelope"></i>
                  <?php echo $this->location->email; ?></div>
            </div>
            <div class="wojo auto divider"></div>
            <a class="wojo fluid basic primary button pnumber" data-number="<?php echo $this->location->phone; ?>"><i
                class="icon phone"></i>
              <span><?php echo substr($this->location->phone, 0, -4); ?>****-<?php echo strtolower(Lang::$word->REVEAL); ?></span></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include THEMEBASE . "/_categories.tpl.php"; ?>
<?php include THEMEBASE . "/_brands.tpl.php"; ?>
<script async src="https://maps.google.com/maps/api/js?key=<?php echo $this->core->mapapi; ?>&callback=runMap"></script>
<script type="text/javascript">
   // <![CDATA[
   function runMap() {
      const markers = [];
      let map;

      const newMapOptions = {
         center: new google.maps.LatLng(<?php echo $this->location->lat;?>, <?php echo $this->location->lng;?>),
         zoom: <?php echo $this->location->zoom;?>,
         zoomControlOptions: {
            style: google.maps.ZoomControlStyle.SMALL
         },
         scaleControl: true,
         mapTypeId: google.maps.MapTypeId.ROADMAP,
         mapTypeControl: false,
         streetViewControl: true,
      };
      map = new google.maps.Map(document.getElementById("map"), newMapOptions);

      //set marker
      const marker = new google.maps.Marker({
         position: new google.maps.LatLng(<?php echo $this->location->lat;?>, <?php echo $this->location->lng;?>),
         map: map,
         draggable: false,
         animation: google.maps.Animation.DROP,
         raiseOnDrag: false,
         icon: "<?php echo SITEURL;?>/assets/images/marker.png",
         title: "<?php echo $this->location->name;?>"
      });

      //set infowindow
      const content =
        '<div class="container">' +
        '<h5><?php echo $this->location->name;?></h5>' +
        '<div class="content">' +
        '<?php echo $this->location->address;?><br>' +
        '<?php echo $this->location->city;?><br>' +
        '<?php echo $this->location->state;?>, <?php echo $this->location->zip;?><br>' +
        '<?php echo $this->location->phone;?>' +
        '</div>' +
        '</div>';

      const infowindow = new google.maps.InfoWindow({
         content: content,
         maxWidth: 350,
         maxHeight: 350
      });

      marker.addListener('click', function () {
         infowindow.open(map, marker);
      });

      markers.push(marker);
   }
</script>