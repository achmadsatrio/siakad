<?php

use Firebase\JWT\JWT;

class Token_parser {

    private $secret_key = 'rahasia';

    public function __construct() 
    {
        $this->ci =& get_instance();
    }

    public function generate($payload)
    {
        $date = date('Y-m-d H:i:s');
        $dateLong = strtotime($date);
        $expired = $dateLong + (1 * 7200);
        $payload->kadaluarsa = $expired;
        $token_payload = JWT::encode($payload, $this->secret_key);

        return $token_payload;
    }

    public function decode($token)
    {
       return JWT::decode($token, $this->secret_key, ['HS256']);
    }

}