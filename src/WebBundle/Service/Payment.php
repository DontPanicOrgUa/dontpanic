<?php

namespace WebBundle\Service;


use AdminBundle\Service\Uuid;
use LiqPay;
use Symfony\Component\Translation\Translator;

class Payment
{
    private $liqpay;

    private $public_key;

    private $private_key;

    private $sandBox;

    private $translator;

    private $uuid;

    public function __construct($public_key, $private_key, $sandbox, Translator $translator, Uuid $uuid)
    {
        $this->public_key = $public_key;
        $this->private_key = $private_key;
        $this->liqpay = new LiqPay($this->public_key, $this->private_key);
        $this->translator = $translator;
        $this->uuid = $uuid;
        $this->sandBox = $sandbox;
    }

    /**
     * _________________________________________________________________________________________________________________
     * |   PARAM         |REQUIRED|   TYPE   | DESCRIPTION                                                             |
     * |=================|========|==========|=========================================================================|
     * | version         | yes    | Number   | Версия API. Текущее значение - 3                                        |
     * |-----------------|--------|----------|-------------------------------------------------------------------------|
     * | action          | yes    | String   | Тип операции. Возможные значения: pay - платеж, hold - блокировка       |
     * |                 |        |          | средств на счету отправителя, subscribe - регулярный платеж,            |
     * |                 |        |          | paydonate - пожертвование, auth - предавторизация карты                 |
     * |-----------------|--------|----------|-------------------------------------------------------------------------|
     * | amount          | yes    | Number   | Сумма платежа.Например: 5, 7.34                                         |
     * |-----------------|--------|----------|-------------------------------------------------------------------------|
     * | currency        | yes    | String   | Валюта платежа. Пример значения: USD, EUR, RUB, UAH BYN KZT.            |
     * |                 |        |          | Дополнительные валюты могут быть установлены по запросу компании.       |
     * |-----------------|--------|----------|-------------------------------------------------------------------------|
     * | description     | yes    | String   | Назначение платежа.                                                     |
     * |-----------------|--------|----------|-------------------------------------------------------------------------|
     * | order_id        | yes    | String   | Уникальный ID покупки в Вашем магазине.Максимальная длина 255 символов. |
     * |-----------------|--------|----------|-------------------------------------------------------------------------|
     * | public_key      | no     | String   | Публичный ключ - идентификатор магазина. Получить ключ можно            |
     * |                 |        |          | в настройках магазина                                                   |
     * |-----------------|--------|----------|-------------------------------------------------------------------------|
     * | language        | no     | String   | Язык клиента ru, uk, en.                                                |
     * |-----------------|--------|----------|-------------------------------------------------------------------------|
     * | sandbox         | no     | String   | Включает тестовый режим. Средства с карты плательщика не списываются.   |
     * |                 |        |          | Для включения тестового режима необходимо передать значение 1. Все      |
     * |                 |        |          | тестовые платежи будут иметь статус sandbox - успешный тестовый платеж. |
     * |-----------------|--------|----------|-------------------------------------------------------------------------|
     * | recurringbytoken| no     | String   | Этот параметр позволяет генерировать card_token плательщика, который    |
     * |                 |        |          | вы получите в callback запросе на server_url. card_token позволяет      |
     * |                 |        |          | проводить платежи без ввода реквизитов карты плательщика, используя     |
     * |                 |        |          | API paytoken. Для получения card_token необходимо передать в запросе    |
     * |                 |        |          | значение: 1                                                             |
     * |-----------------|--------|----------|-------------------------------------------------------------------------|
     * | server_url      | no     | String   | URL API в Вашем магазине для уведомлений об изменении статуса платежа   |
     * |                 |        |          | (сервер->сервер). Максимальная длина 510 символов.                      |
     * |-----------------|--------|----------|-------------------------------------------------------------------------|
     * | result_url      | no     | String   | URL в Вашем магазине на который покупатель будет переадресован после    |
     * |                 |        |          | завершения покупки. Максимальная длина 510 символов.                    |
     * |-----------------|--------|----------|-------------------------------------------------------------------------|
     * | paytypes        | no     | String   | Параметр в котором передаются способы оплаты, которые будут отображены  |
     * |                 |        |          | на чекауте. Возможные значения card - оплата картой, liqpay - через     |
     * |                 |        |          | кабинет liqpay, privat24 - через кабинет приват24, masterpass - через   |
     * |                 |        |          | кабинет masterpass, moment_part - рассрочка, cash - наличными,          |
     * |                 |        |          | invoice - счет на e-mail, qr - сканирование qr-кода. Если параметр не   |
     * |                 |        |          | передан, то применяются настройки магазина, вкладка Checkout.           |
     * |-----------------|--------|----------|-------------------------------------------------------------------------|
     * | verifycode      | no     | String   | Возможное значение Y. Динамический код верификации, генерируется и      |
     * |                 |        |          | возвращается в Callback. Так же сгенерированный код будет передан в     |
     * |                 |        |          |транзакции верификации для отображения в выписке по карте клиента.       |
     * |                 |        |          | Работает для action= auth.                                              |
     * -----------------------------------------------------------------------------------------------------------------
     *
     * $this->get('payment')->getButton([
     *     'order_id' => uniqid(),
     *     'amount' => 1,
     *     'currency' => 'UAH',
     *     'language' => 'uk',
     *     'description' => 'test',
     *     'sandbox' => 1,
     * ]);
     *
     * @param array $options
     * @return string
     */
    public function getButton(array $options)
    {
        $options['action'] = 'pay';
        $options['version'] = '3';

        if (!isset($options['language']) || ($options['language'] != 'ru' && $options['language'] != 'en')) {
            $options['language'] = 'en';
        }

        $html = $this->liqpay->cnb_form($options);
        return $this->prettifyButton($html);
    }

    private function prettifyButton($html, $styled = true)
    {
        $oldBtn = '/<input type="image" src=".+" name="btn_text" \/>/';
        $btnText = $this->translator->trans('Pay online and get 5% discount');
        $btnStyles = '';
        if ($styled) {
            $btnStyles = 'style="'
                . 'height: 50px;'
                . 'width: 200px;'
                . 'color: white;'
                . 'font-size: medium;'
                . 'font-weight: bold;'
                . 'background-color: #6ca91c;'
                . 'border-left: 0px;'
                . 'border-right: 0px;'
                . 'border-top: 0px;'
                . 'border-bottom: 4px solid #4c7714;'
                . 'border-radius: 6px;'
                . 'outline: none;'
                . '"';
        }
        $newBtn = '<button type="submit" ' . $btnStyles . '>' . $this->translator->trans($btnText) . '</button>';
        return preg_replace($oldBtn, $newBtn, $html);
    }

    public function getStatus($orderId)
    {
        return $this->liqpay->api("request", [
            'action' => 'status',
            'version' => '3',
            'order_id' => $orderId
        ]);
    }

    public function getBill($bookingData)
    {
        $options = [
            'order_id' => $this->uuid->generate('bill_', 12),
            'amount' => round($bookingData['price'] * 0.95, 2),
            'currency' => $bookingData['currency'],
            'language' => $bookingData['language'],
            'description' => $bookingData['description'],
            'sandbox' => (int)$this->sandBox
        ];
        return [
            'options' => $options,
            'button' => $this->getButton($options)
        ];
    }

}