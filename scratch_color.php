<?php
$files = glob('public/images/gmf*.png');
foreach ($files as $file) {
    echo "File: $file\n";
    $im = imagecreatefrompng($file);
    if (!$im) continue;
    $w = imagesx($im);
    $h = imagesy($im);
    echo "Dimension: {$w}x{$h}\n";
    $colors = [];
    for ($x = 0; $x < $w; $x += max(1, (int)($w/50))) {
        for ($y = 0; $y < $h; $y += max(1, (int)($h/50))) {
            $rgba = imagecolorat($im, $x, $y);
            $alpha = ($rgba >> 24) & 0x7F;
            if ($alpha > 100) continue; // transparent
            $r = ($rgba >> 16) & 0xFF;
            $g = ($rgba >> 8) & 0xFF;
            $b = $rgba & 0xFF;
            // Round to nearest 16 to group similar colors
            $rR = round($r / 16) * 16;
            $gR = round($g / 16) * 16;
            $bR = round($b / 16) * 16;
            $hex = sprintf('#%02X%02X%02X', min(255, $rR), min(255, $gR), min(255, $bR));
            $colors[$hex] = ($colors[$hex] ?? 0) + 1;
        }
    }
    arsort($colors);
    print_r(array_slice($colors, 0, 8));
    echo "-------------------\n";
}
