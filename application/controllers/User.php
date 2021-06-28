<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use majooportal\Libraries\RestController;



/**
 * @property Model_user $Model_user
 * @property Token_parser $token_parser
 */
class User extends RestController {
    private $id;
    private $role;

    public function __construct(    ) {
        parent::__construct();
        $this->load->model('Model_user');
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
	
    public function all_user_get()
    {
        
        $data = $this->Model_user->getAll();
        $this->response($data);

    }

    public function spesific_user_get()
    {
        $getUser = $this->get('username');
        
        $data = $this->Model_user->get_by_user($getUser);
        $this->response($data);
    }

    public function update_post()
    {   
        $id = $this->post('id');
        $user_name_put = $this->post('username');
        $password_put = md5($this->post('password'));
        $nama_put = $this->post('name');
        $photo = $_FILES['photo'];
        $image_url = "";

        if ($photo) {
            $config['upload_path']          = './uploads/';
            //for overwrite file
            $config['overwrite']            = false;
            $config['allowed_types']        = 'gif|jpg|png|jpeg';
            $config['max_size']             = 0;
            //$config['max_width']            = 640;
            //$config['max_height']           = 640;
            $config['file_name']            = $photo['name'];

            $this->load->library('upload', $config);
            $upload = $this->upload->do_upload('photo'); 

            if ($upload) {
                $image_url = base_url().'upload/'.$photo['name'];
            }
        }

        $data_put = $this->Model_user->update_user($id, $user_name_put,$password_put,$nama_put,$image_url);

        $response_put = [
            "status"    => false,
            "message"   => "Gagal Ubah Data"
        ];
        $response_code = RestController::HTTP_BAD_REQUEST;

        if ($data_put) {
            $response_put = [
                "status"    => true,
                "message"   => "Berhasil Ubah Data",
            ];
            $response_code = RestController::HTTP_CREATED;
        }      

        $this->response($response_put, $response_code);
    
    }

    public function delete_user_put()
    {
        $id = $this->put('id');
        $updatedate = date('Y-m-d H:i:s');
        $updateby = $this->id;
        $role = $this->role;

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

        $data_delete = $this->Model_user->delete_user($id, $updatedate, $updateby);

        if ($data_delete) {
            $response = [
                "status"    => true,
                "message"   => "Berhasil Menghapus Data",
                "query" => $this->db->queries,
            ];
            $response_code = RestController::HTTP_CREATED;
        }

        $this->response($response, $response_code); 
    }

}
