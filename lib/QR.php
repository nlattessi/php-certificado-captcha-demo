<?php

require_once __DIR__ . '/../vendor/phpqrcode/qrlib.php';

function generarQR($url) {
    $tempDir = __DIR__ . '/../img/';

    $fileName = "qrcode.png";

    $pngAbsoluteFilePath = $tempDir . $fileName;

    QRCode::png($url, $pngAbsoluteFilePath, 'L', '4', '4');

    return $pngAbsoluteFilePath;
}
