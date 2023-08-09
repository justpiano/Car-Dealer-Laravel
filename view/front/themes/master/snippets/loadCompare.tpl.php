<?php
/**
 * load Compare
 *
 * @package Wojo Framework
 * @author wojoscripts.com
 * @copyright 2022
 * @version $Id: loadCompare.tpl.php, v1.00 2022-03-02 10:12:05 gewa Exp $
 */
if (!defined("_WOJO"))
  die('Direct access to this location is not allowed.');
?>
<?php if($this->row):?>
<div class="columns" id="column_<?php echo $this->row->id;?>">
  <figure class="wojo rounded image">
    <a data-id="<?php echo $this->row->id;?>" class="wojo mini white circular attached top right icon button"><i class="icon x alt"></i></a>
    <a href="<?php echo Url::url("/listing/" . $this->row->idx, $this->row->slug);?>"><img src="<?php echo UPLOADURL . '/listings/thumbs/' . $this->row->thumb;?>" alt="<?php echo $this->row->nice_title;?>"></a>
  </figure>
  <p class="wojo small text truncate"><?php echo $this->row->nice_title;?></p>
</div>
<?php endif;?>