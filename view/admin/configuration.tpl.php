<?php
    /**
     * Configuration
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2020
     * @version $Id: configuration.tpl.php, v1.00 2020-02-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    if (!Auth::checkAcl("owner")): print Message::msgError(Lang::$word->NOACCESS);
        return;
    endif;
?>
<h2><?php echo Lang::$word->CF_SUB; ?></h2>
<p class="wojo small text"><?php echo Lang::$word->CF_INFO; ?></p>
<form method="post" id="wojo_form" name="wojo_form">
  <div class="wojo segment form">
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->CF_COMPANY; ?>
          <i class="icon asterisk"></i>
        </label>
        <input type="text" placeholder="<?php echo Lang::$word->CF_COMPANY; ?>"
          value="<?php echo $this->core->company; ?>" name="company">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_DIR; ?></label>
        <input type="text" placeholder="<?php echo Lang::$word->CF_DIR; ?>" value="<?php echo $this->core->site_dir; ?>"
          name="site_dir">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->CF_EMAIL; ?>
          <i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->CF_EMAIL; ?>"
          value="<?php echo $this->core->site_email; ?>" name="site_email">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->ADDRESS; ?>
          <i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->ADDRESS; ?>" value="<?php echo $this->core->address; ?>"
          name="address">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->CITY; ?>
          <i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->CITY; ?>" value="<?php echo $this->core->city; ?>"
          name="city">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->STATE; ?>
          <i class="icon asterisk"></i>
        </label>
        <input type="text" placeholder="<?php echo Lang::$word->STATE; ?>" value="<?php echo $this->core->state; ?>"
          name="state">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->ZIP; ?>
          <i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->ZIP; ?>" value="<?php echo $this->core->zip; ?>"
          name="zip">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->COUNTRY; ?><i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->ZIP; ?>" value="<?php echo $this->core->country; ?>"
          name="country">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_PHONE; ?></label>
        <input type="text" placeholder="<?php echo Lang::$word->CF_PHONE; ?>" value="<?php echo $this->core->phone; ?>"
          name="phone">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_FAX; ?></label>
        <input type="text" placeholder="<?php echo Lang::$word->CF_FAX; ?>" value="<?php echo $this->core->fax; ?>"
          name="fax">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->CF_FBID; ?></label>
        <input type="text" placeholder="<?php echo Lang::$word->CF_FBID; ?>"
          value="<?php echo $this->core->social->facebook; ?>" name="facebook">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_TWID; ?></label>
        <input type="text" placeholder="<?php echo Lang::$word->CF_TWID; ?>"
          value="<?php echo $this->core->social->twitter; ?>" name="twitter">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->CF_THEME; ?></label>
        <select name="theme">
            <?php echo Utility::loopOptionsSimple(File::getThemes(FRONTBASE . "/themes"), $this->core->theme); ?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_MAPAPI; ?>
          <span data-tooltip="<?php echo Lang::$word->CF_MAPAPI_T; ?>" data-position="top left"><i
              class="icon question circle"></i></span></label>
        <input type="text" placeholder="<?php echo Lang::$word->CF_MAPAPI; ?>"
          value="<?php echo $this->core->mapapi; ?>" name="mapapi">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field auto">
        <label><?php echo Lang::$word->CF_LOGO; ?>
          <span data-tooltip="<?php echo Lang::$word->CF_LOGO_T; ?>" data-position="top left"><i
              class="icon question circle"></i></span></label>
        <input type="file" name="logo" id="logo" class="filestyle" data-input="false" data-badge="true">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_LOGODEL; ?></label>
        <div class="wojo toggle inline fitted checkbox">
          <input name="dellogo" type="checkbox" value="1" id="dellogo">
          <label for="dellogo"><?php echo Lang::$word->YES; ?></label>
        </div>
      </div>
      <div class="field auto">
        <label><?php echo Lang::$word->CF_LOGOI; ?></label>
        <input type="file" name="plogo" id="plogo" class="filestyle" data-input="false" data-badge="true">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_LOGODEL; ?></label>
        <div class="wojo toggle inline fitted checkbox">
          <input name="dellogop" type="checkbox" value="1" id="dellogop">
          <label for="dellogop"><?php echo Lang::$word->YES; ?></label>
        </div>
      </div>
    </div>
    <div class="wojo auto very wide divider"></div>
    <div class="wojo fields">
      <div class="field five wide">
        <label><?php echo Lang::$word->CF_LONGDATE; ?>
          <i class="icon asterisk"></i></label>
        <select name="long_date">
            <?php echo Date::getLongDate($this->core->long_date); ?>
        </select>
      </div>
      <div class="field three wide">
        <label><?php echo Lang::$word->CF_SHORTDATE; ?>
          <i class="icon asterisk"></i></label>
        <select name="short_date">
            <?php echo Date::getShortDate($this->core->short_date); ?>
        </select>
      </div>
      <div class="field two wide">
        <label><?php echo Lang::$word->CF_TIME; ?>
          <i class="icon asterisk"></i></label>
        <select name="time_format">
            <?php echo Date::getTimeFormat($this->core->time_format); ?>
        </select>
      </div>
    </div>
    <div class="wojo fields">
      <div class="field five wide">
        <label><?php echo Lang::$word->CF_WEEKSTART; ?></label>
        <select name="weekstart">
            <?php echo Date::weekList(true, true, $this->core->weekstart); ?>
        </select>
      </div>
      <div class="field three wide">
        <label><?php echo Lang::$word->CF_LANG; ?></label>
        <select name="lang">
            <?php foreach (Lang::fetchLanguage() as $langlist): ?>
              <option
                value="<?php echo substr($langlist, 0, 2); ?>" <?php echo Validator::getSelected($this->core->lang, substr($langlist, 0, 2)); ?>><?php echo strtoupper(substr($langlist, 0, 2)); ?></option>
            <?php endforeach; ?>
        </select>
      </div>
      <div class="field two wide">
        <label><?php echo Lang::$word->CF_PERPAGE; ?>
          <i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->CF_PERPAGE; ?>"
          value="<?php echo $this->core->perpage; ?>" name="perpage">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field five wide">
        <label><?php echo Lang::$word->CF_DTZ; ?></label>
        <select name="dtz">
            <?php echo Date::getTimezones(); ?>
        </select>
      </div>
      <div class="field three wide">
        <label><?php echo Lang::$word->CF_LOCALES; ?></label>
        <select name="locale">
            <?php echo Date::localeList($this->core->locale); ?>
        </select>
      </div>
      <div class="field two wide">
        <label><?php echo Lang::$word->CF_CALDATE; ?>
          <i class="icon asterisk"></i></label>
        <select name="calendar_date">
            <?php echo Date::getCalendarDate($this->core->calendar_date); ?>
        </select>
      </div>
    </div>
    <div class="wojo auto very wide divider"></div>
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->CF_INVI; ?>
          <span data-tooltip="<?php echo Lang::$word->CF_INVI_T; ?>" data-position="top left"><i
              class="icon question circle"></i></span>
        </label>
        <textarea class="altpost" name="inv_info"><?php echo $this->core->inv_info; ?></textarea>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_INVF; ?></label>
        <textarea class="altpost" name="inv_note"><?php echo $this->core->inv_note; ?></textarea>
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->CF_CURRENCY; ?>
          <i class="icon asterisk"></i><span data-tooltip="<?php echo Lang::$word->CF_CURRENCY_T; ?>"
            data-position="top left"><i class="icon question circle"></i></span></label>
        <input type="text" placeholder="<?php echo Lang::$word->CF_CURRENCY; ?>"
          value="<?php echo $this->core->currency; ?>" name="currency">
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_TAX; ?>
          <span data-tooltip="<?php echo Lang::$word->CF_TAX_T; ?>" data-position="top left"><i
              class="icon question circle"></i></span></label>
        <div class="wojo checkbox radio fitted inline">
          <input name="enable_tax" type="radio" value="1"
            id="tax_1" <?php echo Validator::getChecked($this->core->enable_tax, 1); ?>>
          <label for="tax_1"><?php echo Lang::$word->YES; ?></label>
        </div>
        <div class="wojo checkbox radio fitted inline">
          <input name="enable_tax" type="radio" value="0"
            id="tax_0" <?php echo Validator::getChecked($this->core->enable_tax, 0); ?>>
          <label for="tax_0"><?php echo Lang::$word->NO; ?></label>
        </div>
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_FEATURED; ?>
          <i class="icon asterisk"></i><span data-tooltip="<?php echo Lang::$word->CF_FEATURED_T; ?>"
            data-position="top left"><i class="icon question circle"></i></span></label>
        <input name="featured" type="range" min="5" max="50" step="1" value="<?php echo $this->core->featured; ?>"
          hidden data-suffix=" items">
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_SPERPAGE; ?>
          <i class="icon asterisk"></i><span data-tooltip="<?php echo Lang::$word->CF_SPERPAGE_T; ?>"
            data-position="top left"><i class="icon question circle"></i></span></label>
        <input name="sperpage" type="range" min="5" max="100" step="1" value="<?php echo $this->core->sperpage; ?>"
          hidden data-suffix=" items">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_EUCOOKIE; ?></label>
        <div class="wojo checkbox radio fitted inline">
          <input name="eucookie" type="radio" value="1"
            id="eucookie_1" <?php echo Validator::getChecked($this->core->eucookie, 1); ?>>
          <label for="eucookie_1"><?php echo Lang::$word->YES; ?></label>
        </div>
        <div class="wojo checkbox radio fitted inline">
          <input name="eucookie" type="radio" value="0"
            id="eucookie_0" <?php echo Validator::getChecked($this->core->eucookie, 0); ?>>
          <label for="eucookie_0"><?php echo Lang::$word->NO; ?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_FILESIZE; ?>
          <i class="icon asterisk"></i></label>
        <div class="wojo labeled input">
          <input type="text" placeholder="<?php echo Lang::$word->CF_FILESIZE; ?>"
            value="<?php echo($this->core->file_size / (1024 * 1024)); ?>" name="file_size">
          <span class="wojo simple label">mb</span>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_THUMBWH; ?>
          <i class="icon asterisk"></i></label>
        <div class="wojo labeled input">
          <input name="thumb_w" type="text" value="<?php echo $this->core->thumb_w; ?>">
          <span class="wojo simple label">px</span>
          <input name="thumb_h" type="text" value="<?php echo $this->core->thumb_h; ?>">
          <span class="wojo simple label">px</span>
        </div>
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_VIEW; ?></label>
        <select name="listing_view">
          <option
            value="grid" <?php echo Validator::getSelected($this->core->listing_view, "grid"); ?>><?php echo Lang::$word->GRID; ?></option>
          <option
            value="list" <?php echo Validator::getSelected($this->core->listing_view, "list"); ?>><?php echo Lang::$word->LIST; ?></option>
        </select>
      </div>
    </div>
    <div class="wojo auto very wide divider"></div>
    <div class="wojo fields">
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_NOTIFY; ?>
          <span data-tooltip="<?php echo Lang::$word->CF_NOTIFY_T; ?>" data-position="top left"><i
              class="icon question circle"></i></span></label>
        <div class="wojo checkbox radio fitted inline">
          <input name="notify_admin" type="radio" value="1"
            id="notify_admin_1" <?php echo Validator::getChecked($this->core->notify_admin, 1); ?>>
          <label for="notify_admin_1"><?php echo Lang::$word->YES; ?></label>
        </div>
        <div class="wojo checkbox radio fitted inline">
          <input name="notify_admin" type="radio" value="0"
            id="notify_admin_0" <?php echo Validator::getChecked($this->core->notify_admin, 0); ?>>
          <label for="notify_admin_0"><?php echo Lang::$word->NO; ?></label>
        </div>
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_NOTIFY_EMAIL; ?>
          <span data-tooltip="<?php echo Lang::$word->CF_NOTIFY_EMAIL_T; ?>" data-position="top left"><i
              class="icon question circle"></i></span></label>
        <input type="text" value="<?php echo $this->core->notify_email; ?>" name="notify_email">
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_SOLD; ?>
          <span data-tooltip="<?php echo Lang::$word->CF_SOLD_T; ?>" data-position="top left"><i
              class="icon question circle"></i></span></label>
        <input name="number_sold" type="range" min="1" max="20" step="1" value="<?php echo $this->core->number_sold; ?>"
          hidden data-suffix=" days">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_SPEAD; ?></label>
        <select name="odometer">
          <option
            value="km"<?php if ($this->core->odometer == "km") echo " selected=\"selected\""; ?>><?php echo Lang::$word->KM; ?></option>
          <option
            value="mi"<?php if ($this->core->odometer == "mi") echo " selected=\"selected\""; ?>><?php echo Lang::$word->MI; ?></option>
        </select>
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_SHOWHOME; ?>
          <span data-tooltip="<?php echo Lang::$word->CF_SHOWHOME_T; ?>" data-position="top left"><i
              class="icon question circle"></i></span>
        </label>
        <div class="wojo checkbox radio fitted inline">
          <input type="radio" name="show_home" value="1"
            id="show_home_1" <?php echo Validator::getChecked($this->core->show_home, 1); ?>>
          <label for="show_home_1"><?php echo Lang::$word->YES; ?></label>
        </div>
        <div class="wojo checkbox radio fitted inline">
          <input type="radio" name="show_home" value="0"
            id="show_home_0" <?php echo Validator::getChecked($this->core->show_home, 0); ?>>
          <label for="show_home_0"><?php echo Lang::$word->NO; ?></label>
        </div>
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_SHOWSLIDER; ?>
          <span data-tooltip="<?php echo Lang::$word->CF_SHOWSLIDER_T; ?>" data-position="top left"><i
              class="icon question circle"></i></span></label>
        <div class="wojo checkbox radio fitted inline">
          <input type="radio" name="show_slider" value="1"
            id="show_slider_1" <?php echo Validator::getChecked($this->core->show_slider, 1); ?>>
          <label for="show_slider_1"><?php echo Lang::$word->YES; ?></label>
        </div>
        <div class="wojo checkbox radio fitted inline">
          <input type="radio" name="show_slider" value="0"
            id="show_slider_0" <?php echo Validator::getChecked($this->core->show_slider, 0); ?>>
          <label for="show_slider_0"><?php echo Lang::$word->NO; ?></label>
        </div>
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_SHOWNEWS; ?>
          <span data-tooltip="<?php echo Lang::$word->CF_SHOWNEWS_T; ?>" data-position="top left"><i
              class="icon question circle"></i></span>
        </label>
        <div class="wojo checkbox radio fitted inline">
          <input type="radio" name="show_news" value="1"
            id="show_news_1" <?php echo Validator::getChecked($this->core->show_news, 1); ?>>
          <label for="show_news_1"><?php echo Lang::$word->YES; ?></label>
        </div>
        <div class="wojo checkbox radio fitted inline">
          <input type="radio" name="show_news" value="0"
            id="show_news_0" <?php echo Validator::getChecked($this->core->show_news, 0); ?>>
          <label for="show_news_0"><?php echo Lang::$word->NO; ?></label>
        </div>
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_AUTOAPP; ?>
          <span data-tooltip="<?php echo Lang::$word->CF_AUTOAPP_T; ?>" data-position="top left"><i
              class="icon question circle"></i></span></label>
        <div class="wojo checkbox radio fitted inline">
          <input type="radio" name="autoapprove" value="1"
            id="autoapprove_1" <?php echo Validator::getChecked($this->core->autoapprove, 1); ?>>
          <label for="autoapprove_1"><?php echo Lang::$word->YES; ?></label>
        </div>
        <div class="wojo checkbox radio fitted inline">
          <input type="radio" name="autoapprove" value="0"
            id="autoapprove_0" <?php echo Validator::getChecked($this->core->autoapprove, 0); ?>>
          <label for="autoapprove_0"><?php echo Lang::$word->NO; ?></label>
        </div>
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_POPULAR; ?>
          <span data-tooltip="<?php echo Lang::$word->CF_POPULAR_T; ?>" data-position="top left"><i
              class="icon question circle"></i></span>
        </label>
        <div class="wojo checkbox radio fitted inline">
          <input type="radio" name="show_popular" value="1"
            id="show_popular_1" <?php echo Validator::getChecked($this->core->show_popular, 1); ?>>
          <label for="show_popular_1"><?php echo Lang::$word->YES; ?></label>
        </div>
        <div class="wojo checkbox radio fitted inline">
          <input type="radio" name="show_popular" value="0"
            id="show_popular_0" <?php echo Validator::getChecked($this->core->show_popular, 0); ?>>
          <label for="show_popular_0"><?php echo Lang::$word->NO; ?></label>
        </div>
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_REVIEW; ?>
          <span data-tooltip="<?php echo Lang::$word->CF_REVIEW_T; ?>" data-position="top left"><i
              class="icon question circle"></i></span>
        </label>
        <div class="wojo checkbox radio fitted inline">
          <input type="radio" name="show_reviews" value="1"
            id="show_reviews_1" <?php echo Validator::getChecked($this->core->show_reviews, 1); ?>>
          <label for="show_reviews_1"><?php echo Lang::$word->YES; ?></label>
        </div>
        <div class="wojo checkbox radio fitted inline">
          <input type="radio" name="show_reviews" value="0"
            id="show_reviews_0" <?php echo Validator::getChecked($this->core->show_reviews, 0); ?>>
          <label for="show_reviews_0"><?php echo Lang::$word->NO; ?></label>
        </div>
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_BRANDS; ?>
          <span data-tooltip="<?php echo Lang::$word->CF_BRANDS_T; ?>" data-position="top left"><i
              class="icon question circle"></i></span>
        </label>
        <div class="wojo checkbox radio fitted inline">
          <input type="radio" name="show_brands" value="1"
            id="show_brands_1" <?php echo Validator::getChecked($this->core->show_brands, 1); ?>>
          <label for="show_brands_1"><?php echo Lang::$word->YES; ?></label>
        </div>
        <div class="wojo checkbox radio fitted inline">
          <input type="radio" name="show_brands" value="0"
            id="show_brands_0" <?php echo Validator::getChecked($this->core->show_brands, 0); ?>>
          <label for="show_brands_0"><?php echo Lang::$word->NO; ?></label>
        </div>
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_FEATURED; ?>
          <span data-tooltip="<?php echo Lang::$word->CF_FEATURED_T; ?>" data-position="top left"><i
              class="icon question circle"></i></span>
        </label>
        <div class="wojo checkbox radio fitted inline">
          <input type="radio" name="show_featured" value="1"
            id="show_featured_1" <?php echo Validator::getChecked($this->core->show_featured, 1); ?>>
          <label for="show_featured_1"><?php echo Lang::$word->YES; ?></label>
        </div>
        <div class="wojo checkbox radio fitted inline">
          <input type="radio" name="show_featured" value="0"
            id="show_featured_0" <?php echo Validator::getChecked($this->core->show_featured, 0); ?>>
          <label for="show_featured_0"><?php echo Lang::$word->NO; ?></label>
        </div>
      </div>
    </div>
    <div class="wojo auto very wide divider"></div>
    <div class="wojo fields">
      <div class="five wide field">
        <label><?php echo Lang::$word->CF_OFFLINE; ?></label>
        <div class="wojo checkbox radio fitted inline">
          <input name="offline" type="radio" value="1"
            id="offline_1" <?php echo Validator::getChecked($this->core->offline, 1); ?>>
          <label for="offline_1"><?php echo Lang::$word->YES; ?></label>
        </div>
        <div class="wojo checkbox radio fitted inline">
          <input name="offline" type="radio" value="0"
            id="offline_0" <?php echo Validator::getChecked($this->core->offline, 0); ?>>
          <label for="offline_0"><?php echo Lang::$word->NO; ?></label>
        </div>
      </div>
      <div class="five wide field">
        <label><?php echo Lang::$word->CF_OFFLINE_DATE; ?></label>
        <div class="wojo icon input">
          <input name="offline_d" type="text" placeholder="<?php echo Lang::$word->CF_OFFLINE_DATE; ?>"
            value="<?php echo Date::doDate("calendar", $this->core->offline_d); ?>" readonly class="datepick">
          <i class="icon date"></i>
          <input name="offline_t" type="text" placeholder="<?php echo Lang::$word->CF_OFFLINE_TIME; ?>"
            value="<?php echo $this->core->offline_t; ?>" readonly class="timepick">
          <i class="icon clock"></i>
        </div>
      </div>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->CF_OFFLINE_MSG; ?></label>
      <textarea class="altpost" name="offline_msg"><?php echo $this->core->offline_msg; ?></textarea>
    </div>
  </div>
  <div class="wojo segment form">
    <div class="wojo fields">
      <div class="field five wide">
        <label><?php echo Lang::$word->CF_MAILER; ?></label>
        <select name="mailer" id="mailerchange">
          <option value="SMAIL" <?php echo Validator::getSelected($this->core->mailer, "SMAIL"); ?>>Sendmail</option>
          <option value="SMTP" <?php echo Validator::getSelected($this->core->mailer, "SMTP"); ?>>SMTP Mailer</option>
        </select>
      </div>
      <div class="field showsmail<?php echo ($this->core->mailer == "SMAIL") ? '' : ' hide-all'; ?>">
        <label><?php echo Lang::$word->CF_SMAILPATH; ?></label>
        <input type="text" placeholder="<?php echo Lang::$word->CF_SMAILPATH; ?>"
          value="<?php echo $this->core->sendmail; ?>" name="sendmail">
      </div>
    </div>
    <div class="showsmtp<?php echo ($this->core->mailer == "SMTP") ? '' : ' hide-all'; ?>">
      <div class="wojo fields">
        <div class="field five wide">
          <label><?php echo Lang::$word->CF_SMTP_HOST; ?>
            <i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->CF_SMTP_HOST; ?>"
            value="<?php echo $this->core->smtp_host; ?>" name="smtp_host">
        </div>
        <div class="field five wide">
          <label><?php echo Lang::$word->CF_SMTP_USER; ?></label>
          <input type="text" placeholder="<?php echo Lang::$word->CF_SMTP_USER; ?>"
            value="<?php echo $this->core->smtp_user; ?>" name="smtp_user">
        </div>
      </div>
      <div class="wojo fields">
        <div class="field three wide">
          <label><?php echo Lang::$word->CF_SMTP_PASS; ?></label>
          <input type="text" placeholder="<?php echo Lang::$word->CF_SMTP_PASS; ?>"
            value="<?php echo $this->core->smtp_pass; ?>" name="smtp_pass">
        </div>
        <div class="field three wide">
          <label><?php echo Lang::$word->CF_SMTP_PORT; ?></label>
          <input type="text" placeholder="<?php echo Lang::$word->CF_SMTP_PORT; ?>"
            value="<?php echo $this->core->smtp_port; ?>" name="smtp_port">
        </div>
        <div class="field four wide">
          <label><?php echo Lang::$word->CF_SMTP_SSL; ?></label>
          <div class="wojo checkbox small radio fitted toggle inline">
            <input name="is_ssl" type="radio" value="1"
              id="is_ssl_1" <?php echo Validator::getChecked($this->core->is_ssl, 1); ?>>
            <label for="is_ssl_1">SSL</label>
          </div>
          <div class="wojo checkbox small radio fitted toggle inline">
            <input name="is_ssl" type="radio" value="0"
              id="is_ssl_0" <?php echo Validator::getChecked($this->core->is_ssl, 0); ?>>
            <label for="is_ssl_0">TLS</label>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="wojo segment form">
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->CF_GA; ?>
          <span data-tooltip="<?php echo Lang::$word->CF_GA_T; ?>" data-position="top left"><i
              class="icon question circle"></i></span></label>
        <input type="text" placeholder="<?php echo Lang::$word->CF_GA; ?>" value="<?php echo $this->core->analytics; ?>"
          name="analytics">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_VINAPI; ?><small>https://www.auto.dev</small>
          <span data-tooltip="<?php echo Lang::$word->CF_VINAPI_T; ?>" data-position="top left"><i
              class="icon question circle"></i></span></label>
        <input type="text" placeholder="<?php echo Lang::$word->CF_VINAPI; ?>"
          value="<?php echo $this->core->vinapi; ?>" name="vinapi">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->CF_METAKEY; ?></label>
        <textarea name="metakeys" class="small"
          placeholder="<?php echo Lang::$word->CF_METAKEY; ?>"><?php echo $this->core->metakeys; ?></textarea>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_METADESC; ?></label>
        <textarea name="metadesc" class="small"
          placeholder="<?php echo Lang::$word->CF_METADESC; ?>"><?php echo $this->core->metadesc; ?></textarea>
      </div>
    </div>
  </div>
  <div class="center aligned">
    <button type="button" data-action="processConfig" name="dosubmit"
      class="wojo primary button"><?php echo Lang::$word->CF_UPDATE; ?></button>
  </div>
</form>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $('#mailerchange').change(function () {
         const val = $("#mailerchange option:selected").val();
         if (val === "SMTP") {
            $('.showsmtp').show();
            $('.showsmail').hide();
         } else {
            $('.showsmtp').hide();
            $('.showsmail').show();
         }
      });
   });
   // ]]>
</script>