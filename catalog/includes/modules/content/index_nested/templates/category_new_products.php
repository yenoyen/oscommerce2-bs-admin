<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2016 osCommerce

  Released under the GNU General Public License
*/

use OSC\OM\HTML;
use OSC\OM\OSCOM;
?>
<div class="col-sm-<?php echo $content_width; ?> category-new-products">

  <h3><?php echo sprintf(MODULE_CONTENT_IN_NEW_PRODUCTS_HEADING, strftime('%B')); ?></h3>

  <div class="row" itemtype="http://schema.org/ItemList">
    <meta itemprop="numberOfItems" content="<?php echo (int)$num_new_products; ?>" />
    <?php
    foreach ($new_products as $product) {
      ?>
    <div class="col-sm-<?php echo $product_width; ?>" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/Product">
      <div class="thumbnail equal-height">
        <a href="<?php echo OSCOM::link('product_info.php', 'products_id=' . (int)$product['products_id']); ?>"><?php echo HTML::image(DIR_WS_IMAGES . $product['products_image'], $product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'itemprop="image"'); ?></a>
        <div class="caption">
          <p class="text-center"><a itemprop="url" href="<?php echo OSCOM::link('product_info.php', 'products_id=' . (int)$product['products_id']); ?>"><span itemprop="name"><?php echo $product['products_name']; ?></span></a></p>
          <hr>
          <p class="text-center" itemprop="offers" itemscope itemtype="http://schema.org/Offer"><span itemprop="price"><?php echo $currencies->display_price($product['products_price'], tep_get_tax_rate($product['products_tax_class_id'])); ?></span></p>
          <div class="text-center">
            <div class="btn-group">
              <a href="<?php echo OSCOM::link('product_info.php', tep_get_all_get_params(array('action')) . 'products_id=' . (int)$product['products_id']); ?>" class="btn btn-default" role="button"><?php echo MODULE_CONTENT_IN_NEW_PRODUCTS_BUTTON_VIEW; ?></a>
              <a href="<?php echo OSCOM::link($PHP_SELF, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . (int)$product['products_id']); ?>" class="btn btn-success" role="button"><?php echo MODULE_CONTENT_IN_NEW_PRODUCTS_BUTTON_BUY; ?></a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
  }
  ?>
  </div>

</div>
