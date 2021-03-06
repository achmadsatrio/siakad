<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Model_user extends CI_Model {
    public function __construct(    ) {
        parent::__construct();
        $this->load->database();
    }

    public function getAll()
    {
        return $this->db->get('user')->result(); 
    }

    public function create_user($user_name, $password, $nama, $role, $createdate)
    {
        $user = [
            'username' => $user_name,
            'password' => $password,
            'name' => $nama,
            'role' => $role,
            'createdate' => $createdate
        ];

        $this->db->insert('user',$user);
        return $this->db->affected_rows();
    }

    public function get_by_user($id)
    {
        $this->db->where('id',$id);
        return $this->db->get('user')->row();
    }

    public function update_user($id, $user_name_put, $password_put, $nama_put, $image_url)
    {
        $user_put = [
            'username'     => $user_name_put,
            'password'      => $password_put,
            'name'          => $nama_put,
            'image_url' => $image_url
        ];

        $this->db->where('id',$id);
        $this->db->update('user',$user_put);
        return $this->db->affected_rows();
    }

    public function delete_user($id, $updatedate, $updateby)
    {
        // status 1 (ACTIVE), status 2 (INACTIVE)
        $score_delete = [
            'updatedate' => $updatedate,
            'updateby' => $updateby,
            'status' => 2
        ];
        $this->db->where('id', $id);
        $this->db->update('user', $score_delete);
        return $this->db->affected_rows();
    }

    public function login_post_m($user_name_login,$password_login)
    {
        $this->db->where('username',$user_name_login);
        $this->db->where('password',$password_login);

        return $this->db->get('user')->row();
    }
}
