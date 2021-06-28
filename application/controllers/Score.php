<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use majooportal\Libraries\RestController;


/**
 * @property Model_score $Model_score
 * @property Token_parser $token_parser
 */

class score extends RestController {
    private $id;
    private $role;

    public function __construct(    ) {
        parent::__construct();
        $this->load->model('Model_score');
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
    
    public function insert_post()
    {
        $score = $this->post("score_value");
        $subject = $this->post("score_mata_kuliah_id");
        $student = $this->post("score_user_id");
        $createdate = date('Y-m-d H:i:s');
        $createby = $this->id;

        //print_r($this->role);exit;
        if ($this->role != 1 && $this->role != 3) {
           $this->response([
               "status"    => false,
               "message"  => "Anda Tidak Diperkenankan!"
           ],RestController::HTTP_BAD_REQUEST);       
        }   

        $data = $this->Model_score->create_score($score, $subject, $student, $createdate, $createby);

        $this->response([
            "status"    => true,
            "message"   => "Data Berhasil Ditambahkan!"   
        ],RestController::HTTP_CREATED);      
    }

    public function update_put()
    {
        $id_score = $this->put("id");
        $score = $this->put("score_value");
        $subject = $this->put("score_mata_kuliah_id");
        $student = $this->put("score_user_id");
        $updatedate = date('Y-m-d H:i:s');
        $updateby = $this->id;
        $role = $this->role;

        if ($role != 1) {
            $response = [
                "status"    => false,
                "message"   => "Anda Tidak Diperkenankan!"
            ];
            $response_code = RestController::HTTP_BAD_REQUEST;
            $this->response($response, $response_code);
        }

        $response = [
            "status"    => false,
            "message"   => "Gagal Ubah Data!"
        ];
        $response_code = RestController::HTTP_BAD_REQUEST;

        $data_put = $this->Model_score->update_score($id_score, $score, $subject, $student, $updatedate, $updateby);

        if ($data_put) {
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
        $id_score = $this->put('id');
        $updatedate = date('Y-m-d H:i:s');
        $updateby = $this->id;
        
        if ($this->role != 1) {
            $response = [
                "status"    => false,
                "message"   => "Anda Tidak Diperkenankan!"
            ];
            $response_code = RestController::HTTP_BAD_REQUEST;
            $this->response($response, $response_code);
        }

        $response = [
            "status"    => false,
            "message"   => "Gagal Menghapus Data"
        ];
        $response_code = RestController::HTTP_BAD_REQUEST;

        $data_delete = $this->Model_score->delete_score($id_score, $updatedate, $updateby);

        if ($data_delete) {
            $response = [
                "status"    => true,
                "message"   => "Berhasil Menghapus Data"
            ];
            $response_code = RestController::HTTP_CREATED;
        }

        $this->response($response, $response_code);   
    }

    public function view_get()
    {
        $id = $this->get('id_user');
        
        if ($this->role != 2 && $this->role != 4) {
            $response = [
                "status"    => false,
                "message"   => "Anda Tidak Diperkenankan!"
            ];
            $response_code = RestController::HTTP_BAD_REQUEST;
            $this->response($response, $response_code);
        }
        
        if ($this->role == 4) {
            $id = $this->id;
            $data = $this->Model_score->get_by_id($id);
            $response = [
                "status"    => true,
                "message"   => "Berhasil Mendapatkan Data!",
                "data" => empty($data) ? null : $data
            ];
            $response_code = RestController::HTTP_OK;
            $this->response($response, $response_code);
        }
        
        if ($this->role == 2) {
            $data = $this->Model_score->get_by_id($id);
            $response = [
                "status"    => true,
                "message"   => "Berhasil Mendapatkan Data!",
                "data" => empty($data) ? null : $data
            ];
            $response_code = RestController::HTTP_OK;
            $this->response($response, $response_code);
        }
    }
}