<?php
    /**
     * Contact
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _contact.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
  <div class="wojo-grid">
    <div class="big vertical padding">
      <div class="row big gutters">
        <div class="columns screen-50 tablet-50 mobile-100 phone-100">
          <h3><?php echo Lang::$word->CONTACT_INFO; ?></h3>
          <div class="wojo big space divider"></div>
            <?php echo $this->row->body; ?>
            <?php if ($this->core->address): ?>
              <div id="map" style="height:300px"></div>
            <?php endif; ?>
        </div>
        <div class="columns screen-50 tablet-50 mobile-100 phone-100">
          <h3><?php echo Lang::$word->CONTACT_WRITE; ?></h3>
          <div class="wojo big space divider"></div>
          <div class="wojo form">
            <form id="wojo_form" name="wojo_form" method="post">
              <div class="wojo block fields">
                <div class="field">
                  <label><?php echo Lang::$word->NAME; ?>
                    <i class="icon asterisk"></i></label>
                  <input type="text" placeholder="<?php echo Lang::$word->NAME; ?>"
                    value="<?php echo App::Auth()->name; ?>" name="name">
                </div>
                <div class="field">
                  <label><?php echo Lang::$word->EMAIL; ?>
                    <i class="icon asterisk"></i></label>
                  <input type="text" placeholder="<?php echo Lang::$word->EMAIL; ?>"
                    value="<?php echo App::Auth()->email; ?>" name="email">
                </div>
                <div class="field">
                  <label><?php echo Lang::$word->MESSAGE; ?>
                    <i class="icon asterisk"></i></label>
                  <textarea class="small" placeholder="<?php echo Lang::$word->MESSAGE; ?>" name="notes"></textarea>
                </div>
                <div class="field">
                  <label><?php echo Lang::$word->CAPTCHA; ?>
                    <i class="icon asterisk"></i></label>
                  <div class="wojo labeled input">
                    <input name="captcha" placeholder="<?php echo Lang::$word->CAPTCHA; ?>" type="text">
                    <div class="wojo simple label"><?php echo Session::captcha(); ?></div>
                  </div>
                </div>
                <div class="field">
                  <button type="button" data-hide="true" data-action="contact" name="dosubmit"
                    class="wojo primary fluid  button"><?php echo Lang::$word->SEND; ?></button>
                </div>
              </div>
              <input type="hidden" name="slug" value="<?php echo $this->segments[1]; ?>">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php if ($this->core->address): ?>
  <script async
    src="https://maps.google.com/maps/api/js?key=<?php echo $this->core->mapapi; ?>&callback=initMap"></script>
  <script type="text/javascript">
     // <![CDATA[
     function initMap() {
        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({
           'address': "<?php echo $this->core->address;?>, <?php echo $this->core->city;?>, <?php echo $this->core->state;?> <?php echo $this->core->zip;?>"
        }, function (results, status) {
           if (status === google.maps.GeocoderStatus.OK) {
              const map = new google.maps.Map(document.getElementById('map'), {
                 zoom: 16,
                 center: {
                    lat: results[0].geometry.location.lat(),
                    lng: results[0].geometry.location.lng()
                 },
                 zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.SMALL
                 },
                 scaleControl: true,
                 streetViewControl: false,
                 styles: [
                    {"featureType": "all", "stylers": [{"saturation": 0}, {"hue": "#e7ecf0"}]},
                    {"featureType": "road", "stylers": [{"saturation": -70}]},
                    {"featureType": "transit", "stylers": [{"visibility": "off"}]},
                    {"featureType": "poi", "stylers": [{"visibility": "off"}]},
                    {"featureType": "water", "stylers": [{"visibility": "simplified"}, {"saturation": -60}]}
                 ]
              });
              map.setCenter(results[0].geometry.location);
              const marker = new google.maps.Marker({
                 map: map,
                 animation: google.maps.Animation.DROP,
                 icon: '<?php echo SITEURL;?>/assets/images/marker.png',
                 position: results[0].geometry.location
              });
              //set infowindow
              const content =
                "<div class=\"container\">" +
                "<h5><?php echo $this->core->company;?></h5>" +
                "<div class=\"content\">" +
                "<?php echo $this->core->address;?>, <?php echo $this->core->city;?><br><?php echo $this->core->state;?> <?php echo $this->core->zip;?>" +
                "</div>" +
                "</div>";

              const infowindow = new google.maps.InfoWindow({
                 content: content,
                 maxWidth: 350,
                 maxHeight: 350
              });

              marker.addListener('click', function () {
                 infowindow.open(map, marker);
              });
           } else {
              $.wNotice('Geocode was not successful for the following reason: ' + status, {
                 title: 'Error',
                 type: 'error'
              });
           }
        });
     }

     // ]]>
  </script>
<?php endif; ?>