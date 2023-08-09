<?php
    /**
     * Locations
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: locations.tpl.php, v1.00 2022-05-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    if (!Auth::checkAcl("owner", "staff")): print Message::msgError(Lang::$word->NOACCESS);
        return; endif;
?>
<?php switch (Url::segment($this->segments)): case "edit": ?>
  <!-- Start edit -->
  <h2><?php echo Lang::$word->LOC_SUB1; ?></h2>
  <p class="wojo small text"><?php echo Lang::$word->LOC_INFO1; ?></p>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="wojo segment form">
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->LOC_NAME; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->LOC_NAME; ?>" value="<?php echo $this->row->name; ?>"
            name="name">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->EMAIL; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->EMAIL; ?>" value="<?php echo $this->row->email; ?>"
            name="email">
        </div>
      </div>
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->ADDRESS; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->ADDRESS; ?>"
            value="<?php echo $this->row->address; ?>" name="address">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->CITY; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->CITY; ?>" value="<?php echo $this->row->city; ?>"
            name="city">
        </div>
      </div>
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->STATE; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->STATE; ?>" value="<?php echo $this->row->state; ?>"
            name="state">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->ZIP; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->ZIP; ?>" value="<?php echo $this->row->zip; ?>"
            name="zip">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->COUNTRY; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->COUNTRY; ?>"
            value="<?php echo $this->row->country; ?>" name="country">
        </div>
      </div>
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->CF_PHONE; ?></label>
          <input type="text" placeholder="<?php echo Lang::$word->CF_PHONE; ?>" value="<?php echo $this->row->phone; ?>"
            name="phone">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->CF_FAX; ?></label>
          <input type="text" placeholder="<?php echo Lang::$word->CF_FAX; ?>" value="<?php echo $this->row->fax; ?>"
            name="fax">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->CF_WEBURL; ?></label>
          <input type="text" placeholder="<?php echo Lang::$word->CF_WEBURL; ?>" value="<?php echo $this->row->url; ?>"
            name="url">
        </div>
      </div>
      <div class="wojo fields">
        <div class="basic field">
          <label><?php echo Lang::$word->CF_LOGO; ?></label>
          <input type="file" name="logo" id="logo" class="filestyle" data-input="false">
        </div>
        <div class="basic field">
          <label><?php echo Lang::$word->CF_LOGO; ?></label>
          <div class="wojo circular primary small image"><img
              src="<?php echo UPLOADURL; ?>/showrooms/<?php echo ($this->row->logo) ?: "blank.png"; ?>" alt=""></div>
        </div>
      </div>
    </div>
    <div class="wojo segment form">
      <div class="wojo fields">
        <div class="field">
          <div style="position:absolute;z-index:500;right:2rem;top:2rem">
            <div class="wojo action input" style="width:400px;">
              <input placeholder="<?php echo Lang::$word->LOC_SEARCH; ?>" type="text" name="adrs" id="address">
              <a id="lookup" class="wojo icon button"><i class="find icon"></i></a>
            </div>
          </div>
          <div id="map" style="width:100%;height:400px;z-index:300"></div>
        </div>
      </div>
      <div class="center aligned">
        <a href="<?php echo Url::url("/admin/locations"); ?>"
          class="wojo small simple button"><?php echo Lang::$word->CANCEL; ?></a>
        <button type="button" data-action="processLocation" name="dosubmit"
          class="wojo secondary button"><?php echo Lang::$word->LOC_UPDATE; ?></button>
      </div>
    </div>
    <input type="hidden" name="id" value="<?php echo $this->row->id; ?>">
    <input name="lat" id="lat" type="hidden" value="<?php echo $this->row->lat; ?>">
    <input name="lng" id="lng" type="hidden" value="<?php echo $this->row->lng; ?>">
    <input name="zoom" id="zoomlevel" type="hidden" value="<?php echo $this->row->zoom; ?>">
  </form>
  <script
    src="https://maps.googleapis.com/maps/api/js?key=<?php echo $this->core->mapapi; ?>&callback=Function.prototype"></script>
  <script type="text/javascript" src="<?php echo ADMINVIEW; ?>/js/locations.js"></script>
  <script type="text/javascript">
     // <![CDATA[
     $(document).ready(function () {
        $.Locations({
           zoom: <?php echo $this->row->zoom;?>,
           latitude: "<?php echo $this->row->lat;?>",
           longitude: "<?php echo $this->row->lng;?>",
           pin: "<?php echo SITEURL;?>/assets/pin.png"
        });
     });
     // ]]>
  </script>
    <?php break; ?>
  <!-- Start new -->
<?php case "new": ?>
  <h2><?php echo Lang::$word->LOC_SUB2; ?></h2>
  <p class="wojo small text"><?php echo Lang::$word->LOC_INFO2; ?></p>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="wojo segment form">
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->LOC_NAME; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->LOC_NAME; ?>" name="name">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->EMAIL; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->EMAIL; ?>" name="email">
        </div>
      </div>
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->ADDRESS; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->ADDRESS; ?>" name="address">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->CITY; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->CITY; ?>" name="city">
        </div>
      </div>
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->STATE; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->STATE; ?>" name="state">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->ZIP; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->ZIP; ?>" name="zip">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->COUNTRY; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->COUNTRY; ?>" name="country">
        </div>
      </div>
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->CF_PHONE; ?></label>
          <input type="text" placeholder="<?php echo Lang::$word->CF_PHONE; ?>" name="phone">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->CF_FAX; ?></label>
          <input type="text" placeholder="<?php echo Lang::$word->CF_FAX; ?>" name="fax">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->CF_WEBURL; ?></label>
          <input type="text" placeholder="<?php echo Lang::$word->CF_WEBURL; ?>" name="url">
        </div>
      </div>
      <div class="wojo fields">
        <div class="basic field">
          <label><?php echo Lang::$word->CF_LOGO; ?></label>
          <input type="file" name="logo" id="logo" class="filestyle" data-input="false">
        </div>
        <div class="basic field">
        </div>
      </div>
    </div>
    <div class="wojo segment form">
      <div class="wojo fields">
        <div class="field">
          <div style="position:absolute;z-index:500;right:2rem;top:2rem">
            <div class="wojo action input" style="width:400px;">
              <input placeholder="<?php echo Lang::$word->LOC_SEARCH; ?>" type="text" name="adrs" id="address">
              <a id="lookup" class="wojo icon button"><i class="find icon"></i></a>
            </div>
          </div>
          <div id="map" style="width:100%;height:400px;z-index:300"></div>
        </div>
      </div>
      <div class="center aligned">
        <a href="<?php echo Url::url("/admin/locations"); ?>"
          class="wojo small simple button"><?php echo Lang::$word->CANCEL; ?></a>
        <button type="button" data-action="processLocation" name="dosubmit"
          class="wojo secondary button"><?php echo Lang::$word->LOC_ADD; ?></button>
      </div>
    </div>
    <input name="lat" id="lat" type="hidden" value="0">
    <input name="lng" id="lng" type="hidden" value="0">
    <input name="zoom" id="zoomlevel" type="hidden" value="14">
  </form>
  <script
    src="https://maps.googleapis.com/maps/api/js?key=<?php echo $this->core->mapapi; ?>&callback=Function.prototype"></script>
  <script type="text/javascript" src="<?php echo ADMINVIEW; ?>/js/locations.js"></script>
  <script type="text/javascript">
     // <![CDATA[
     $(document).ready(function () {
        $.Locations({
           zoom: 10,
           latitude: "43.652527",
           longitude: "-79.381961",
           pin: "<?php echo SITEURL;?>/assets/pin.png"
        });
     });
     // ]]>
  </script>
    <?php break; ?>
<?php default: ?>
  <div class="row gutters align middle">
    <div class="columns phone-100">
      <h2><?php echo Lang::$word->LOC_TITLE; ?></h2>
      <p class="wojo small text"><?php echo Lang::$word->LOC_INFO; ?></p>
    </div>
    <div class="columns auto phone-100">
      <a href="<?php echo Url::url("/admin/locations", "new"); ?>" class="wojo small secondary stacked button"><i
          class="icon plus alt"></i><?php echo Lang::$word->LOC_ADD; ?></a>
    </div>
  </div>
    <?php if (!$this->data): ?>
    <div class="center aligned"><img src="<?php echo ADMINVIEW; ?>/images/nop.svg" alt="">
      <p class="wojo small demi caps text"><?php echo Lang::$word->LOC_NOLOC; ?></p>
    </div>
    <?php else: ?>
    <div class="wojo cards screen-3 tablet-3 mobile-1">
        <?php foreach ($this->data as $row): ?>
          <div class="card" id="item_<?php echo $row->id; ?>">
            <div class="header divided">
              <div class="row horizontal gutters">
                <div class="columns auto"><img
                    src="<?php echo UPLOADURL; ?>/showrooms/<?php echo ($row->logo) ?: "blank.png"; ?>" alt=""
                    class="wojo small image"></div>
                <div class="columns">
                  <a href="<?php echo Url::url("/admin/locations/edit", $row->id); ?>"><?php echo $row->name; ?>
                  </a>
                  <p><?php echo $row->email; ?></p>
                </div>
              </div>
            </div>
            <div class="content">
              <div class="wojo divided very relaxed fluid list">
                <div class="item">
                  <div class="content auto padding right">
                    <i class="icon building"></i>
                  </div>
                  <div class="content">
                      <?php echo $row->address; ?><br>
                      <?php echo $row->city; ?>, <?php echo $row->state; ?>, <?php echo $row->zip; ?><br>
                      <?php echo $row->country; ?>
                  </div>
                </div>
                <div class="item">
                  <div class="content auto padding right">
                    <i class="icon globe"></i>
                  </div>
                  <div class="content">
                      <?php echo $row->url; ?>
                  </div>
                </div>
                <div class="item">
                  <div class="content auto padding right">
                    <i class="icon phone"></i>
                  </div>
                  <div class="content">
                      <?php echo $row->phone; ?>
                  </div>
                </div>
                <div class="item">
                  <div class="content auto padding right">
                    <i class="icon printer"></i>
                  </div>
                  <div class="content">
                      <?php echo $row->fax; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="footer divided center aligned">
              <a href="<?php echo Url::url("/admin/locations/edit", $row->id); ?>"
                class="wojo icon circular inverted primary button"><i class="icon pencil"></i></a>
              <a
                data-set='{"option":[{"trash":"trashLocation","title": "<?php echo Validator::sanitize($row->name, "chars"); ?>","id": "<?php echo $row->id; ?>"}],"action":"trash", "parent":"#item_<?php echo $row->id; ?>"}'
                class="wojo icon circular inverted negative button data">
                <i class="icon trash"></i>
              </a>
            </div>
          </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php break; ?>
<?php endswitch; ?>