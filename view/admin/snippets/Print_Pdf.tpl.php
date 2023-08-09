<?php
    /**
     * Print
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: print.tpl.php, v1.00 2022-06-02 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title><?php echo Lang::$word->LPR_SUB; ?></title>
  <style type="text/css">
     body {
        background-color: #fff;
        color: #333;
        font-family: DejaVu Serif, Helvetica, Times-Roman;
        font-size: 1em;
        margin: 0;
        padding: 0
     }
     .wojo.table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 2px;
     }
     .wojo.table th, .wojo.table td {
        text-align: left;
        border-radius: .25em;
        border-style: solid;
        border-width: 1px;
        padding: .5em;
        vertical-align: top;
     }
     .wojo.table th {
        background: #EEE;
        border-color: #BBB
     }
     .wojo.table td {
        border-color: #DDD
     }
     h1 {
        font: bold 100% sans-serif;
        letter-spacing: .5em;
        text-align: center;
        text-transform: uppercase
     }
     .wojo.table.inventory td {
        font-size: 80%;
     }
     img {
        margin: 0;
        padding: 0;
     }
     small {
        font-size: 75%;
        line-height: 1.5em
     }
     table.payments td.right, table.balance td {
        text-align: right
     }
     .wojo.image {
        max-width: 100%;
     }
     .wojo.grid {
        width: 100%;
        padding: 5px;
        border-spacing: 0
     }
     .wojo.grid td {
        border: 0;
        border-bottom: #DDD 1px dashed;
        padding: 5px;
        font-size: 80%
     }
     #footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100px;
        text-align: center;
        border-top: 2px solid #eee;
        font-size: 85%;
        padding-top: 5px
     }
     @page {
        margin: 30px;
        margin-footer: 5mm;
        footer: html_footer;
     }
  </style>
</head>
<body>
<table class="wojo table">
  <thead>
  <tr>
    <th colspan="2"><?php echo $this->data->year . ' ' . $this->data->name; ?></th>
  </tr>
  </thead>
  <tr>
    <td style="width:50%">
      <div class="center aligned margin bottom"><img src="<?php echo UPLOADURL . '/listings/' . $this->row->thumb; ?>"
          alt=""></div>
        <?php if ($this->gallery): ?>
          <div style="margin-top:10px;margin-bottom">
              <?php foreach ($this->gallery as $grow): ?>
                <img style="max-width:33%"
                  src="<?php echo UPLOADURL . '/listings/pics' . $this->row->id . '/thumbs/' . $grow->photo; ?>" alt="">
              <?php endforeach; ?>
          </div>
        <?php endif; ?></td>
    <td>
      <table class="wojo table inventory">
        <tr>
          <td><?php echo Lang::$word->LST_STOCK; ?></td>
          <td><?php echo Validator::has($this->data->stock_id); ?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->LST_VIN; ?></td>
          <td><?php echo Validator::has($this->data->vin); ?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->LST_COND; ?></td>
          <td><?php echo $this->data->condition_name; ?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->LST_ODM; ?></td>
          <td><?php echo Validator::has($this->data->mileage); ?>
              <?php echo $this->core->odometer; ?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->LST_PRICE; ?></td>
          <td><?php echo ($this->data->price_sale) ? '<span class="wojo strike text">' . Utility::formatMoney($this->data->price) . '</span>' : Utility::formatMoney($this->data->price); ?></td>
        </tr>
          <?php if ($this->data->price_sale): ?>
            <tr>
              <td><?php echo Lang::$word->LST_DPRICE_S; ?></td>
              <td><?php echo Validator::has(Utility::formatMoney($this->data->price_sale)); ?></td>
            </tr>
          <?php endif; ?>
        <tr>
          <td><?php echo Lang::$word->LST_ROOM; ?></td>
          <td><?php echo $this->location->name; ?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->EMAIL; ?></td>
          <td><?php echo $this->location->email; ?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->CF_PHONE; ?></td>
          <td><?php echo Validator::has($this->location->phone); ?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->LST_INTC; ?></td>
          <td><?php echo Validator::has($this->data->color_i); ?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->LST_EXTC; ?></td>
          <td><?php echo Validator::has($this->data->color_e); ?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->LST_DOORS; ?></td>
          <td><?php echo Validator::has($this->data->doors); ?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->LST_ENGINE; ?></td>
          <td><?php echo Validator::has($this->data->engine); ?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->LST_TRANS; ?></td>
          <td><?php echo $this->data->trans_name; ?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->LST_FUEL; ?>:</td>
          <td><?php echo $this->data->fuel_name; ?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->LST_TRAIN; ?>:</td>
          <td><?php echo Validator::has($this->data->drive_train); ?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->LST_SPEED; ?>:</td>
          <td><?php echo Validator::has($this->data->top_speed); ?>
              <?php echo ($this->core->odometer == "km") ? 'km/h' : 'mph'; ?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->LST_POWER; ?></td>
          <td><?php echo Validator::has($this->data->horse_power); ?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->LST_TORQUE; ?></td>
          <td><?php echo Validator::has($this->data->torque); ?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->LST_TOWING; ?></td>
          <td><?php echo Validator::has($this->data->towing_capacity); ?></td>
        <tr>
          <td colspan="2"><?php echo str_replace("[SITEURL]", SITEURL, $this->data->body); ?></td>
        </tr>
        <tr>
          <td colspan="2"><?php echo $this->features; ?></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<htmlpagefooter name="footer">
  <table width="100%"
    style="vertical-align: bottom;font-size: 8pt; border-top:1px solid #BBB; font-weight: bold; font-style: italic;">
    <tr>
      <td width="33%" style="border:0"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
      <td width="33%" align="center" style="font-weight: bold; font-style: italic;border:0">{PAGENO}/{nbpg}</td>
      <td width="33%" style="text-align: right;border:0 "><?php echo $this->core->company; ?></td>
    </tr>
  </table>
</htmlpagefooter>
</body>
</html>