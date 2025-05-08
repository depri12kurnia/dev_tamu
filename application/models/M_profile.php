<?php
class M_profile extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }

    public function get_by_id($id)
    {
        $this->db->from('users');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function update_photo($user_id, $photo)
    {
        $this->db->where('id', $user_id);
        $this->db->update('users', array('photo' => $photo));
    }
}
