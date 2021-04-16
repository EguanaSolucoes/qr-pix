<?php

use Eguana\QrPix\Pix\Payload;
use \Eguana\QrPix\Pix\QrCodePix;

require __DIR__ . '/bootstrap.php';

     // Instancia principal do payload pix
     $obPayload = (new Payload())->setPixKey('wesley@agits.com.br')
                                 ->setDescription('Contribuição para o pacote')
                                 ->setMerchantName('Wesley Serafim Araujo')
                                 ->setMerchantCity('Rio de Janeiro')
                                 ->setAmount(10.00)
                                 ->setTransactionId('6079e46d3c45d');
    // Código de pagamento
    $payloadQrCode = $obPayload->getPayload();
    $image = (new QrCodePix)->setObjQrCode($payloadQrCode)
        ->getImageQrCode(100);
    ?>
<h1>QR Code Pix</h1>
<br>
<img src="data:image/png;base64, <?= base64_encode($image)?>" alt="">
<p>
    <strong>Código Pix:</strong>
    <?= $payloadQrCode ?>
</p>