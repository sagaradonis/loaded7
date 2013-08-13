<?php
/**
  @package    catalog::templates::boxes
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: checkout_trail.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/boxes/checkout_trail.php start-->
<div class="well" >
  <ul class="box-checkout-trail nav nav-list list-style-decimal">
    <li class="nav-header"><?php echo $lC_Box->getTitle(); ?></li>
    <?php echo $lC_Box->getContent(); ?>
  </ul>
</div>
<script>
$(document).ready(function() {
  $(".box-checkout-trail li").each(function(){
    if ($(this).attr('class') != 'nav-header') $(this).attr('style', 'margin-left:15px;');
  });
});
</script>
<!--modules/boxes/checkout_trail.php end-->