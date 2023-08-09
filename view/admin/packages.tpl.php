<?php
    /**
     * Packages
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: packages.tpl.php, v1.00 2022-05-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    if (!Auth::checkAcl("owner")) : print Message::msgError(Lang::$word->NOACCESS);
        return; endif;
?>
<?php switch (Url::segment($this->segments)): case "edit": ?>
  <!-- Start edit -->
  <h2><?php echo Lang::$word->MSM_SUB1; ?></h2>
  <p class="wojo small text"><?php echo Lang::$word->MSM_INFO; ?></p>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="wojo segment form">
      <div class="row gutters">
        <div class="columns screen-70 tablet-60 mobile-100 phone-100 padding">
          <div class="wojo fields align middle">
            <div class="field four wide labeled">
              <label><?php echo Lang::$word->MSM_NAME; ?>
                <i class="icon asterisk"></i></label>
            </div>
            <div class="field">
              <input type="text" placeholder="<?php echo Lang::$word->MSM_NAME; ?>"
                value="<?php echo $this->row->title; ?>" name="title">
            </div>
          </div>
          <div class="wojo fields align middle">
            <div class="field four wide labeled">
              <label><?php echo Lang::$word->MSM_PRICE; ?>
                <i class="icon asterisk"></i></label>
            </div>
            <div class="field">
              <div class="wojo labeled input">
                <div class="wojo simple label"><?php echo Utility::currencySymbol(); ?></div>
                <input type="text" placeholder="<?php echo Lang::$word->MSM_PRICE; ?>"
                  value="<?php echo $this->row->price; ?>" name="price">
              </div>
            </div>
          </div>
          <div class="wojo fields align middle">
            <div class="field four wide labeled">
              <label><?php echo Lang::$word->MSM_PERIOD; ?>
                <i class="icon asterisk"></i></label>
            </div>
            <div class="field">
              <div class="wojo action input">
                <input type="text" placeholder="<?php echo Lang::$word->MSM_PERIOD; ?>"
                  value="<?php echo $this->row->days; ?>" name="days">
                <select name="period">
                    <?php echo Utility::loopOptionsSimpleAlt(Date::getMembershipPeriod(), $this->row->period); ?>
                </select>
              </div>
            </div>
          </div>
          <div class="wojo fields">
            <div class="field four wide labeled">
              <label><?php echo Lang::$word->MSM_LISTS; ?></label>
            </div>
            <div class="field">
              <input name="listings" type="range" min="1" max="100" step="1" value="<?php echo $this->row->listings; ?>"
                hidden data-suffix=" itm" data-type="labels" data-labels="1,25,50,75,100">
            </div>
          </div>
          <div class="wojo fields">
            <div class="field four wide labeled">
              <label><?php echo Lang::$word->MSM_FEATURED; ?></label>
            </div>
            <div class="field">
              <div class="wojo checkbox radio inline">
                <input name="featured" type="radio"
                  value="1" <?php echo Validator::getChecked($this->row->featured, 1); ?> id="featured_1">
                <label for="featured_1"><?php echo Lang::$word->YES; ?></label>
              </div>
              <div class="wojo checkbox radio inline">
                <input name="featured" type="radio"
                  value="0" <?php echo Validator::getChecked($this->row->featured, 0); ?> id="featured_0">
                <label for="featured_0"><?php echo Lang::$word->NO; ?></label>
              </div>
            </div>
          </div>
          <div class="wojo fields">
            <div class="field four wide labeled">
              <label><?php echo Lang::$word->MSM_PRIVATE; ?></label>
            </div>
            <div class="field">
              <div class="wojo checkbox radio inline">
                <input name="private" type="radio"
                  value="1" <?php echo Validator::getChecked($this->row->private, 1); ?> id="private_1">
                <label for="private_1"><?php echo Lang::$word->YES; ?></label>
              </div>
              <div class="wojo checkbox radio inline">
                <input name="private" type="radio"
                  value="0" <?php echo Validator::getChecked($this->row->private, 0); ?> id="private_0">
                <label for="private_0"><?php echo Lang::$word->NO; ?></label>
              </div>
            </div>
          </div>
          <div class="wojo fields">
            <div class="field four wide labeled">
              <label><?php echo Lang::$word->PUBLISHED; ?></label>
            </div>
            <div class="field">
              <div class="wojo checkbox radio inline">
                <input name="active" type="radio" value="1" <?php echo Validator::getChecked($this->row->active, 1); ?>
                  id="active_1">
                <label for="active_1"><?php echo Lang::$word->YES; ?></label>
              </div>
              <div class="wojo checkbox radio inline">
                <input name="active" type="radio" value="0" <?php echo Validator::getChecked($this->row->active, 0); ?>
                  id="active_0">
                <label for="active_0"><?php echo Lang::$word->NO; ?></label>
              </div>
            </div>
          </div>
        </div>
        <div class="columns screen-30 tablet-40 mobile-100 phone-100">
          <div class="wojo block fields">
            <div class="field">
              <input type="file" name="thumb" data-type="image"
                data-exist="<?php echo ($this->row->thumb) ? UPLOADURL . '/memberships/' . $this->row->thumb : UPLOADURL . '/default.png'; ?>"
                accept="image/png, image/jpeg">
            </div>
            <div class="field">
              <label><?php echo Lang::$word->DESCRIPTION; ?></label>
              <textarea placeholder="<?php echo Lang::$word->DESCRIPTION; ?>"
                name="description"><?php echo $this->row->description; ?></textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="center aligned">
        <a href="<?php echo Url::url("/admin/packages"); ?>"
          class="wojo simple button"><?php echo Lang::$word->CANCEL; ?></a>
        <button type="button" data-action="processPackage" name="dosubmit"
          class="wojo secondary button"><?php echo Lang::$word->MSM_UPDATE; ?></button>
      </div>
    </div>
    <input type="hidden" name="id" value="<?php echo $this->row->id; ?>">
  </form>
    <?php break; ?>
  <!-- Start new -->
<?php case "new": ?>
  <h2><?php echo Lang::$word->MSM_SUB2; ?></h2>
  <p class="wojo small text"><?php echo Lang::$word->MSM_INFO1; ?></p>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="wojo segment form">
      <div class="row gutters">
        <div class="columns screen-70 tablet-60 mobile-100 phone-100 padding">
          <div class="wojo fields align middle">
            <div class="field four wide labeled">
              <label><?php echo Lang::$word->MSM_NAME; ?>
                <i class="icon asterisk"></i></label>
            </div>
            <div class="field">
              <input type="text" placeholder="<?php echo Lang::$word->MSM_NAME; ?>" name="title">
            </div>
          </div>
          <div class="wojo fields align middle">
            <div class="field four wide labeled">
              <label><?php echo Lang::$word->MSM_PRICE; ?>
                <i class="icon asterisk"></i></label>
            </div>
            <div class="field">
              <div class="wojo labeled input">
                <div class="wojo simple label"><?php echo Utility::currencySymbol(); ?></div>
                <input type="text" placeholder="<?php echo Lang::$word->MSM_PRICE; ?>" name="price">
              </div>
            </div>
          </div>
          <div class="wojo fields align middle">
            <div class="field four wide labeled">
              <label><?php echo Lang::$word->MSM_PERIOD; ?>
                <i class="icon asterisk"></i></label>
            </div>
            <div class="field">
              <div class="wojo action input">
                <input type="text" placeholder="<?php echo Lang::$word->MSM_PERIOD; ?>" name="days">
                <select name="period">
                    <?php echo Utility::loopOptionsSimpleAlt(Date::getMembershipPeriod()); ?>
                </select>
              </div>
            </div>
          </div>
          <div class="wojo fields">
            <div class="field four wide labeled">
              <label><?php echo Lang::$word->MSM_LISTS; ?></label>
            </div>
            <div class="field">
              <input name="listings" type="range" min="1" max="100" step="1" value="1" hidden data-suffix=" itm"
                data-type="labels" data-labels="1,25,50,75,100">
            </div>
          </div>
          <div class="wojo fields">
            <div class="field four wide labeled">
              <label><?php echo Lang::$word->MSM_FEATURED; ?></label>
            </div>
            <div class="field">
              <div class="wojo checkbox radio inline">
                <input name="featured" type="radio" value="1" checked="checked" id="featured_1">
                <label for="featured_1"><?php echo Lang::$word->YES; ?></label>
              </div>
              <div class="wojo checkbox radio inline">
                <input name="featured" type="radio" value="0" id="featured_0">
                <label for="featured_0"><?php echo Lang::$word->NO; ?></label>
              </div>
            </div>
          </div>
          <div class="wojo fields">
            <div class="field four wide labeled">
              <label><?php echo Lang::$word->MSM_PRIVATE; ?></label>
            </div>
            <div class="field">
              <div class="wojo checkbox radio inline">
                <input name="private" type="radio" value="1" checked="checked" id="private_1">
                <label for="private_1"><?php echo Lang::$word->YES; ?></label>
              </div>
              <div class="wojo checkbox radio inline">
                <input name="private" type="radio" value="0" id="private_0">
                <label for="private_0"><?php echo Lang::$word->NO; ?></label>
              </div>
            </div>
          </div>
          <div class="wojo fields">
            <div class="field four wide labeled">
              <label><?php echo Lang::$word->PUBLISHED; ?></label>
            </div>
            <div class="field">
              <div class="wojo checkbox radio inline">
                <input name="active" type="radio" value="1" checked="checked" id="active_1">
                <label for="active_1"><?php echo Lang::$word->YES; ?></label>
              </div>
              <div class="wojo checkbox radio inline">
                <input name="active" type="radio" value="0" id="active_0">
                <label for="active_0"><?php echo Lang::$word->NO; ?></label>
              </div>
            </div>
          </div>
        </div>
        <div class="columns screen-30 tablet-40 mobile-100 phone-100">
          <div class="wojo block fields">
            <div class="field">
              <input type="file" name="thumb" data-type="image" accept="image/png, image/jpeg">
            </div>
            <div class="field">
              <label><?php echo Lang::$word->DESCRIPTION; ?></label>
              <textarea placeholder="<?php echo Lang::$word->DESCRIPTION; ?>" name="description"></textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="center aligned">
        <a href="<?php echo Url::url("/admin/packages"); ?>"
          class="wojo simple button"><?php echo Lang::$word->CANCEL; ?></a>
        <button type="button" data-action="processPackage" name="dosubmit"
          class="wojo secondary button"><?php echo Lang::$word->MSM_ADD; ?></button>
      </div>
    </div>
  </form>
    <?php break; ?>
  <!-- Start history -->
<?php case "history": ?>
  <div class="row">
    <div class="columns">
      <h2><?php echo Lang::$word->MSM_SUB3; ?> <small>// <?php echo $this->data->title; ?></small></h2>
      <p class="wojo small text"><?php echo Lang::$word->MSM_INFO3; ?></p>
    </div>
    <div class="columns auto"><a
        href="<?php echo ADMINVIEW . '/helper.php?action=exportMembershipPayments&amp;id=' . $this->data->id; ?>"
        class="wojo small primary button"><?php echo Lang::$word->EXPORT; ?></a></div>
  </div>
  <div class="wojo segment">
    <div id="legend" class="wojo small horizontal list"></div>
    <div id="payment_chart" style="height:300px;"></div>
  </div>
    <?php if ($this->plist): ?>
    <div class="wojo segment">
      <table class="wojo sorting basic table">
        <thead>
        <tr>
          <th data-sort="string"><?php echo Lang::$word->NAME; ?></th>
          <th data-sort="int"><?php echo Lang::$word->TRX_AMOUNT; ?></th>
          <th data-sort="int"><?php echo Lang::$word->TRX_TAX; ?></th>
          <th data-sort="int"><?php echo Lang::$word->TRX_COUPON; ?></th>
          <th data-sort="int"><?php echo Lang::$word->TRX_TOTAMT; ?></th>
          <th data-sort="int"><?php echo Lang::$word->CREATED; ?></th>
        </tr>
        </thead>
          <?php foreach ($this->plist as $row): ?>
            <tr>
              <td><a class="inverted"
                  href="<?php echo Url::url("/admin/members/edit", $row->user_id); ?>"><?php echo $row->name; ?></a>
              </td>
              <td><?php echo $row->amount; ?></td>
              <td><?php echo $row->tax; ?></td>
              <td><?php echo $row->coupon; ?></td>
              <td><?php echo $row->total; ?></td>
              <td
                data-sort-value="<?php echo strtotime($row->created); ?>"><?php echo Date::doDate("short_date", $row->created); ?></td>
            </tr>
          <?php endforeach; ?>
      </table>
      <div
        class="wojo small primary passive button"><?php echo Lang::$word->TRX_TOTAMT; ?><?php echo Utility::formatMoney(Stats::doArraySum($this->plist, "total")); ?></div>
    </div>
    <div class="row gutters align middle">
      <div class="columns auto mobile-100 phone-100">
        <div
          class="wojo small thick text"><?php echo Lang::$word->TOTAL . ': ' . $this->pager->items_total; ?> / <?php echo Lang::$word->CURPAGE . ': ' . $this->pager->current_page . ' ' . Lang::$word->OF . ' ' . $this->pager->num_pages; ?></div>
      </div>
      <div class="columns right aligned mobile-100 phone-100"><?php echo $this->pager->display_pages(); ?></div>
    </div>
    <?php endif; ?>
  <script type="text/javascript" src="<?php echo SITEURL; ?>/assets/morris.min.js"></script>
  <script type="text/javascript" src="<?php echo SITEURL; ?>/assets/raphael.min.js"></script>
  <script type="text/javascript">
     // <![CDATA[
     $(document).ready(function () {
        $("#payment_chart").parent().addClass('loading');
        $.ajax({
           type: 'GET',
           url: "<?php echo ADMINVIEW . '/helper.php?action=getPackagePaymentsChart&id=' . $this->data->id;?>&timerange=all",
           dataType: 'json'
        }).done(function (json) {
           let legend = '';
           json.legend.map(function (val) {
              legend += val;
           });
           $("#legend").html(legend);
           Morris.Line({
              element: 'payment_chart',
              data: json.data,
              xkey: 'm',
              ykeys: json.label,
              labels: json.label,
              parseTime: false,
              lineWidth: 4,
              pointSize: 6,
              lineColors: json.color,
              gridTextFamily: "Roboto",
              gridTextColor: "rgba(0,0,0,0.6)",
              gridTextSize: 14,
              fillOpacity: '.75',
              hideHover: 'auto',
              preUnits: json.preUnits,
              smooth: true,
              resize: true,
           });
           $("#payment_chart").parent().removeClass('loading');
        });
     });
     // ]]>
  </script>
    <?php break; ?>
<?php default: ?>
  <div class="row gutters align middle">
    <div class="columns phone-100">
      <h2><?php echo Lang::$word->MSM_TITLE; ?></h2>
      <p class="wojo small text"><?php echo Lang::$word->MSM_INFO2; ?></p>
    </div>
    <div class="columns auto phone-100">
      <a href="<?php echo Url::url("/admin/packages", "new"); ?>" class="wojo small secondary stacked button"><i
          class="icon plus alt"></i><?php echo Lang::$word->MSM_ADD; ?></a>
    </div>
  </div>
    <?php if (!$this->data): ?>
    <div class="center aligned"><img src="<?php echo ADMINVIEW; ?>/images/nop.svg" alt="">
      <p class="wojo small demi caps text"><?php echo Lang::$word->MSM_NOMBS; ?></p>
    </div>
    <?php else: ?>
    <div class="wojo cards screen-3 tablet-2 mobile-1">
        <?php foreach ($this->data as $row): ?>
          <div class="card" id="item_<?php echo $row->id; ?>">
            <div class="content center aligned">
                <?php if ($row->thumb): ?>
                  <img src="<?php echo UPLOADURL; ?>/memberships/<?php echo $row->thumb; ?>" alt="">
                <?php else: ?>
                  <img src="<?php echo UPLOADURL; ?>/memberships/default.png" alt="">
                <?php endif; ?>
              <h4><?php echo Utility::formatMoney($row->price); ?>
                  <?php echo $row->title; ?></h4>
              <p class="wojo tiny text"><?php echo Validator::truncate($row->description, 40); ?></p>
              <a href="<?php echo Url::url(Router::$path, "history/" . $row->id); ?>"
                class="wojo small primary inverted icon label"><?php echo $row->total; ?>
                  <?php echo Lang::$word->TRX_SALES; ?></a>
            </div>
            <div class="footer divided">
              <div class="row">
                <div class="columns">
                  <a href="<?php echo Url::url(Router::$path, "edit/" . $row->id); ?>"
                    class="wojo icon inverted positive small button"><i class="icon pencil"></i></a>
                </div>
                <div class="columns auto">
                  <a
                    data-set='{"option":[{"trash": "trashPackage","title": "<?php echo Validator::sanitize($row->title, "chars"); ?>","id": <?php echo $row->id; ?>}],"action":"trash","parent":"#item_<?php echo $row->id; ?>"}'
                    class="wojo icon inverted negative small button data"><i class="icon trash"></i></a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php break; ?>
<?php endswitch; ?>