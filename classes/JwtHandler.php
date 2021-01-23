<?php
require __DIR__.'/../jwt/src/JWT.php';
require __DIR__.'/../jwt/src/ExpiredException.php';
require __DIR__.'/../jwt/src/SignatureInvalidException.php';
require __DIR__.'/../jwt/src/BeforeValidException.php';

use \Firebase\JWT\JWT;

class JwtHandler {
    protected $jwt_secrect;
    protected $token;
    protected $issuedAt;
    protected $expire;
    protected $jwt;

    public function __construct()
    {

        date_default_timezone_set('Europe/Warsaw');
        $this->issuedAt = time();
        
        // Token Validity (7200 second = 2hr)
        $this->expire = $this->issuedAt + 7200;

        // Set your secret or signature
        $this->jwt_secret = "this_is_my_secret";  
    }

    // ENCODING THE TOKEN
    public function _jwt_encode_data($iss,$data){

        $this->token = array(

            "iss" => $iss,
            "aud" => $iss,

            "iat" => $this->issuedAt,

            "exp" => $this->expire,

            "data"=> $data
        );

        $this->jwt = JWT::encode($this->token, $this->jwt_secret);
        return $this->jwt;

    }

    protected function _errorMessage($message){
        return [
            "auth" => 0,
            "message" => $message
        ];
    }
    

    public function _jwt_decode_data($jwt_token){
        try{
            $decode = JWT::decode($jwt_token, $this->jwt_secret, array('HS256'));
            return [
                "auth" => 1,
                "data" => $decode->data
            ];
        }
        catch(\Firebase\JWT\ExpiredException $e){
            return $this->_errorMessage($e->getMessage());
        }
        catch(\Firebase\JWT\SignatureInvalidException $e){
            return $this->_errorMessage($e->getMessage());
        }
        catch(\Firebase\JWT\BeforeValidException $e){
            return $this->_errorMessage($e->getMessage());
        }
        catch(\DomainException $e){
            return $this->_errorMessage($e->getMessage());
        }
        catch(\InvalidArgumentException $e){
            return $this->_errorMessage($e->getMessage());
        }
        catch(\UnexpectedValueException $e){
            return $this->_errorMessage($e->getMessage());
        }

    }
}