<?php
class User_model extends CI_Model
{

    public function verify_user($username, $password)
    {
        // Query ke database untuk memverifikasi user
        $query = $this->db->get_where('users', ['username' => $username]);

        if ($query->num_rows() > 0) {
            $user = $query->row_array();

            // Misal menggunakan hash password
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }

        return false;
    }
}
