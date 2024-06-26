<?php
namespace App\api;
require_once CORE . 'ControllerBase.php';
require_once MODEL . 'user.php';
require_once SERVICE . 'user.php';
require_once DATABASE . 'userEntity.php';

require_once 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class User extends \App\core\ControllerBase {
    private $model;
    private $userService;

    public function __construct( $reqInfo ) {
        parent::__construct( $reqInfo[0] );     // $reqInfo[0] is reqType
        try {
            $this->model = new \App\model\User;
        } catch( \Exception $e ) {
            echo $e->getMessage(), __LINE__,'<br>';
            return;
        } catch( \Error $er) {
            echo $er->getMessage(), __LINE__,'<br>';
            return;
        }

        try {
            $this->userService = new \App\service\User( $this->reqType );
        } catch( \Exception $e ) {
            echo $e->getMessage(), __LINE__,'<br>';
        } catch( \Error $er) {
            echo $er->getMessage(), __LINE__,'<br>';
        }
    }

    public function getAllUsers() {
        parent::AuthApi();

        $data = [];
        $this->model->getAllUsers( $data );     // $data passed as an OUT param
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        }


    public function getUser( $reqInfo ) {
        parent::AuthApi();

        if( $this->reqType == POST ) {
            $id = $_POST['docid'];
            $data = [];
            $this->model->getUserByID( $id, $data );
            echo json_encode( $data );
        }
    }

    
    public function addUser( $reqInfo ) {
        parent::AuthApi();

        if( $this->reqType == POST ) {
            $user = new \database\userEntity;

            $errors = [];
            $rslt = $this->userService->validate( $user, $errors );
            if( $rslt == false ) {
                http_response_code(500);
                echo json_encode( $errors );
                return;
            }
            $ret = $this->model->createUser( $user );
            echo json_encode( $ret );
        }
    }

    public function updateUser( $reqInfo ) {
        parent::AuthApi();
        
        if( $this->reqType == POST ) {
            $id = $_POST['docid'];

            $ret = $this->model->updateUserByID( $id );
            echo json_encode( $ret );
        }
    }

    public function deleteUserById( $reqInfo ) {
        parent::AuthApi();
        if( $this->reqType == POST ) {
            $id = $_POST['docid'];

            $rslt = $this->model->deleteUserByID( $id );
            echo json_encode( $rslt );
        }
    }

    public function login() {
        if( $this->reqType == POST ) {
            if (!empty($_POST) ) {
                $uname = trim($_POST['uname']);                     // TODO validate
                $pwd = trim($_POST['psw']);
            }

            $data = [];
            $this->model->getUserByName( $uname, $data );       // $data passed as an OUT param

            $rslt = password_verify( $pwd, $data['Password'] ); // compare passwords
            if( $rslt == false ) {
                $GLOBALS['error_data'] = 'Incorrect Login Data';
                require_once VIEWS . '404.php';
                header("HTTP/1.1 404 Not Found");
                echo '{}';
                return;
            }

            $payload = [        // JWT
                'iat' => time(),
                'exp' => time() + 60*60*4, // + 4 hours
                'role' => 'user',
                'ID' => $data['UserID'],
                'UserName' => $data['UserName']
            ];

            // send back jwt
            try {
                $jwt = JWT::encode($payload, $this->secretKey, 'HS256');
            } catch( \Exception $e ) {
                echo $e->getMessage(), __LINE__,'<br>';
                return false;
            } catch( \Error $er) {
                echo $er->getMessage(), __LINE__,'<br>';
                return false;
            }

            header( 'Content-Type: text/html; charset=UTF-8');
           echo $jwt;
        }
    }
}


