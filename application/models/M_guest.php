<?php
class M_guest extends CI_Model
{
    var $table = 'guests';
    var $column_order = ['id', 'name', 'email', 'nim', 'prodi', 'is_checked_in'];
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

    function save($data)
    {
        $this->db->insert($this->table, $data);
    }

    function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    function update($data, $id)
    {
        $this->db->update($this->table, $data, ['id' => $id]);
    }

    function delete_by_id($id)
    {
        $this->db->delete($this->table, ['id' => $id]);
    }

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    public function get_by_qr($qr_code)
    {
        return $this->db->get_where('guests', ['qr_code' => $qr_code])->row();
    }

    public function checkin($qr_code)
    {
        $this->db->where('qr_code', $qr_code);
        return $this->db->update('guests', [
            'is_checked_in' => 1,
            'is_checked_time' => date('Y-m-d H:i:s')
        ]);
    }

    public function manual_checkin($id)
    {
        return $this->db->update('guests', [
            'is_checked_in' => 1,
            'is_checked_time' => date('Y-m-d H:i:s')
        ], ['id' => $id]);
    }


    public function get_report($start_date = null, $end_date = null)
    {
        if ($start_date && $end_date) {
            $this->db->where('DATE(is_checked_time) >=', $start_date);
            $this->db->where('DATE(is_checked_time) <=', $end_date);
        }

        return $this->db->get('guests')->result();
    }
}
