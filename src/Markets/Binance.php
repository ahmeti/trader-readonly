<?php

namespace Ahmeti\Trader\Markets;

use Binance\API;
use Illuminate\Support\Facades\Mail;

class Binance {

    private $api;

    public function __construct()
    {
        if(is_null($this->api)){
            $this->api = new API(env('BINANCE_API_KEY'), env('BINANCE_API_SECRET'));
        }
    }

    public function checkOpenOrder($coinCode, $orderId)
    {
        $openOrders = $this->api->openOrders($coinCode);

        foreach ($openOrders as $openOrder){
            if($openOrder['symbol'] == $coinCode && $openOrder['orderId'] == $orderId){
                return true;
            }
        }

        return false;
    }

    public function orderStatus($coinCode, $orderId)
    {
        $orderStatus = $this->api->orderStatus($coinCode, $orderId);

        if($orderStatus['origQty'] === $orderStatus['executedQty']){
            return true;
        }

        return false;
    }

    public function tradeHistory($coinCode)
    {
        return $this->api->history($coinCode);
    }

    public function marketBuy($coinCode, $quantity)
    {
        return $this->api->marketBuy($coinCode, $quantity);
    }

    public function marketSell($coinCode, $quantity)
    {
        return $this->api->marketSell($coinCode, $quantity);
    }

    public function sendMail($content)
    {
        Mail::raw($content, function($message)
        {
            $message->from(env('MAIL_USERNAME'), config('app.name'));
            $message->subject('COIN BOT - Hata oluştu!');
            $message->to(env('ALERT_MAIL'));
        });
    }
}