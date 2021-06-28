<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Model_mata_kuliah extends CI_Model {
    public function __construct(    ) {
        parent::__construct();
        $this->load->database();
    }

    public function get_by_subject($getSubject = 0)
    {
        $this->db->where('name',$getSubject);
        return $this->db->get('mata_kuliah')->row();
    }

    public function create_subject($name_subject, $createdate)
    {
       $subject = [
        'name' => $name_subject,
        'createdate' => $createdate
       ];
       
       $this->db->insert('mata_kuliah',$subject);
       return $this->db->affected_rows();
    }

    public function update_subject($id_subject, $name_subject, $updatedate, $updateby)
    {
        $subject_put = [
            'name'          => $name_subject,
            'updatedate'    => $updatedate,
            'updateby'      => $updateby
        ];

        $this->db->where('id',$id_subject);
        $this->db->update('mata_kuliah',$subject_put);
        return $this->db->affected_rows();
    }

    public function delete_subject($id_subject, $updatedate, $updateby)
    {
        $subject_delete = [
            'updatedate' => $updatedate,
            'updateby' => $updateby,
            'status' => 2
        ];
        $this->db->where('id', $id_subject);
        $this->db->update('mata_kuliah', $subject_delete);
        return $this->db->affected_rows();
    }
}