<?php

namespace PagarMeAPICreditCard;

class PagarMeAPICreditCard
{
    /**
     * @var int
     */
    public $idCard;
    
    /**
     * @var string
     */
    private $api_key;
    
    /**
     * @var string
     */
    public $hash;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $exp_date;

    /**
     * @var string
     */
    private $card_number;

    /**
     * @var string
     */
    private $card_cvv;

    /**
     * @var string
     */
    private $card_name;

    /**
     * response
     *
     * @var mixed
     */
    private $response;

    /**
     * __construct
     *
     * @param string $key
     * @return void
     */
    public function __construct(string $key)
    {
        $this->url = "https://api.pagar.me/1";
        $this->api_key = $key;
    }

    /**
     * createCard
     *
     * @return PagarMeAPICreditCard
     */
    public function createCard(string $card_number, string $card_name, string $expiration_month, string $expiration_year, string $card_cvv): PagarMeAPICreditCard
    {   
        $this->card_number = $card_number;
        $this->card_name = $card_name;
        $this->exp_date = $expiration_month . substr($expiration_year, -2);
        $this->card_cvv = $card_cvv;

        $create_card = [
            "api_key" => $this->api_key,
            "card_expiration_date" => $this->clear($this->exp_date),
            "card_number" => filter_var($this->card_number, FILTER_SANITIZE_STRING),
            "card_cvv" => filter_var($this->card_cvv, FILTER_SANITIZE_STRING),
            "card_holder_name" => filter_var($this->card_name, FILTER_SANITIZE_STRING)
        ];

        $this->response = $this->curl($this->url . "/cards", $create_card);
        
        if (empty($this->response->id) || !$this->response->valid) {
            return $this;
        }

        return $this;
    }

    /**
     * transactions
     *
     * @param  mixed $amount
     * @return PagarMeAPICreditCard
     */
    public function transactions($amount, $hash): PagarMeAPICreditCard
    {
        $transaction = [
            "api_key" => $this->api_key,
            "payment_method" => "credit_card",
            "card_id" => $hash,
            "amount" => $this->clear($amount)
        ];

        $this->response = $this->curl($this->url . "/transactions", $transaction);

        if (empty($this->response->status) || $this->response->status != "paid") {
            return $this;
        }

        return $this;
    }

    /**
     * curl
     *
     * @param  mixed $url
     * @param  mixed $params
     * @return object
     */
    private function curl($url, $params): object
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response);

        return $response;
    }
    
    /**
     * clear
     *
     * @param string $number
     * @return string
     */
    private function clear(string $number): string
    {
        return preg_replace("/[^0-9]/", "", $number);
    }

    /**
     * Get response
     *
     * @return object
     */ 
    public function getResponse(): object
    {
        return $this->response;
    }
}
