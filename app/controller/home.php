<?php
namespace App\controller;
require_once CORE . 'ControllerBase.php';

// CONTROLLER
class Home extends \App\core\ControllerBase {

    public function __construct( $reqInfo ) {
        parent::__construct( $reqInfo[0] ); // $reqInfo[0] is reqType
    }

    public function index() {
        require_once VIEWS . 'head_begin.php';
        require_once VIEWS . 'top_content.php';
        require_once VIEWS . 'tinySidebar.php';
        require_once VIEWS . 'pOne.php';
        require_once VIEWS . 'footer.php';
    }

    public function indexAuth() {
        parent::AuthUI();
        require_once VIEWS . 'head_begin.php';
        require_once VIEWS . 'top_content.php';
        require_once VIEWS . 'sidebar.php';
        require_once VIEWS . 'pOne.php';
        require_once VIEWS . 'footer.php';
    }

    public function login( $reqInfo ) {
        include_once VIEWS . 'head_begin.php';
        include_once VIEWS . 'top_content.php';
        include_once VIEWS . 'tinySidebar.php';
        include_once VIEWS . 'login.php';
        include_once VIEWS . 'footer.php';        
    }

    public function new_user() {
        require_once VIEWS . 'head_begin.php';
        require_once VIEWS . 'top_content.php';
        require_once VIEWS . 'new_user.php';
        require_once VIEWS . 'footer.php';
    }

    public function pagetwo( $reqInfo ) {
        parent::AuthUI();
        include_once VIEWS . 'head_begin.php';
        include_once VIEWS . 'top_content.php';
        include_once VIEWS . 'sidebar.php';
        include_once VIEWS . 'pagetwo.php';
        include_once VIEWS . 'footer.php';
     }

    public function pagethree() {
        parent::AuthUI();
        include_once VIEWS . 'head_begin.php';
        include_once VIEWS . 'top_content.php';
        include_once VIEWS . 'sidebar.php';
        include_once VIEWS . 'pagethree.php';
        include_once VIEWS . 'footer.php';
    }

    public function pagefour() {
        parent::AuthUI();
        include_once VIEWS . 'head_begin.php';
        include_once VIEWS . 'top_content.php';
        include_once VIEWS . 'sidebar.php';
        include_once VIEWS . 'pagefour.php';
        include_once VIEWS . 'footer.php';
    }

    public function pagefive() {
        parent::AuthUI();
        include_once VIEWS . 'head_begin.php';
        include_once VIEWS . 'top_content.php';
        include_once VIEWS . 'sidebar.php';
        include_once VIEWS . 'pagefive.php';
        include_once VIEWS . 'footer.php';
    }

    public function pagesix() {
        parent::AuthUI();
        include_once VIEWS . 'head_begin.php';
        include_once VIEWS . 'top_content.php';
        include_once VIEWS . 'sidebar.php';
        include_once VIEWS . 'pagesix.php';
        include_once VIEWS . 'footer.php';
    }

    public function pagenine() {
        parent::AuthUI();
        include_once VIEWS . 'head_begin.php';
        include_once VIEWS . 'top_content.php';
        include_once VIEWS . 'sidebar.php';
        include_once VIEWS . 'pagenine.php';
        include_once VIEWS . 'footer.php';
    }

    public function page_sdlc() {
        parent::AuthUI();
        include_once VIEWS . 'head_begin.php';
        include_once VIEWS . 'top_content.php';
        include_once VIEWS . 'sidebar.php';
        include_once VIEWS . 'page_sdlc.php';
        include_once VIEWS . 'footer.php';
    }

    public function glossy2() {
        $_SERVER['PATH_INFO'] = GLOSSY;
        $_SERVER['PHP_SELF'] = GLOSSY;
        include_once GLOSSY . 'index.html';
    }

    public function pageseven() { // high-tech news
        include_once VIEWS . 'head_begin.php';
        include_once VIEWS . 'top_content.php';
        include_once VIEWS . 'sidebar.php';
        include_once VIEWS . 'pageseven.php';
        include_once VIEWS . 'footer.php';
    }

    public function contact() {
        include_once VIEWS . 'head_begin.php';
        include_once VIEWS . 'top_content.php';
        include_once VIEWS . 'sidebar.php';
        include_once VIEWS . 'contact.php';
        include_once VIEWS . 'footer.php';        
    }

    public function userlist() {
        parent::AuthUI();
        require_once VIEWS . 'head_begin.php';
        //require_once VIEWS . 'top_content.php';
        //require_once VIEWS . 'sidebar.php';
        require_once VIEWS . 'userList.php';
        //require_once VIEWS . 'footer.php';
    }
}
