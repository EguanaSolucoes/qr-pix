<?php

namespace Eguana\QrPix\Pix;

use Mpdf\QrCode\Output\Png;
use Mpdf\QrCode\QrCode;

class QrCodePix
{
    /**
     * @var QrCode
     */
    private $objQrCode;

    /**
     * @throws \Mpdf\QrCode\QrCodeException
     */
    public function setObjQrCode($payload)
    {
        $this->objQrCode = new \Mpdf\QrCode\QrCode($payload);
        return $this;
    }

    public function getImageQrCode($size)
    {
        return (new Png)->output($this->objQrCode, $size);
    }
}