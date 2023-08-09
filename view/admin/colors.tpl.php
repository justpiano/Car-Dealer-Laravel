<?php
    /**
     * Colors
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: colors.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    if (!Auth::checkAcl("owner")): print Message::msgError(Lang::$word->NOACCESS);
        return; endif;
?>
<h2><?php echo Lang::$word->CF_COLORS; ?></h2>
<p class="wojo small text"><?php echo Lang::$word->CF_INFO1; ?></p>
<div class="wojo form segment">
  <form method="post" name="wojo_formt">
    <div class="wojo small fields">
      <div class="field">
        <label>Body Bg Color</label>
        <input data-wcolor="full" name="body-bg-color"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['body-bg-color']; ?>"}'
          type="text" value="<?php echo $this->colors['body-bg-color']; ?>">
      </div>
      <div class="field">
        <label>Body Color</label>
        <input data-wcolor="full" name="body-color"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['body-color']; ?>"}'
          type="text" value="<?php echo $this->colors['body-color']; ?>">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label>Primary</label>
        <input data-wcolor="full" name="primary-color"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['primary-color']; ?>"}'
          type="text" value="<?php echo $this->colors['primary-color']; ?>">
      </div>
      <div class="field">
        <label>Primary Hover</label>
        <input data-wcolor="full" name="primary-color-hover"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['primary-color-hover']; ?>"}'
          type="text" value="<?php echo $this->colors['primary-color-hover']; ?>">
      </div>
      <div class="field">
        <label>Primary Active</label>
        <input data-wcolor="full" name="primary-color-active"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['primary-color-active']; ?>"}'
          type="text" value="<?php echo $this->colors['primary-color-active']; ?>">
      </div>
      <div class="field">
        <label>Primary Inverted</label>
        <input data-wcolor="full" name="primary-color-inverted"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['primary-color-inverted']; ?>"}'
          type="text" value="<?php echo $this->colors['primary-color-inverted']; ?>">
      </div>
      <div class="field">
        <label>Primary Shadow</label>
        <input data-wcolor="full" name="primary-color-shadow"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['primary-color-shadow']; ?>"}'
          type="text" value="<?php echo $this->colors['primary-color-shadow']; ?>">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label>Secondary</label>
        <input data-wcolor="full" name="secondary-color"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['secondary-color']; ?>"}'
          type="text" value="<?php echo $this->colors['secondary-color']; ?>">
      </div>
      <div class="field">
        <label>Secondary Hover</label>
        <input data-wcolor="full" name="secondary-color-hover"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['secondary-color-hover']; ?>"}'
          type="text" value="<?php echo $this->colors['secondary-color-hover']; ?>">
      </div>
      <div class="field">
        <label>Secondary Active</label>
        <input data-wcolor="full" name="secondary-color-active"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['secondary-color-active']; ?>"}'
          type="text" value="<?php echo $this->colors['secondary-color-active']; ?>">
      </div>
      <div class="field">
        <label>Secondary Inverted</label>
        <input data-wcolor="full" name="secondary-color-inverted"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['secondary-color-inverted']; ?>"}'
          type="text" value="<?php echo $this->colors['secondary-color-inverted']; ?>">
      </div>
      <div class="field">
        <label>Secondary Shadow</label>
        <input data-wcolor="full" name="secondary-color-shadow"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['secondary-color-shadow']; ?>"}'
          type="text" value="<?php echo $this->colors['secondary-color-shadow']; ?>">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label>Positive</label>
        <input data-wcolor="full" name="positive-color"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['positive-color']; ?>"}'
          type="text" value="<?php echo $this->colors['positive-color']; ?>">
      </div>
      <div class="field">
        <label>Positive Hover</label>
        <input data-wcolor="full" name="positive-color-hover"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['positive-color-hover']; ?>"}'
          type="text" value="<?php echo $this->colors['positive-color-hover']; ?>">
      </div>
      <div class="field">
        <label>Positive Active</label>
        <input data-wcolor="full" name="positive-color-active"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['positive-color-active']; ?>"}'
          type="text" value="<?php echo $this->colors['positive-color-active']; ?>">
      </div>
      <div class="field">
        <label>Positive Inverted</label>
        <input data-wcolor="full" name="positive-color-inverted"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['positive-color-inverted']; ?>"}'
          type="text" value="<?php echo $this->colors['positive-color-inverted']; ?>">
      </div>
      <div class="field">
        <label>Positive Shadow</label>
        <input data-wcolor="full" name="positive-color-shadow"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['positive-color-shadow']; ?>"}'
          type="text" value="<?php echo $this->colors['positive-color-shadow']; ?>">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label>Negative</label>
        <input data-wcolor="full" name="negative-color"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['negative-color']; ?>"}'
          type="text" value="<?php echo $this->colors['negative-color']; ?>">
      </div>
      <div class="field">
        <label>Negative Hover</label>
        <input data-wcolor="full" name="negative-color-hover"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['negative-color-hover']; ?>"}'
          type="text" value="<?php echo $this->colors['negative-color-hover']; ?>">
      </div>
      <div class="field">
        <label>Negative Active</label>
        <input data-wcolor="full" name="negative-color-active"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['negative-color-active']; ?>"}'
          type="text" value="<?php echo $this->colors['negative-color-active']; ?>">
      </div>
      <div class="field">
        <label>Negative Inverted</label>
        <input data-wcolor="full" name="negative-color-inverted"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['negative-color-inverted']; ?>"}'
          type="text" value="<?php echo $this->colors['negative-color-inverted']; ?>">
      </div>
      <div class="field">
        <label>Negative Shadow</label>
        <input data-wcolor="full" name="negative-color-shadow"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['negative-color-shadow']; ?>"}'
          type="text" value="<?php echo $this->colors['negative-color-shadow']; ?>">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label>Alert</label>
        <input data-wcolor="full" name="alert-color"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['alert-color']; ?>"}'
          type="text" value="<?php echo $this->colors['alert-color']; ?>">
      </div>
      <div class="field">
        <label>Alert Hover</label>
        <input data-wcolor="full" name="alert-color-hover"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['alert-color-hover']; ?>"}'
          type="text" value="<?php echo $this->colors['alert-color-hover']; ?>">
      </div>
      <div class="field">
        <label>Alert Active</label>
        <input data-wcolor="full" name="alert-color-active"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['alert-color-active']; ?>"}'
          type="text" value="<?php echo $this->colors['alert-color-active']; ?>">
      </div>
      <div class="field">
        <label>Alert Inverted</label>
        <input data-wcolor="full" name="alert-color-inverted"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['alert-color-inverted']; ?>"}'
          type="text" value="<?php echo $this->colors['alert-color-inverted']; ?>">
      </div>
      <div class="field">
        <label>Alert Shadow</label>
        <input data-wcolor="full" name="alert-color-shadow"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['alert-color-shadow']; ?>"}'
          type="text" value="<?php echo $this->colors['alert-color-shadow']; ?>">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label>Info</label>
        <input data-wcolor="full" name="info-color"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['info-color']; ?>"}'
          type="text" value="<?php echo $this->colors['info-color']; ?>">
      </div>
      <div class="field">
        <label>Info Hover</label>
        <input data-wcolor="full" name="info-color-hover"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['info-color-hover']; ?>"}'
          type="text" value="<?php echo $this->colors['info-color-hover']; ?>">
      </div>
      <div class="field">
        <label>Info Active</label>
        <input data-wcolor="full" name="info-color-active"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['info-color-active']; ?>"}'
          type="text" value="<?php echo $this->colors['info-color-active']; ?>">
      </div>
      <div class="field">
        <label>Info Inverted</label>
        <input data-wcolor="full" name="info-color-inverted"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['info-color-inverted']; ?>"}'
          type="text" value="<?php echo $this->colors['info-color-inverted']; ?>">
      </div>
      <div class="field">
        <label>Info Shadow</label>
        <input data-wcolor="full" name="info-color-shadow"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['info-color-shadow']; ?>"}'
          type="text" value="<?php echo $this->colors['info-color-shadow']; ?>">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label>Light</label>
        <input data-wcolor="full" name="light-color"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['light-color']; ?>"}'
          type="text" value="<?php echo $this->colors['light-color']; ?>">
      </div>
      <div class="field">
        <label>Light Hover</label>
        <input data-wcolor="full" name="light-color-hover"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['light-color-hover']; ?>"}'
          type="text" value="<?php echo $this->colors['light-color-hover']; ?>">
      </div>
      <div class="field">
        <label>Light Active</label>
        <input data-wcolor="full" name="light-color-active"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['light-color-active']; ?>"}'
          type="text" value="<?php echo $this->colors['light-color-active']; ?>">
      </div>
      <div class="field">
        <label>Light Inverted</label>
        <input data-wcolor="full" name="light-color-inverted"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['light-color-inverted']; ?>"}'
          type="text" value="<?php echo $this->colors['light-color-inverted']; ?>">
      </div>
      <div class="field">
        <label>Light Shadow</label>
        <input data-wcolor="full" name="light-color-shadow"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['light-color-shadow']; ?>"}'
          type="text" value="<?php echo $this->colors['light-color-shadow']; ?>">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label>Dark</label>
        <input data-wcolor="full" name="dark-color"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['dark-color']; ?>"}'
          type="text" value="<?php echo $this->colors['dark-color']; ?>">
      </div>
      <div class="field">
        <label>Dark Hover</label>
        <input data-wcolor="full" name="dark-color-hover"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['dark-color-hover']; ?>"}'
          type="text" value="<?php echo $this->colors['dark-color-hover']; ?>">
      </div>
      <div class="field">
        <label>Dark Active</label>
        <input data-wcolor="full" name="dark-color-active"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['dark-color-active']; ?>"}'
          type="text" value="<?php echo $this->colors['dark-color-active']; ?>">
      </div>
      <div class="field">
        <label>Dark Inverted</label>
        <input data-wcolor="full" name="dark-color-inverted"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['dark-color-inverted']; ?>"}'
          type="text" value="<?php echo $this->colors['dark-color-inverted']; ?>">
      </div>
      <div class="field">
        <label>Dark Shadow</label>
        <input data-wcolor="full" name="dark-color-shadow"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['dark-color-shadow']; ?>"}'
          type="text" value="<?php echo $this->colors['dark-color-shadow']; ?>">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label>Grey</label>
        <input data-wcolor="full" name="grey-color"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['grey-color']; ?>"}'
          type="text" value="<?php echo $this->colors['grey-color']; ?>">
      </div>
      <div class="field">
        <label>Grey 100</label>
        <input data-wcolor="full" name="grey-color-100"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['grey-color-100']; ?>"}'
          type="text" value="<?php echo $this->colors['grey-color-100']; ?>">
      </div>
      <div class="field">
        <label>Grey 300</label>
        <input data-wcolor="full" name="grey-color-300"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['grey-color-300']; ?>"}'
          type="text" value="<?php echo $this->colors['grey-color-300']; ?>">
      </div>
      <div class="field">
        <label>Grey 500</label>
        <input data-wcolor="full" name="grey-color-500"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['grey-color-500']; ?>"}'
          type="text" value="<?php echo $this->colors['grey-color-500']; ?>">
      </div>
      <div class="field">
        <label>Grey 700</label>
        <input data-wcolor="full" name="grey-color-700"
          data-color='{"showInput":true,"showPaletteOnly":true,"color": "<?php echo $this->colors['grey-color-700']; ?>"}'
          type="text" value="<?php echo $this->colors['grey-color-700']; ?>">
      </div>
    </div>
    <div class="center aligned">
      <button type="button" data-action="processColors" name="dosubmit"
        class="wojo small primary button"><?php echo Lang::$word->UPDATE; ?></button>
    </div>
  </form>
</div>
