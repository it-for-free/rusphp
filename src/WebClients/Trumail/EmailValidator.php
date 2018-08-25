<?php

namespace ItForFree\rusphp\WebClients\Trumail;

use GuzzleHttp\Client;

/**
 * Валидация verify email-а
 */
class EmailValidator
{
    
    public $email = 'example@example.com';
    public static $baseUrl = 'https://api.trumail.io/';
    
    protected $guzzleClient = null;
    
    public function __construct()
    {
        $this->guzzleClient =  new Client([
            'base_uri' => self::$baseUrl,
        ]);
        
    }
    
    /**
     * Check mail is deliverable
     * 
     * @param string $email
     * @return bool
     */
    public function verify($email)
    {
        $Response = $this->getTrumailResponce($email);
        return ($Response->deliverable ?? false);
    }
    
    /**
     * Check mail is deliverable and (!) server not in "catch-All" mode
     * @see http://fkn.ktu10.com/?q=node/10336
     * 
     * @param string $email
     * @return bool
     */
    public function strongVerify($email)
    {
        $Response = $this->getTrumailResponce($email);
        return ($Response->deliverable ?? false) 
            && (!($Response->catchAll ?? false));
    }
   
    /**
     * Return full answer of https://api.trumail.io/ for curren email
     * 
     * @param string $email
     * @return object
     */
    public function getTrumailResponce($email)
    {
        $response = $this->guzzleClient->get("v2/lookups/json?email=$email");
        return json_decode($response->getBody()->getContents());
    }
}