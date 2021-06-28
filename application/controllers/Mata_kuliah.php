<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use majooportal\Libraries\RestController;


/**
 * @property Model_mata_kuliah $Model_mata_kuliah
 * @property Model_user $Model_user
 * @property Token_parser $token_parser
 */

class mata_kuliah extends RestController {
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

    public function update_put()
    {  
        $id_subject = $this->put('id');
        $name_subject = $this->put('name');
        $updatedate = date('Y-m-d H:i:s');
        $updateby = $this->id;
        $role = $this->role;

        $data_put = $this->Model_mata_kuliah->update_subject($id_subject, $name_subject, $updatedate, $updateby);

        $response = [
            "status"    => false,
            "message"   => "Gagal Ubah Data"
        ];
        $response_code = RestController::HTTP_BAD_REQUEST;

        if ($role == 1 && $data_put) {
            $response = [
                "status"    => true,
                "message"   => "Berhasil Ubah Data"
            ];
            $response_code = RestController::HTTP_CREATED;
        }

        $this->response($response, $response_code);

    }

    public function delete_put()
    {
        $id_subject = $this->put('id');
        $updatedate = date('Y-m-d H:i:s');
        $updateby = $this->id;
        
        $data_delete = $this->Model_mata_kuliah->delete_subject($id_subject, $updatedate, $updateby);

        $response = [
            "status"    => false,
            "message"   => "Gagal Menghapus Data"
        ];
        $response_code = RestController::HTTP_BAD_REQUEST;

        if ($this->role == 1 && $data_delete) {
            $response = [
                "status"    => true,
                "message"   => "Berhasil Menghapus Data"
            ];
            $response_code = RestController::HTTP_CREATED;
        }

        $this->response($response, $response_code);   
    }


}