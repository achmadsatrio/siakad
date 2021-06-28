<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Model_score extends CI_Model {
    public function __construct(    ) {
        parent::__construct();
        $this->load->database();
    }

    public function create_score($score, $subject, $student, $createdate, $createby)
    {
        $score_data = [
            'score_value' => $score,
            'score_mata_kuliah_id' => $subject,
            'score_user_id' => $student,
            'createdate' => $createdate,
            'createby' => $createby
           ];
           
           $this->db->insert('score',$score_data);
           return $this->db->affected_rows();
    }

    public function update_score($id_score, $score, $subject, $student, $updatedate, $updateby)
    {
        $score_put = [
            'score_value'   => $score,
            'score_mata_kuliah_id' => $subject,
            'score_user_id' => $student,
            'updatedate'    => $updatedate,
            'updateby'      => $updateby
        ];

        $this->db->where('id',$id_score);
        $this->db->update('score',$score_put);
        return $this->db->affected_rows();
    }

    public function delete_score($id_score, $updatedate, $updateby)
    {
        // status 1 (ACTIVE), status 2 (INACTIVE)
        $score_delete = [
            'updatedate' => $updatedate,
            'updateby' => $updateby,
            'status' => 2
        ];
        $this->db->where('id', $id_score);
        $this->db->update('score', $score_delete);
        return $this->db->affected_rows();
    }

    public function getAll()
    {
        return $this->db->get('score')->result();   
    }

    public function get_by_id($id_student = 0)
    {
        if ($id_student) {
            $this->db->where('score_user_id', $id_student);
        }
        return $this->db->get('score')->result();
    }
}