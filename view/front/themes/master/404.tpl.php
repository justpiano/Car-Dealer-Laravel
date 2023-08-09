<?php
    /**
     * 404 Page
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: 404.tpl.php, v1.00 2022-05-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="wojo-grid">
  <div class="wojo fof basic simple card">
    <div class="content">
      <h1><?php echo Lang::$word->META_ERROR; ?></h1>
      <figure class="wojo big inline image margin bottom"><img src="<?php echo UPLOADURL; ?>/images/error.svg" alt="">
      </figure>
      <p><?php echo Lang::$word->META_ERROR1; ?> :(</p>
      <p><?php echo Lang::$word->META_ERROR2; ?></p>
    </div>
  </div>
</div>