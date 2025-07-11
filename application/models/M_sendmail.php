<?php
class M_sendmail extends CI_Model
{
    var $table = 'guests';
    var $column_order = ['id', 'name', 'email', 'nim', 'prodi', 'is_checked_time', 'is_checked_in', 'is_sendmail_time', 'is_sendmail_id'];
    var $column_search = ['name', 'email', 'nim', 'prodi'];
    var $order = ['id' => 'desc'];

    private function _get_datatables_query()
    {
        $this->db->from($this->table);
        $i = 0;
        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) {
                    $this->db->group_end();
                }
                $i++;
            }
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1) $this->db->limit($_POST['length'], $_POST['start']);
        return $this->db->get()->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->db->get()->num_rows();
    }

    function count_all()
    {
        return $this->db->count_all($this->table);
    }

    function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    public function get_by_qr($qr_code)
    {
        return $this->db->get_where('guests', ['qr_code' => $qr_code])->row();
    }

    public function upsend($email)
    {
        $this->db->where('email', $email);
        return $this->db->update('guests', [
            'is_sendmail_in' => 1,
            'is_sendmail_time' => date('Y-m-d H:i:s')
        ]);
    }
}
