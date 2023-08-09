<?php
    /**
     * Compare
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: compare.tpl.php", v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="wojo big vertical padding">
  <div class="wojo-grid">
      <?php if (!$this->data): ?>
          <?php echo Message::msgSingleInfo(Lang::$word->HOME_SUB23); ?>
      <?php else: ?>
        <table class="wojo definition table responsive">
          <thead>
          <tr>
            <th><h4><?php echo Lang::$word->COMPARE . " (" . count($this->data) . ")"; ?></h4>
              <a href="<?php echo Url::url("/listings"); ?>"
                class="wojo primary button"><?php echo Lang::$word->HOME_SUB22; ?></a>
            </th>
              <?php foreach ($this->data as $row): ?>
                <th>
                  <figure class="wojo rounded zoom image">
                    <a href="<?php echo Url::url("/listing/" . $row->idx, $row->slug); ?>">
                      <img src="<?php echo UPLOADURL . '/listings/thumbs/' . $row->thumb; ?>"
                        alt="<?php echo $row->nice_title; ?>">
                    </a>
                  </figure>
                </th>
              <?php endforeach; ?>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td><?php echo Lang::$word->LST_PRICE; ?></td>
              <?php foreach ($this->data as $row): ?>
                <td class="center aligned"><span
                    class="wojo<?php echo ($row->price_sale > 0) ? " strike demi" : " large primary"; ?> text"><?php echo Utility::formatMoney($row->price); ?></span>
                </td>
              <?php endforeach; ?>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_DPRICE_S; ?></td>
              <?php foreach ($this->data as $row): ?>
                <td class="center aligned"><span
                    class="wojo<?php echo ($row->price_sale > 0) ? " large primary text" : null; ?>"><?php echo $row->price_sale > 0 ? Utility::formatMoney($row->price_sale) : "--"; ?></span>
                </td>
              <?php endforeach; ?>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_COND; ?></td>
              <?php foreach ($this->data as $row): ?>
                <td class="center aligned"><?php echo $row->condition_name; ?></td>
              <?php endforeach; ?>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_CAT; ?></td>
              <?php foreach ($this->data as $row): ?>
                <td class="center aligned"><?php echo $row->category_name; ?></td>
              <?php endforeach; ?>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_MAKE; ?></td>
              <?php foreach ($this->data as $row): ?>
                <td class="center aligned"><?php echo $row->make_name; ?></td>
              <?php endforeach; ?>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_MODEL; ?></td>
              <?php foreach ($this->data as $row): ?>
                <td class="center aligned"><?php echo $row->model_name; ?></td>
              <?php endforeach; ?>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_YEAR; ?></td>
              <?php foreach ($this->data as $row): ?>
                <td class="center aligned"><?php echo $row->year; ?></td>
              <?php endforeach; ?>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_TRANS; ?></td>
              <?php foreach ($this->data as $row): ?>
                <td class="center aligned"><?php echo $row->trans_name; ?></td>
              <?php endforeach; ?>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_FUEL; ?></td>
              <?php foreach ($this->data as $row): ?>
                <td class="center aligned"><?php echo $row->fuel_name; ?></td>
              <?php endforeach; ?>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_ODM; ?></td>
              <?php foreach ($this->data as $row): ?>
                <td
                  class="center aligned"><?php echo number_format($row->mileage, 0, '.', ($this->core->odometer == "km" ? "." : ",")); ?><?php echo $this->core->odometer; ?></td>
              <?php endforeach; ?>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_ENGINE; ?></td>
              <?php foreach ($this->data as $row): ?>
                <td class="center aligned"><?php echo $row->engine; ?></td>
              <?php endforeach; ?>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_DOORS; ?></td>
              <?php foreach ($this->data as $row): ?>
                <td class="center aligned"><?php echo $row->doors; ?></td>
              <?php endforeach; ?>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_EXTC; ?></td>
              <?php foreach ($this->data as $row): ?>
                <td class="center aligned"><?php echo $row->color_name; ?></td>
              <?php endforeach; ?>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_SPEED; ?></td>
              <?php foreach ($this->data as $row): ?>
                <td
                  class="center aligned"><?php echo $row->top_speed; ?><?php echo ($this->core->odometer == "km") ? 'km/h' : 'mph'; ?></td>
              <?php endforeach; ?>
          </tr>
          <?php if ($this->features): ?>
              <?php foreach ($this->features as $frow): ?>
              <tr>
                <td><?php echo $frow->name; ?></td>
                  <?php foreach ($this->data as $row): ?>
                    <td
                      class="center aligned"><?php echo(in_array($frow->id, Utility::jSonToArray($row->features)) ? "<i class=\"icon positive check\"></i>" : "<i class=\"icon negative ban\"></i>"); ?></td>
                  <?php endforeach; ?>
              </tr>
              <?php endforeach; ?>
          <?php endif; ?>
          </tbody>
          <tfoot>
          <tr>
            <th></th>
              <?php foreach ($this->data as $row): ?>
                <th class="center aligned"><a href="<?php echo Url::url("/listing/" . $row->idx, $row->slug); ?>"
                    class="wojo primary button"><?php echo Lang::$word->VIEW; ?></a></th>
              <?php endforeach; ?>
          </tr>
          </tfoot>
        </table>
      <?php endif; ?>
  </div>
</div>