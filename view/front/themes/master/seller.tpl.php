<?php
    /**
     * Seller
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: seller.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="wojo primary inverted bg big top padding">
  <div class="wojo-grid">
    <div class="row gutters">
        <?php if ($this->row->logo): ?>
          <div class="columns auto">
            <img src="<?php echo UPLOADURL . "/showrooms/" . $this->row->logo; ?>" alt="" class="wojo small image">
          </div>
        <?php endif; ?>
      <div class="columns">
        <h4 class="basic"><?php echo $this->row->name; ?></h4>
        <p class="wojo dark text"><?php echo Lang::$word->HOME_SUB9P; ?></p>
      </div>
    </div>
    <div id="map" style="height:300px" class="mb3"></div>
  </div>
</div>
<div class="wojo-grid">
    <?php if (!$this->data): ?>
        <?php echo Message::msgSingleInfo(Lang::$word->NOLISTFOUND); ?>
    <?php else: ?>
        <?php include THEMEBASE . "/_grid_search.tpl.php"; ?>
    <?php endif; ?>
  <div class="row gutters align middle">
    <div class="columns auto mobile-100 phone-100">
      <div class="wojo small semi text">
          <?php echo Lang::$word->TOTAL . ': ' . $this->pager->items_total; ?> / <?php echo Lang::$word->CURPAGE . ': ' . $this->pager->current_page . ' ' . Lang::$word->OF . ' ' . $this->pager->num_pages; ?>
      </div>
    </div>
    <div class="columns right aligned mobile-100 phone-100">
        <?php echo $this->pager->display_pages(); ?>
    </div>
  </div>
</div>
<?php include THEMEBASE . "/_brands.tpl.php"; ?>
<?php include THEMEBASE . "/_categories.tpl.php"; ?>
<script async src="https://maps.google.com/maps/api/js?key=<?php echo $this->core->mapapi; ?>&callback=runMap"></script>
<script type="text/javascript">
   // <![CDATA[
   function runMap() {
      const markers = [];
      let map;

      const newMapOptions = {
         center: new google.maps.LatLng(<?php echo $this->row->lat;?>, <?php echo $this->row->lng;?>),
         zoom: <?php echo $this->row->zoom;?>,
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
         position: new google.maps.LatLng(<?php echo $this->row->lat;?>, <?php echo $this->row->lng;?>),
         map: map,
         draggable: false,
         animation: google.maps.Animation.DROP,
         raiseOnDrag: false,
         icon: "<?php echo SITEURL;?>/assets/pin.png",
         title: "<?php echo $this->row->name;?>"
      });

      //set infowindow
      const content =
        '<div class="container">' +
        '<h5><?php echo $this->row->name;?></h5>' +
        '<div class="content">' +
        '<?php echo $this->row->address;?><br>' +
        '<?php echo $this->row->city;?><br>' +
        '<?php echo $this->row->state;?>, <?php echo $this->row->zip;?><br>' +
        '<?php echo $this->row->phone;?>' +
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