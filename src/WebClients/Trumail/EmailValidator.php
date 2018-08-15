<?php

namespace ItForFree\rusphp\WebClients\Trumail;

/**
 * Валидация email-а
 */
class EmailValidator
{
    
    public static $baseUrl = 'https://api.trumail.io/';
    
    protected $guzzleClient = null;
    
    public function __constructor()
    {
        $this->guzzleClient =  new Client([
            'base_uri' => self::$baseUrl,
        ]);
    }
    
    public function verify($email = 'example@example.com')
    {
        $response = $this->getTrumailResponce($email);
        $response->getBody()->getContents();
    }
    
    protected function getTrumailResponce($email)
    {
        $response = $this->guzzleClient->get("v2/lookups/json?email=$email");
        return json_decode($response->getBody()->getContents());
    }
}