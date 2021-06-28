<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use majooportal\Libraries\RestController;


/**
 * @property Model_schedule $Model_schedule
 * @property Token_parser $token_parser
 */

class schedule extends RestController {
    private $id;
    private $role;

    public function __construct(    ) {
        parent::__construct();
        $this->load->model('Model_schedule');
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
        $subject = $this->post('mata_kuliah_id');
        $day = $this->post('day');
        $time = $this->post('time');
        $createdate = date('Y-m-d H:i:s');
        $createby = $this->id;

        if ($this->role != 1 && $this->role != 3) {
            $this->response([
                "status"    => false,
                "message"  => "Anda Tidak Diperkenankan!"
            ],RestController::HTTP_BAD_REQUEST);       
        } 

        $data = $this->Model_schedule->create_schedule($subject, $day, $time, $createdate, $createby);

        $this->response([
            "status"    => true,
            "message"   => "Data Berhasil Ditambahkan!",
            "data" => $data   
        ],RestController::HTTP_CREATED);      
    }

    public function create_detail_post()
    {
        $student = $this->post('student_id');
        $id_schedule = $this->post('schedule_id');
        $createdate = date('Y-m-d H:i:s');
        $createby = $this->id;

        if ($this->role != 1 && $this->role != 3) {
            $this->response([
                "status"    => false,
                "message"  => "Anda Tidak Diperkenankan!"
            ],RestController::HTTP_BAD_REQUEST);       
        } 

        $data = $this->Model_schedule->create_detail_schedule($student, $id_schedule, $createdate, $createby);

        $this->response([
            "status"    => true,
            "message"   => "Data Berhasil Ditambahkan!",
            "data" => $data   
        ],RestController::HTTP_CREATED);    
    }

    
}