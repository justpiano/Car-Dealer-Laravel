<?php
    /**
     * Profile
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: profile.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="wojo big vertical padding">
  <div class="wojo-grid">
      <?php include_once(THEMEBASE . '/snippets/dashNav.tpl.php'); ?>
    <h3><?php echo Lang::$word->HOME_SUB12; ?></h3>
    <p class="wojo big bottom margin"><?php echo Lang::$word->HOME_SUB12P; ?></p>
    <div class="row align center">
      <div class="columns screen-60 tablet-80 mobile-100 phone-100">
        <form method="post" id="wojo_form" name="wojo_form">
          <div class="wojo form">
            <div class="wojo fields align middle">
              <div class="field four wide labeled">
                <label><?php echo Lang::$word->AVATAR; ?></label>
              </div>
              <div class="field">
                <input type="file" name="avatar" data-type="image"
                  data-exist="<?php echo ($this->row->avatar) ? UPLOADURL . '/avatars/' . $this->row->avatar : UPLOADURL . '/avatars/blank.svg'; ?>"
                  accept="image/png, image/jpeg">
              </div>
            </div>
            <div class="wojo fields align middle">
              <div class="field four wide labeled">
                <label><?php echo Lang::$word->EMAIL; ?>
                  <i class="icon asterisk"></i></label>
              </div>
              <div class="field">
                <input type="text" placeholder="<?php echo Lang::$word->EMAIL; ?>"
                  value="<?php echo $this->row->email; ?>" name="email">
              </div>
            </div>
            <div class="wojo fields align middle">
              <div class="field four wide labeled">
                <label><?php echo Lang::$word->NAME; ?>
                  <i class="icon asterisk"></i></label>
              </div>
              <div class="field three wide">
                <input type="text" placeholder="<?php echo Lang::$word->FNAME; ?>"
                  value="<?php echo $this->row->fname; ?>" name="fname">
              </div>
              <div class="field three wide">
                <input type="text" placeholder="<?php echo Lang::$word->FNAME; ?>"
                  value="<?php echo $this->row->lname; ?>" name="lname">
              </div>
            </div>
            <div class="wojo fields align middle">
              <div class="field four wide labeled">
                <label><?php echo Lang::$word->PASSWORD; ?></label>
              </div>
              <div class="field">
                <input type="password" placeholder="<?php echo Lang::$word->PASSWORD; ?>" name="password">
              </div>
            </div>
              <?php if ($this->core->enable_tax): ?>
                <div class="wojo fields align middle">
                  <div class="field four wide labeled">
                    <label><?php echo Lang::$word->ADDRESS; ?>
                      <i class="icon asterisk"></i></label>
                  </div>
                  <div class="field">
                    <input type="text" placeholder="<?php echo Lang::$word->ADDRESS; ?>"
                      value="<?php echo $this->row->address; ?>" name="address">
                  </div>
                </div>
                <div class="wojo fields align middle">
                  <div class="field four wide labeled">
                    <label><?php echo Lang::$word->CITY; ?>/<?php echo Lang::$word->STATE; ?>
                      <i class="icon asterisk"></i></label>
                  </div>
                  <div class="field three wide">
                    <input type="text" placeholder="<?php echo Lang::$word->CITY; ?>"
                      value="<?php echo $this->row->city; ?>" name="city">
                  </div>
                  <div class="field three wide">
                    <input type="text" placeholder="<?php echo Lang::$word->STATE; ?>"
                      value="<?php echo $this->row->state; ?>" name="state">
                  </div>
                </div>
                <div class="wojo fields align middle">
                  <div class="field four wide labeled">
                    <label><?php echo Lang::$word->COUNTRY; ?>/<?php echo Lang::$word->ZIP; ?>
                      <i class="icon asterisk"></i>
                    </label>
                  </div>
                  <div class="field">
                    <div class="wojo action input">
                      <input type="text" placeholder="<?php echo Lang::$word->ZIP; ?>"
                        value="<?php echo $this->row->zip; ?>" name="zip">
                      <select name="country">
                          <?php echo Utility::loopOptions($this->clist, "abbr", "name", $this->row->country); ?>
                      </select>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            <div class="wojo fields align middle">
              <div class="field four wide labeled"></div>
              <div class="field">
                <button class="bottom fluid wojo primary button" data-action="profile" name="dosubmit"
                  type="button"><?php echo Lang::$word->M_UPDATE; ?></button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>