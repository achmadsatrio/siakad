<?php
 
class Jwt
{
	function __construct() {
        $this->secret_key = 'd0n7STOPmeN@wPorTal';
        $this->secret_key_webstruk = 'N0t4S3cr3t!#6372';
        $this->header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    }
    
    public function get_token_webstruk($param)
    {
        // Create token payload as a JSON string
        $payload = json_encode($param);

        // Encode Header to Base64Url String
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($this->header));

        // Encode Payload to Base64Url String
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secret_key_webstruk, true);

        // Encode Signature to Base64Url String
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        // Create JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        return $jwt;
    }
	
	public function get_token($param)
    {
        // Create token payload as a JSON string
        $payload = json_encode($param);

        // Encode Header to Base64Url String
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($this->header));

        // Encode Payload to Base64Url String
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secret_key, true);

        // Encode Signature to Base64Url String
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        // Create JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        return $jwt;
    }

    public function get_static_token() {
        $b64header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode('https://majoo.id'));
        $b64payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode('aUtHtOApI'));
        $signature = hash_hmac('sha256', $b64header . "." . $b64payload, $this->secret_key, true);
        $b64sign = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        $jwt = $b64header . "." . $b64payload . "." . $b64sign;

        return $jwt;
    }

    public function get_upload_token() {
        $b64header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode('https://majoo.id'));
        $b64payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode('iW4nT2UpLoAd'));
        $signature = hash_hmac('sha256', $b64header . "." . $b64payload, $this->secret_key, true);
        $b64sign = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        $jwt = $b64header . "." . $b64payload . "." . $b64sign;

        return $jwt;
    }

    public function check_token($token) {
        $av = $this->get_static_token();
        // aHR0cHM6Ly9tYWpvby5pZA.YVV0SHRPQXBJ.0fMAGQ1_g9DXeX1CWr1kLqZlsQYg4dO3ankutzCTM2w
        
        if ($token === $av) {
            return true;
        }

        return false;
    }

    public function check_upload_token($token) {
        $av = $this->get_upload_token();
        // aHR0cHM6Ly9tYWpvby5pZA.aVc0blQyVXBMb0Fk.bJj21XgpphXZOKH9NmJD49YIFBGv1-qfQCFBjy-8JJU
        
        if ($token === $av) {
            return true;
        }

        return false;
    }
}
