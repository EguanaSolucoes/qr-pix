# QrPix

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]

Pacote desenvolvido com o objetivo de abstratir o calculo e formulação do Payload QR Code do Pix do Banco Central. Além de ter o método getPayload() o pacote também conta com uma classe responsável em retornar a imagem do QR Code.
## Instalação

Via Composer:

``` bash
$ composer require eguana/qr-pix
```

## Uso

```
<?php
use Eguana\QrPix\Pix\Payload;
use \Eguana\QrPix\Pix\QrCodePix;

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

```
## Credits

- [Eguana Soluções][link-author]

[ico-version]: https://img.shields.io/packagist/v/eguana/qr-pix.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/eguana/qr-pix.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/eguana/qr-pix/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/eguana/qr-pix
[link-downloads]: https://packagist.org/packages/eguana/qr-pix
[link-travis]: https://travis-ci.org/eguana/qrpix
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/EguanaSolucoes