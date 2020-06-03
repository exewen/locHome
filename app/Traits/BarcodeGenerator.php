<?php
namespace App\Traits;

use Picqer\Barcode\BarcodeGeneratorJPG;
use Picqer\Barcode\BarcodeGeneratorPNG;

Trait BarcodeGenerator
{
    /**生成条码,全部用code128-A码,从wms拷贝而来
     * @param $content
     * @param int $widthFactor
     * @param int $height
     * @return string
     */
    public  function generateBarcodeImageString($content)
    {
        $generator = new BarcodeGeneratorPNG();
        $img_str = "data:image/png;base64," . base64_encode($generator->getBarcode($content,$generator::TYPE_CODE_128,20,50));
        return $img_str;
    }

    /**生成二维码,从wms拷贝而来
     * @param $content
     * @param int $height
     * @param int $width
     * @return string
     */
    public  function generateQRcodeBase64ImageString($content,$height = 100, $width = 100)
    {
        $renderer = new \BaconQrCode\Renderer\Image\Png();
        $renderer->setHeight($height);
        $renderer->setWidth($width);
        $renderer->setMargin(0);
        $writer = new \BaconQrCode\Writer($renderer);
        $img_str = "data:image/png;base64,".base64_encode($writer->writeString($content));
        return $img_str;
    }
}