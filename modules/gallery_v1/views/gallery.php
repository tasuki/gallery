<?php

echo "<div id='content'>\n";
echo View::factory('gallery/crumbs')->set('crumbs', $crumbs);
echo View::factory('gallery/galleries')->set('galleries', $galleries);
echo View::factory('gallery/images')->set('images', $images);
echo "</div>\n";
echo "<footer>\n";
echo View::factory('gallery/prevnext')->set('neighbors', $neighbors);
echo View::factory('gallery/footer')->set('calibration', $calibration);
echo "</footer>\n";
