<?php

namespace Ahmeti\Trader\Markets;

use Ahmeti\Trader\Apis\Binance as Api;
use Illuminate\Support\Facades\Mail;

class Binance {

    private $api;

    public function __construct()
    {
        if(is_null($this->api)){
            $this->api = new Api(env('BINANCE_API_KEY'), env('BINANCE_API_SECRET'));
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

        if(isset($orderStatus['status'], $orderStatus['origQty'], $orderStatus['executedQty'], $orderStatus['fills'])){

            if($orderStatus['status']=='FILLED' && $orderStatus['origQty'] === $orderStatus['executedQty'] && is_array($orderStatus['fills'])){

                // Ağırlıklı Ortalama
                $sum1 = [];
                $sum2 = [];
                foreach ($orderStatus['fills'] as $fill){
                    $sum1[]=$fill['qty'] * $fill['price'];
                    $sum2[]=$fill['qty'];
                }

                $orderStatus['price']=array_sum($sum1) / array_sum($sum2);

                return $orderStatus;

            }
            return $orderStatus;
        }

        return $orderStatus;
    }

    public function tradeHistory($coinCode)
    {
        return $this->api->history($coinCode);
    }

    public function tradeHistoryByOrderId($coinCode, $orderId)
    {
        $items = $this->api->history($coinCode);
    }

    public function marketBuy($coinCode, $quantity)
    {
        $marketBuy = $this->api->marketBuy($coinCode, $quantity);

        if(isset($marketBuy['status'], $marketBuy['origQty'], $marketBuy['executedQty'], $marketBuy['fills'])){

            if($marketBuy['status']=='FILLED' && $marketBuy['origQty'] === $marketBuy['executedQty'] && is_array($marketBuy['fills'])){

                // Ağırlıklı Ortalama
                $sum1 = [];
                $sum2 = [];
                foreach ($marketBuy['fills'] as $fill){
                    $sum1[]=$fill['qty'] * $fill['price'];
                    $sum2[]=$fill['qty'];
                }

                $marketBuy['price']=array_sum($sum1) / array_sum($sum2);

                return $marketBuy;

            }
            return $marketBuy;
        }

        return $marketBuy;
    }

    public function marketSell($coinCode, $quantity)
    {
        $marketSell = $this->api->marketSell($coinCode, $quantity);

        if(isset($marketSell['status'], $marketSell['origQty'], $marketSell['executedQty'], $marketSell['fills'])){

            if($marketSell['status']=='FILLED' && $marketSell['origQty'] === $marketSell['executedQty'] && is_array($marketSell['fills'])){

                // Ağırlıklı Ortalama
                $sum1 = [];
                $sum2 = [];
                foreach ($marketSell['fills'] as $fill){
                    $sum1[]=$fill['qty'] * $fill['price'];
                    $sum2[]=$fill['qty'];
                }

                $marketSell['price']=array_sum($sum1) / array_sum($sum2);

                return $marketSell;

            }
            return $marketSell;
        }

        return $marketSell;
    }

    public function sendMail($content)
    {
        Mail::raw($content, function($message)
        {
            $message->from(env('MAIL_USERNAME'), config('app.name'));
            $message->subject('BİLGİ -> COIN BOT');
            $message->to(env('TRADER_ALERT_MAIL'));
        });
    }
}