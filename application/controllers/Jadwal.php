<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use majooportal\Libraries\RestController;



/**
 * @property Model_jadawl $Model_jadwal
 * @property Token_parser $token_parser
 */

 class jadwal extends RestController {
    private $id;
    private $role;

    public function __construct(    ) {
        parent::__construct();
        $this->load->model('Model_mata_kuliah');
        $this->load->library('Token_parser');

        $header = $this->input->request_headers();
        if (!isset($header['token'])) {
            $response = [
                "status"    => false,
                "message"   => "token kosong"
            ];
            $this->response($response);
        }

        $token = $header['token'];
        $tokenDecode = $this->token_parser->decode($token);
        $kadaluarsa = $tokenDecode->kadaluarsa;
        $this->id = $tokenDecode->id;
        $this->role = $tokenDecode->role;
        $date = date('Y-m-d H:i:s');
        $dateKadaluarsa = date('Y-m-d H:i:s', $kadaluarsa);
        
        if ($dateKadaluarsa < $date) {
            $response = [
                "status"    => false,
                "message"   => "invalid token"
            ];
            $this->response($response);
        }
    }

    public function create_post()
    {
        $name_subject = $this->post("name");
        $createdate = date('Y-m-d H:i:s');

        $getSubjectExist = $this->Model_mata_kuliah->get_by_subject($name_subject);
        
        if ($getSubjectExist) {
            $this->response([
                "status"    => false,
                "message"  => "Data sudah digunakan!"
            ],RestController::HTTP_BAD_REQUEST);       
        }   

        $data = $this->Model_mata_kuliah->create_subject($name_subject, $createdate);

        $this->response([
            "status"    => true,
            "message"   => "Data Berhasil Ditambahkan!"   
        ],RestController::HTTP_CREATED);            
    }

 }