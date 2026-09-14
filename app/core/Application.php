<?php
namespace App\core;

$gAction;                           // set exactly ONCE per request. forced to be global by function call limitation

class Application {
    protected $controller;          // current controller object
    protected $controllerPath;      // file path to file
    // protected $action;           // = 'index';
    protected $params = [];


    public function __construct() {
        $GLOBALS['AppObj'] = $this;
    }


    public function run() {
        //try {
            $this->setReqMethod();
            $this->parseURL();
            $this->invoke();

        // } catch( \ErrorException $er ) {
        //     //$this->displayProblem( 'Error: ' . $er->getMessage(), $er->getFile(),$er->getLine() );
        //     $this->displayProblem( $er );
        // } catch( \Exception $e ) {
        //     //$this->displayProblem( 'Exception: ' . $e->getMessage(), $e->getFile(), $e->getLine() );
        //     $this->displayProblem( $e );
        // } catch( \Error $err ) {
        //      //$this->displayProblem( 'ERROR: ' . $err->getMessage(),  $err->getFile(),$err->getLine() );
        //      $this->displayProblem( $err );
        //  }
        //return true;
    }


    protected function setReqMethod() {
        // can add any number of other REST RM types like PUT, DELETE etc.
        $val = strtoupper( $_SERVER['REQUEST_METHOD'] );
        switch( $val ) {
            case 'GET':
                //$GLOBALS['AppObj']->_ReqType = GET;
                $GLOBALS['ReqType'] = GET;

                $this->params[0] = GET;
                break;
            case 'POST':
                //$GLOBALS['AppObj']->_ReqType = POST;
                $GLOBALS['ReqType'] = POST;
                    $this->params[0] = POST;
                break;
        }
    }


    protected function parseURL() {
        global $gAction;
        // controller and action paths and names are set

        $request = trim( $_SERVER['REQUEST_URI'], '/' );
        if( empty($request) ) {
            // by convention, if no querystring; ex: web-app.com uses default: home controller and index action
            $this->controllerPath = CONTROLLER . 'home.php';
            $this->controller = 'App\\controller\\home';
            // $this->action = 'index';
            $gAction = 'index';
            return;
        }

       $url = parse_url( $request );
       $urlPath = explode( '/', $url['path'] );
        // by convention, if only one element, it's a home page action - see views/sidebar
        if( count($urlPath) == 1 ) {
            $this->controllerPath = CONTROLLER . 'home.php';
            $this->controller = 'App\\controller\\home';
            // $this->action = $urlPath[0];
            $gAction = $urlPath[0];
            return;
        }

        // based upon index position, we know api, non-api / controller / action-page / params
        if( count($urlPath) > 1 ) {
            if( $urlPath[0] == 'api' ) {
                $this->controllerPath = API . $urlPath[1] . '.php'; // now current controller PATH
                $this->controller = "App\\api\\" . $urlPath[1];     // now current controller CLASS
                // $this->action = $urlPath[2];                     // method / function
                $gAction = $urlPath[2];
            } else {
                $this->controllerPath = CONTROLLER . $urlPath[0] . '.php';  // now current controller
                $this->controller = "App\\controller\\" . $urlPath[0];      // now current controller CLASS
                // $this->action = $urlPath[1];                             // method / function
                $gAction = $urlPath[1];
            }
        }
    }


    // Exceptions / Errors are now caught by global handlers in index.php
    protected function invoke() {
        global $gAction;
//        try {
            // auto class loader from file path, controller class gets instantiated and action / function invoked
            if( file_exists($this->controllerPath) ) {
                require_once $this->controllerPath;
                $this->controller = new $this->controller( $this->params );
            } else {
                $this->displayProblem( 'Error: Controller ' . $this->controller . ' Missing', __FILE__, __LINE__ );
                return false;
            }
    
            if( method_exists($this->controller, $gAction) ) {
                // invoke an instance method. below done the 'old' way, commented out
                // call_user_func_array( [$this->controller, $this->action], $this->params ); DO NOT become Emotionally invested in your code. This allows discussion and rapid change.
                // $gAction = $this->action; ugh... not on every request

                if ( $GLOBALS['IsHalted'] == false ) {
                    $this->controller->$gAction( $this->params );    // The MAGIC - extreamly fast
                }
                
            } else {
                $classObj = new \ReflectionClass( $this->controller );
                $this->displayProblem( 'Error: ' . $classObj->getName() . '\\' . $gAction . ' Function Missing',  __FILE__, __LINE__ );
                return false;
            }
        // } catch( \Exception $e ) {
        //     $IsHalted = true;
        //     $str = 'Exception: ' . $e->getMessage() . ' File: ' . __FILE__ . ' Line: ' . __LINE__;
        //     throw new Exception( $str );
        //     //$this->displayProblem( 'Exception: ' . $e->getMessage(), __FILE__, __LINE__ );
        //     return false;
        // } catch( \Error $er ) {
        //     //$str = 'Error: ' . $er->getMessage() . ' File: ' . __FILE__ . ' Line: ' . __LINE__;
        //     $IsHalted = true;
        //     throw new \ErrorException( $er->getMessage() );
        //     //$this->displayProblem( 'Error: ' . $er->getMessage(), __FILE__, __LINE__ );
        //     return false;
        // }
        return true;
    }


    public function displayProblem( $ex ) {
        require_once SERVICE . 'ErrorHandler.php';
        \App\service\showError( $ex->getMessage(), $ex->getFile(), $ex->getLine() );
        exit();
    }
}