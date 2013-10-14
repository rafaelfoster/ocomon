<?php 

 header ("Content-type: image/png");
$im = @ImageCreate (50, 100)
    or die ("Cannot create a new GD image.");
$background_color = ImageColorAllocate ($im, 255, 255, 255);
$text_color = ImageColorAllocate ($im, 233, 14, 91);
ImageString ($im, 1, 5, 5,  "A Simple Text String", $text_color);
ImagePng ($im);


?>