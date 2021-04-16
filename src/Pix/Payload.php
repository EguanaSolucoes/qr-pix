<?php

namespace Eguana\QrPix\Pix;

use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;

class Payload
{
    /**
     * IDs do Payload do Pix
     * @var string
     */
    const ID_PAYLOAD_FORMAT_INDICATOR = '00';
    const ID_MERCHANT_ACCOUNT_INFORMATION = '26';
    const ID_MERCHANT_ACCOUNT_INFORMATION_GUI = '00';
    const ID_MERCHANT_ACCOUNT_INFORMATION_KEY = '01';
    const ID_MERCHANT_ACCOUNT_INFORMATION_DESCRIPTION = '02';
    const ID_MERCHANT_CATEGORY_CODE = '52';
    const ID_TRANSACTION_CURRENCY = '53';
    const ID_TRANSACTION_AMOUNT = '54';
    const ID_COUNTRY_CODE = '58';
    const ID_MERCHANT_NAME = '59';
    const ID_MERCHANT_CITY = '60';
    const ID_ADDITIONAL_DATA_FIELD_TEMPLATE = '62';
    const ID_ADDITIONAL_DATA_FIELD_TEMPLATE_TXID = '05';
    const ID_CRC16 = '63';

    /**
     * Chave Pix
     * @var string
     */
    private $pixKey;

    /**
     * Descrição do pagamento
     * @var string
     */
    private $description;

    /**
     * Nome do titular da conta
     *
     * @var string
     */
    private $merchantName;

    /**
     * Cidade da Conta Titular
     *
     * @var string
     */
    private $merchantCity;

    /**
     * ID da transação pix
     * @var string
     */
    private $transactionId;

    /**
     * Valor do pagamento
     * @var string
     */
    private $amount;

    /**
     * Método que define o valor da variável $pixKey
     * @param $pixKey
     * @return $this
     */
    public function setPixKey($pixKey)
    {
        $this->pixKey = $pixKey;
        return $this;
    }

    /**
     * Método que define o valor da variável $description
     * @param $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Método que define o valor da variável $mercahntName
     * @param $merchantName
     * @return $this
     */
    public function setMerchantName($merchantName)
    {
        $this->merchantName = $merchantName;
        return $this;
    }

    /**
     * Método que define o valor da variável $mechantCity
     * @param $merchantCity
     * @return $this
     */
    public function setMerchantCity($merchantCity)
    {
        $this->merchantCity = $merchantCity;
        return $this;
    }

    /**
     * Método que define o valor da variável $transactionId
     * @param $transactionId
     * @return $this
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
        return $this;
    }

    /**
     * Método que define o valor da variável $amount
     * @param float $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = (string) number_format($amount, 2, '.', '');
        return $this;
    }

    /**
     * Método que retonra o valor completo de um objeto do payload
     * @param $id
     * @param $value
     * @return string $id.$size.$alue
     */
    private function getValue($id, $value)
    {
        $size = str_pad(mb_strlen($value), 2,'0', STR_PAD_LEFT);
        return $id.$size.$value;
    }

    /**
     * Método responsável por retornar as informações da conta
     * @return string
     */
    private function getMerchantAccountInformation()
    {
        // Domínio do Banco Central
        $gui = $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION_GUI, 'br.gov.bcb.pix');
        // Chave Pix
        $key = $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION_KEY, $this->pixKey);
        // Descrição da transação
        $description = $this->description !== ''
            ? $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION_DESCRIPTION, $this->description)
            : '';
        // Valor Completo da Conta
         return $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION, $gui.$key.$description);
    }

    /**
     * Mátodo responsáevel por retornar os valores completos do campo adicional do pix
     * @return string
     */
    private function getAdditionalDataFieldTemplate()
    {
        $transactionId = $this->getValue(self::ID_ADDITIONAL_DATA_FIELD_TEMPLATE_TXID, $this->transactionId);
        return $this->getValue(self::ID_ADDITIONAL_DATA_FIELD_TEMPLATE, $transactionId);
    }

    /**
     * Método responsável por calcular o valor da hash de validação do código pix
     * @return string
     */
    private function getCRC16($payload) {
        //ADICIONA DADOS GERAIS NO PAYLOAD
        $payload .= self::ID_CRC16.'04';

        //DADOS DEFINIDOS PELO BACEN
        $polinomio = 0x1021;
        $resultado = 0xFFFF;

        //CHECKSUM
        if (($length = strlen($payload)) > 0) {
            for ($offset = 0; $offset < $length; $offset++) {
                $resultado ^= (ord($payload[$offset]) << 8);
                for ($bitwise = 0; $bitwise < 8; $bitwise++) {
                    if (($resultado <<= 1) & 0x10000) $resultado ^= $polinomio;
                    $resultado &= 0xFFFF;
                }
            }
        }

        //RETORNA CÓDIGO CRC16 DE 4 CARACTERES
        return self::ID_CRC16.'04'.strtoupper(dechex($resultado));
    }

    public function getPayload()
    {
        $payload = $this->getValue(self::ID_PAYLOAD_FORMAT_INDICATOR, '01').
                    $this->getMerchantAccountInformation().
                    $this->getValue(self::ID_MERCHANT_CATEGORY_CODE, '0000').
                    $this->getValue(self::ID_TRANSACTION_CURRENCY, '986').
                    $this->getValue(self::ID_TRANSACTION_AMOUNT, $this->amount).
                    $this->getValue(self::ID_COUNTRY_CODE, 'BR').
                    $this->getValue(self::ID_MERCHANT_NAME, $this->merchantName).
                    $this->getValue(self::ID_MERCHANT_CITY, $this->merchantCity).
                    $this->getAdditionalDataFieldTemplate();
        return $payload.$this->getCRC16($payload);
    }
}