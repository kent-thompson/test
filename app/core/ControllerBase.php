<?php
namespace App\core;
require_once 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
// NOTE:
// $jwt = JWT::encode($payload, $this->secretKey, 'HS256');
// $decoded = JWT::decode($jwt, new Key($this->secretKey, 'HS256'));

class ControllerBase {
    protected $mIsAuth = false;
    protected $mPayload;
    // JWT - put in config file or ENV
    protected $secretKey = 'e6311e81b59543c8aae070c54a28b801'; // TODO

    public function __construct() {
    }

    protected function AuthApi() {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                //$m = $matches[1];
                try {    
                    $this->mPayload = JWT::decode($matches[1], new Key($this->secretKey, 'HS256'));
                } catch( LogicException $e ) {
                    // errors having to do with environmental setup or malformed JWT Keys
                    $s = 'NOT AUTHORIZED: ' . $e->getMessage();
                    throw new \Exception( $s );
                } catch( UnexpectedValueException $e ) {
                    // errors having to do with JWT signature and claims
                    $s = 'NOT AUTHORIZED: ' . $e->getMessage();
                    throw new \Exception( $s );
                }
                $this->mIsAuth = true;
            }
        }
    }

    protected function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    protected function AuthUI() {
        // $token = '';
        // $val = strtoupper($_SERVER['REQUEST_METHOD']);
        // switch( $val ) {
        //     case 'GET':
        //         $token = $_GET['jwt'];
        //         break;
        //     case 'POST':
        //         $token = $_POST['jwt'];
        //         break;
        // }
        $token = $_POST['jwt'];
        if (isset($token) && $token !== '') {
            try {
                $this->mPayload = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            } catch( LogicException $e ) {
                // errors having to do with environmental setup or malformed JWT Keys
                $s = 'NOT AUTHORIZED: ' . $e->getMessage();
                throw new \Exception( $s );
            } catch( UnexpectedValueException $e ) {
                // errors having to do with JWT signature and claims
                $s = 'NOT AUTHORIZED: ' . $e->getMessage();
                throw new \Exception( $s );
            }
            $this->mIsAuth = true;
        } else {
            throw new \Exception("NOT AUTHORIZED - Please Login "); 
        }
    }

    protected function getIsAuth() {
        if( $this->mIsAuth ) {
            return true;
        } else {
            return false;
        }
    }    

    protected function setPayload( &$data) {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

        $payload = [
            'iat' => time(),
            'exp' => time() + 60*60*4, // + 4 hours TODO: get time from single source
            'role' => 'user'
        ];
        $this->mPayload = array_merge($payload, $data);
    }
}

// $jwt = JWT::encode($payload, $this->secretKey, 'HS256');
// $decoded = JWT::decode($jwt, new Key($this->secretKey, 'HS256'));

    // protected function login( $reqInfo ) {
    //     if( $reqInfo == POST ) {
    //         require_once MODEL . 'user.php';
    //         $model = new \App\model\User;
    //         $uname = $_POST['uname'];
    //         $pwd = $_POST['psw'];

    //         $data = [];
    //         $model->getUserByName( $uname, $data );  // $data passed as an OUT param            

    //         // compare passwords
    //         $rslt = password_verify( $pwd, $data['Password'] );
    //         if( $rslt == false ) {
    //             $GLOBALS['error_data'] = 'Incorrect Login Data';
    //             include_once VIEWS . '404.php';
    //             return;
    //         }

    //         $payload = [
    //             'iat' => time(),
    //             'exp' => time() + 60*60*4, // + 4 hours
    //             'role' => 'user',
    //             'ID' => $data['UserID'],
    //             'UserName' => $data['UserName']
    //         ];

    //         // send back jwt
    //         $jwt;
    //         try {
    //             $jwt = JWT::encode($payload, $this->secretKey, 'HS256');
    //         } catch( \Exception $e ) {
    //             echo $e->getMessage(), __LINE__,'<br>';
    //         } catch( \Error $er) {
    //             echo $er->getMessage(), __LINE__,'<br>';
    //         }                    
    //         header( 'Authorization: Bearer ' . $jwt );
    //     }
    // }
