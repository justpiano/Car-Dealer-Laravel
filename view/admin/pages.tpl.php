<?php
    /**
     * Pages
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: pages.tpl.php, v1.00 2022-05-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<?php switch (Url::segment($this->segments)): case "edit": ?>
    <?php if (!Auth::hasPrivileges('edit_pages')): print Message::msgError(Lang::$word->NOACCESS);
        return; endif; ?>
  <!-- Start edit -->
  <h2><?php echo Lang::$word->PAG_SUB1; ?></h2>
  <p class="wojo small text"><?php echo Lang::$word->PAG_INFO1; ?></p>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="wojo segment form">
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->PAG_NAME; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholdder="<?php echo Lang::$word->PAG_NAME; ?>"
            value="<?php echo $this->row->title; ?>" name="title">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->PAG_SLUG; ?>
            <span data-tooltip="<?php echo Lang::$word->PAG_SLUG_T; ?>"><i
                class="icon question circle"></i></span></label>
          <input type="text" placeholdder="<?php echo Lang::$word->PAG_SLUG; ?>" value="<?php echo $this->row->slug; ?>"
            name="slug">
        </div>
      </div>
      <div class="wojo fields">
        <div class="field">
          <textarea class="bodypost" name="body"><?php echo Url::out_url($this->row->body); ?></textarea>
        </div>
      </div>
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->METAKEYS; ?></label>
          <textarea class="small" placeholder="<?php echo Lang::$word->METAKEYS; ?>"
            name="keywords"><?php echo $this->row->keywords; ?></textarea>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->METADESC; ?></label>
          <textarea class="small" placeholder="<?php echo Lang::$word->METADESC; ?>"
            name="description"><?php echo $this->row->description; ?></textarea>
        </div>
      </div>
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->PUBLISHED; ?></label>
          <div class="wojo checkbox radio inline">
            <input name="active" type="radio" value="1"
              id="active_1" <?php echo Validator::getChecked($this->row->active, 1); ?>>
            <label for="active_1"><?php echo Lang::$word->YES; ?></label>
          </div>
          <div class="wojo checkbox radio inline">
            <input name="active" type="radio" value="0"
              id="active_0" <?php echo Validator::getChecked($this->row->active, 0); ?>>
            <label for="active_0"><?php echo Lang::$word->NO; ?></label>
          </div>
        </div>
      </div>
      <div class="center aligned">
        <a href="<?php echo Url::url("/admin/pages"); ?>"
          class="wojo small simple button"><?php echo Lang::$word->CANCEL; ?></a>
        <button type="button" data-action="processPage" name="dosubmit"
          class="wojo secondary button"><?php echo Lang::$word->PAG_UPDATE; ?></button>
      </div>
    </div>
    <input type="hidden" name="id" value="<?php echo $this->row->id; ?>">
  </form>
    <?php break; ?>
  <!-- Start new -->
<?php case "new": ?>
    <?php if (!Auth::hasPrivileges('add_pages')): print Message::msgError(Lang::$word->NOACCESS);
        return; endif; ?>
  <h2><?php echo Lang::$word->PAG_SUB2; ?></h2>
  <p class="wojo small text"><?php echo Lang::$word->PAG_INFO2; ?></p>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="wojo segment form">
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->PAG_NAME; ?></label>
          <input type="text" placeholdder="<?php echo Lang::$word->PAG_NAME; ?>" name="title">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->PAG_SLUG; ?>
            <span data-tooltip="<?php echo Lang::$word->PAG_SLUG_T; ?>"><i
                class="icon question circle"></i></span></label>
          <input type="text" placeholdder="<?php echo Lang::$word->PAG_SLUG; ?>" name="slug">
        </div>
      </div>
      <div class="wojo fields">
        <div class="field">
          <textarea class="bodypost" name="body"></textarea>
        </div>
      </div>
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->METAKEYS; ?></label>
          <textarea class="small" placeholder="<?php echo Lang::$word->METAKEYS; ?>" name="keywords"></textarea>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->METADESC; ?></label>
          <textarea class="small" placeholder="<?php echo Lang::$word->METADESC; ?>" name="description"></textarea>
        </div>
      </div>
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->PUBLISHED; ?></label>
          <div class="wojo checkbox radio inline">
            <input name="active" type="radio" value="1" id="active_1" checked="checked">
            <label for="active_1"><?php echo Lang::$word->YES; ?></label>
          </div>
          <div class="wojo checkbox radio inline">
            <input name="active" type="radio" value="0" id="active_0">
            <label for="active_0"><?php echo Lang::$word->NO; ?></label>
          </div>
        </div>
      </div>
      <div class="center aligned">
        <a href="<?php echo Url::url("/admin/pages"); ?>"
          class="wojo small simple button"><?php echo Lang::$word->CANCEL; ?></a>
        <button type="button" data-action="processPage" name="dosubmit"
          class="wojo secondary button"><?php echo Lang::$word->PAG_ADD; ?></button>
      </div>
    </div>
  </form>
    <?php break; ?>
<?php default: ?>
  <div class="row gutters align middle">
    <div class="columns phone-100">
      <h2><?php echo Lang::$word->PAG_TITLE; ?></h2>
      <p class="wojo small text"><?php echo Lang::$word->PAG_INFO; ?></p>
    </div>
    <div class="columns auto phone-100">
      <a href="<?php echo Url::url("/admin/pages", "new"); ?>" class="wojo secondary small stacked button"><i
          class="icon plus alt"></i><?php echo Lang::$word->PAG_ADD; ?></a>
    </div>
  </div>
    <?php if (!$this->data): ?>
    <div class="center aligned"><img src="<?php echo ADMINVIEW; ?>/images/nop.svg" alt="">
      <p class="wojo small demi caps text"><?php echo Lang::$word->PAG_NOPAGE; ?></p>
    </div>
    <?php else: ?>
    <table class="wojo fitted segment responsive table">
      <thead>
      <tr>
        <th class="center aligned auto"></th>
        <th><?php echo Lang::$word->PAG_NAME; ?></th>
        <th class="center aligned"><?php echo Lang::$word->TYPE; ?></th>
        <th class="center aligned"><?php echo Lang::$word->ACTIONS; ?></th>
      </tr>
      </thead>
        <?php foreach ($this->data as $row): ?>
          <tr id="item_<?php echo $row->id; ?>">
            <td><span class="wojo small simple label"><?php echo $row->id; ?></span></td>
            <td><a href="<?php echo Url::url(Router::$path, "edit/" . $row->id); ?>">
                    <?php echo $row->title; ?></a></td>
            <td class="auto center aligned"><?php if ($row->type == "contact"): ?>
                <i class="icon secondary envelope disabled"></i>
                <?php elseif ($row->type == "home"): ?>
                <i class="icon primary house disabled"></i>
                <?php elseif ($row->type == "faq"): ?>
                <i class="icon positive chat square text disabled"></i>
                <?php else: ?>
                <i class="icon file disabled"></i>
                <?php endif; ?></td>
            <td class="auto"><a href="<?php echo Url::url(Router::$path, "edit/" . $row->id); ?>"
                class="wojo icon primary inverted circular button"><i class="icon pencil line"></i></a>
                <?php if ($row->type == "normal"): ?>
                  <a
                    data-set='{"option":[{"trash":"trashPage","title": "<?php echo Validator::sanitize($row->title, "chars"); ?>","id": "<?php echo $row->id; ?>"}],"action":"trash","parent":"#item_<?php echo $row->id; ?>"}'
                    class="wojo icon simple button data">
                    <i class="icon negative trash"></i>
                  </a>
                <?php else: ?>
                  <a class="wojo icon simple disabled button">
                    <i class="icon x alt"></i>
                  </a>
                <?php endif; ?></td>
          </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    <?php break; ?>
<?php endswitch; ?>