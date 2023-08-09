<?php
    /**
     * F.A.Q.
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _faq.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="wojo-grid">
  <div class="row align center">
    <div class="columns screen-70 tablet-100 mobile-100 phone-100">
      <div class="wojo rounded big full padding mt3 mb3">
        <h1><?php echo $this->row->title; ?></h1>
          <?php echo $this->row->body; ?>
          <?php if ($this->questions): ?>
              <?php foreach ($this->questions as $row): ?>
              <article class="wojo accordion" data-waccordion='{"closeOther" : true}'>
                <section>
                  <h6 class="summary">
                    <a><?php echo Url::out_url($row->question); ?></a>
                  </h6>
                  <div class="details"><?php echo $row->answer; ?></div>
                </section>
              </article>
              <?php endforeach; ?>
          <?php endif; ?>
      </div>
    </div>
  </div>
</div>