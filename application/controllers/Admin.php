<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use majooportal\Libraries\RestController;



/**
 * @property Model_user $Model_user
 * @property Token_parser $token_parser
 */
class Admin extends RestController {
    public function __construct(    ) {
        parent::__construct();
        $this->load->model('Model_user');
        $this->load->library('Token_parser');
    }
	
    public function login_post()
    {
        $user_login = $this->post('username');
        $pass_login = md5($this->post('password'));

        $post_login = $this->Model_user->login_post_m($user_login, $pass_login);

        if ($post_login){
            $token = $this->token_parser->generate($post_login);
            
            $post_login->token = $token; 

            $response = [
                "status"    => true,
                "message"   => "Login Berhasil",
                "data"      => $post_login,
            ];
            
            $this->response($response);
        }

        $this->response([
            "status"    => false,
            "message"   => "Login Gagal",
            "data"      => $post_login              
        ], RestController::HTTP_BAD_REQUEST);             
    }

    public function create_user_post()
    {
        $user_name = $this->post('username');
        $password = md5($this->post('password'));
        $nama = $this->post('name');
        $role = $this->post('role');
        $createdate = date('Y-m-d H:i:s');

        $getUserExist = $this->Model_user->get_by_user($user_name);
        
        if ($getUserExist) {
            $this->response([
                "status"    => false,
                "message"  => "Data sudah digunakan!"
            ],RestController::HTTP_BAD_REQUEST);       
        }   

        $data = $this->Model_user->create_user($user_name, $password, $nama, $role, $createdate);
        
        $this->response([
            "status"    => true,
            "message"   => "Berhasil!",
        ],RestController::HTTP_CREATED);        
    }
}
