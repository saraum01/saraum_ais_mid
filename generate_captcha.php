<?php
session_start();

// Generate a random number to determine the correct shape
$shapes = ['circle', 'square', 'triangle'];
$correct_shape = $shapes[array_rand($shapes)];
$_SESSION['captcha'] = $correct_shape; // Save the correct shape in session

// Create an image
$image = imagecreatetruecolor(200, 200);

// Set background color and shape color
$bg_color = imagecolorallocate($image, 0, 0, 0); // Black background
$shape_color = imagecolorallocate($image, 255, 0, 0); // Red shapes

// Fill the background
imagefill($image, 0, 0, $bg_color);

// Randomly generate shapes
for ($i = 0; $i < 5; $i++) {
    $x = rand(50, 150);
    $y = rand(50, 150);
    $size = rand(20, 50);

    // Draw a random shape (circle, square, or triangle)
    if ($i == 0) {
        // Draw a circle
        imagefilledellipse($image, $x, $y, $size, $size, $shape_color);
    } elseif ($i == 1) {
        // Draw a square
        imagefilledrectangle($image, $x, $y, $x + $size, $y + $size, $shape_color);
    } else {
        // Draw a triangle
        imagefilledpolygon($image, [ $x, $y, $x + $size, $y, $x + $size / 2, $y - $size ], 3, $shape_color);
    }
}

// Output the image
header("Content-Type: image/png");
imagepng($image);
imagedestroy($image);
?>
