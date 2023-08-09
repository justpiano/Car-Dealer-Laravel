<?php
/**
 * Compare
 *
 * @package Wojo Framework
 * @author wojoscripts.com
 * @copyright 2022
 * @version $Id: compare.tpl.php, v1.00 2022-03-02 10:12:05 gewa Exp $
 */
if (!defined("_WOJO"))
  die('Direct access to this location is not allowed.');
?>
<div id="compare" class="<?php echo ($this->compareData) ? "visible" : "hidden";?>">
  <div class="wrapper">
    <div class="compareTab<?php echo ($this->compareData) ? " active" : "";?>">
      <a class="wojo simple compact right button compareButton"><span class="small margin right">(<?php echo count($this->compareData)?>)</span>
      <?php echo Lang::$word->COMPARE;?><i class="icon collection"></i></a>
    </div>
    <div class="inner<?php echo ($this->compareData) ? " hide-all" : "";?>">
      <div class="row grid gutters screen-4 tablet-4 mobile-2 phone-2">
        <?php if ($this->compareData):?>
        <?php $counter = (4 - count($this->compareData));?>
        <?php foreach($this->compareData as $key => $data):?>
        <?php $row = json_decode($data);?>
        <div class="columns" id="column_<?php echo $row->id;?>">
          <figure class="wojo rounded image">
            <a data-id="<?php echo $row->id;?>" class="wojo mini white circular attached top right icon button"><i class="icon x alt"></i></a>
            <a href="<?php echo Url::url("/listing/" . $row->idx, $row->slug);?>"><img src="<?php echo UPLOADURL . '/listings/thumbs/' . $row->thumb;?>" alt="<?php echo $row->nice_title;?>"></a>
          </figure>
          <p class="wojo small text truncate"><?php echo $row->nice_title;?></p>
        </div>
        <?php endforeach;?>
        <?php if($counter):?>
        <?php for ($i = 0; $i < $counter; $i++):?>
        <div class="columns blank">
          <div class="blankCompare"></div>
        </div>
        <?php endfor;?>
        <?php endif;?>
        <?php else:?>
        <?php for ($i = 0; $i < 4; $i++):?>
        <div class="columns blank">
          <div class="blankCompare"></div>
        </div>
        <?php endfor;?>
        <?php endif;?>
      </div>
      <div class="cButton<?php echo count($this->compareData) < 2 ? " hide-all" : null; ?>">
        <a href="<?php echo Url::url("/compare");?>" class="wojo small primary button">
        <?php echo Lang::$word->COMPARE;?></a>
      </div>
      <p class="wojo small primary demi text cMessage<?php echo count($this->compareData) > 1 ? " hide-all" : null; ?>"><?php echo Lang::$word->LST_SUB6;?></p>
    </div>
  </div>
</div>