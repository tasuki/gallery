<?php echo View::factory('gallery/crumbs')->set('crumbs', $crumbs); ?>

<div class="navigation"><?php echo $message ?></div>

<?php echo View::factory('gallery/footer')->set('calibration', $calibration); ?>
