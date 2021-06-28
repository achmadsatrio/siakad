<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Model_schedule extends CI_Model {
    public function __construct(    ) {
        parent::__construct();
        $this->load->database();
    }

    public function create_schedule($subject, $day, $time, $createdate, $createby)
    {
       $schedule = [
        'mata_kuliah_id' => $subject,
        'day' => $day,
        'time' => $time,
        'createdate' => $createdate,
        'createby' => $createby
       ];
       
       $this->db->insert('schedule',$schedule);
       return $this->db->insert_id();
    }

    public function create_detail_schedule($student, $id_schedule, $createdate, $createby)
    {
        $schedule_detail = [
            'student_id' => $student,
            'schedule_id' => $id_schedule,
            'createdate' => $createdate,
            'createby' => $createby
           ];
           
           $this->db->insert('schedule_has_student',$schedule_detail);
           return $this->db->insert_id();
    }
}