<?php
/*
Project     : Ub Thumb - PHP GD Image resize script
Purpose     : Generate thumbnails on the fly maintaining aspect ratio
File name   : ubthumb.php
Version     : 1.0.071213
Author(s)   : Radu Maier
Copyright   : Copyright (C) 2006 Radu Maier maierDOTraduATyahooDOTcom
License     : GNU/GPL
Website     : http://underbeat.evonet.ro

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; version 2 of the License.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public  License for more details.

You should have received a copy of the GNU General Public License
along with this program from the site that you downloaded it
from; if not. write to the Free Software Foundation, Inc., 59 Temple
Place, Suite 330, Boston, MA 02111-1307 USA
*/

//filename
if (!isset($_GET['imgfile'])) {die('Oops!');}
$imgfile = $_GET['imgfile'];

//max size
$max_size = 80;
if (isset($_GET['max_size'])) {$max_size = $_GET['max_size'];}

//output image type
$default_output_type = 'png';
$output_type = $default_output_type;
if (isset($_GET['output_type'])) {$output_type = $_GET['output_type'];
if ($_GET['output_type'] != 'png' && $_GET['output_type'] != 'jpeg') {$output_type = $default_output_type;}
}
// get image info
list($old_width, $old_height, $old_type, $old_attr) = getimagesize($imgfile);

switch ($old_type) {
        case "1": {$new_image = imagecreatefromgif($imgfile) ; break;}
        case "2": {$new_image = imagecreatefromjpeg($imgfile); break;}
        case "3": {$new_image = imagecreatefrompng($imgfile); break;}
        default: {die('image type not supported'); break;}
}
// resize math
if ($old_width > $old_height) { // landscape
    $new_width  = $max_size;
    $new_height = ($old_height * $max_size) / $old_width;
}
else if ($old_width < $old_height) { // portrait
    $new_width  = ($old_width * $max_size) / $old_height;
    $new_height = $max_size;
}
else if ($old_width == $old_height) { //square
    $new_width  = $max_size;
    $new_height = $max_size;
}

// headers
if ($output_type == 'jpeg') {header('Content-type: image/jpeg');}
else if ($output_type == 'png') {header('Content-type: image/png');}

$newsizeimage = imagecreatetruecolor($new_width, $new_height);
imagecopyresampled($newsizeimage, $new_image, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);

// Output
if ($output_type == 'jpeg') {imagejpeg($newsizeimage, null, 100);}
else if ($output_type == 'png') {imagepng($newsizeimage);}

// free memory
imagedestroy($newsizeimage);
?>