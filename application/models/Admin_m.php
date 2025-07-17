<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_m extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // =================== USER ===================
    public function get_all_users_with_roles()
    {
        $this->db->select('u.*, r.nama_role, ur.id_prodi, ur.id_fakultas, p.nama_prodi, f.nama_fakultas');
        $this->db->from('user u');
        $this->db->join('user_role ur', 'u.id_user = ur.id_user', 'left');
        $this->db->join('role r', 'ur.id_role = r.id_role', 'left');
        $this->db->join('prodi p', 'ur.id_prodi = p.id_prodi', 'left');
        $this->db->join('fakultas f', 'ur.id_fakultas = f.id_fakultas', 'left');
        $this->db->group_by('u.id_user');
        $this->db->order_by('u.id_user', 'DESC');
        $query = $this->db->get();
        $results = $query->result_array();

        foreach ($results as &$result) {
            $roles = $this->get_user_roles($result['id_user']);
            $role_names = array_column($roles, 'nama_role');
            $result['roles'] = implode(', ', $role_names);
            $result['nama_prodi'] = $result['nama_prodi'] ?? '-';
            $result['nama_fakultas'] = $result['nama_fakultas'] ?? '-';
        }
        return $results;
    }

    public function get_user_by_id_with_prodi_fakultas($id_user)
    {
        $this->db->select('u.*, GROUP_CONCAT(DISTINCT r.nama_role) AS roles, p.nama_prodi, f.nama_fakultas');
        $this->db->from('user u');
        $this->db->join('user_role ur', 'u.id_user = ur.id_user', 'left');
        $this->db->join('role r', 'ur.id_role = r.id_role', 'left');
        $this->db->join('prodi p', 'ur.id_prodi = p.id_prodi', 'left');
        $this->db->join('fakultas f', 'ur.id_fakultas = f.id_fakultas', 'left');
        $this->db->where('u.id_user', $id_user);
        $this->db->group_by('u.id_user');
        return $this->db->get()->row_array();
    }

    public function add_user($data)
    {
        $this->db->insert('user', $data);
        return $this->db->insert_id();
    }

    public function update_user($id_user, $data)
    {
        $this->db->where('id_user', $id_user);
        return $this->db->update('user', $data);
    }

    public function delete_user($id_user)
    {
        $this->db->where('id_user', $id_user);
        $this->db->delete('user');
        $this->db->where('id_user', $id_user);
        return $this->db->delete('user_role');
    }

    // =================== ROLE ===================
    public function get_all_roles()
    {
        $this->db->order_by('id_role', 'DESC');
        return $this->db->get('role')->result_array();
    }

    public function get_role_by_id($id_role)
    {
        return $this->db->get_where('role', ['id_role' => $id_role])->row_array();
    }

    public function get_role_by_name($kode_role = null, $nama_role = null)
    {
        if ($kode_role !== null) {
            $this->db->where('kode_role', $kode_role);
        }

        if ($nama_role !== null) {
            $this->db->or_where('nama_role', $nama_role);
        }

        return $this->db->get('role')->row_array();
    }

    public function add_role($data)
    {
        return $this->db->insert('role', $data);
    }

    public function update_role($id_role, $data)
    {
        $this->db->where('id_role', $id_role);
        return $this->db->update('role', $data);
    }

    public function delete_role($id_role)
    {
        $this->db->where('id_role', $id_role);
        return $this->db->delete('role');
    }

    // =================== USER_ROLE ===================
    public function get_user_roles($id_user)
    {
        $this->db->select('r.*');
        $this->db->from('user_role ur');
        $this->db->join('role r', 'ur.id_role = r.id_role');
        $this->db->where('ur.id_user', $id_user);
        return $this->db->get()->result_array();
    }

    public function add_user_role_with_detail($id_user, $id_role, $id_prodi = null, $id_fakultas = null)
    {
        return $this->db->insert('user_role', [
            'id_user' => $id_user,
            'id_role' => $id_role,
            'id_prodi' => $id_prodi,
            'id_fakultas' => $id_fakultas
        ]);
    }

    public function remove_role_from_user($user_id, $role_id)
    {
        $this->db->where('id_user', $user_id);
        $this->db->where('id_role', $role_id);
        return $this->db->delete('user_role');
    }

    public function add_role_to_user($id_user, $id_role, $id_prodi = null, $id_fakultas = null)
    {
        return $this->db->insert('user_role', [
            'id_user' => $id_user,
            'id_role' => $id_role,
            'id_prodi' => $id_prodi,
            'id_fakultas' => $id_fakultas
        ]);
    }

    // =================== PRODI ===================
    public function get_all_prodis()
    {
        $this->db->order_by('id_prodi', 'DESC');
        return $this->db->get('prodi')->result_array();
    }

    public function get_prodi_by_id($id_prodi)
    {
        return $this->db->get_where('prodi', ['id_prodi' => $id_prodi])->row_array();
    }

    public function add_prodi($data)
    {
        return $this->db->insert('prodi', $data);
    }

    public function update_prodi($id_prodi, $data)
    {
        $this->db->where('id_prodi', $id_prodi);
        return $this->db->update('prodi', $data);
    }

    public function delete_prodi($id_prodi)
    {
        $this->db->where('id_prodi', $id_prodi);
        return $this->db->delete('prodi');
    }

    public function check_prodi_exists($nama_prodi, $id_fakultas)
    {
        $this->db->where('nama_prodi', $nama_prodi);
        $this->db->where('id_fakultas', $id_fakultas);
        $query = $this->db->get('prodi');
        return $query->num_rows() > 0;
    }

    // =================== FAKULTAS ===================
    public function get_all_fakultas()
    {
        $this->db->order_by('id_fakultas', 'DESC');
        return $this->db->get('fakultas')->result_array();
    }

    public function get_fakultas_by_id($id)
    {
        return $this->db->get_where('fakultas', ['id_fakultas' => $id])->row_array();
    }

    public function add_fakultas($data)
    {
        return $this->db->insert('fakultas', $data);
    }

    public function update_fakultas($id, $data)
    {
        $this->db->where('id_fakultas', $id);
        return $this->db->update('fakultas', $data);
    }

    public function delete_fakultas($id)
    {
        $this->db->where('id_fakultas', $id);
        return $this->db->delete('fakultas');
    }

    public function check_fakultas_exists($nama_fakultas, $id = null)
    {
        $this->db->where('nama_fakultas', $nama_fakultas);
        if ($id) {
            $this->db->where('id_fakultas !=', $id);
        }
        return $this->db->get('fakultas')->row_array();
    }

    // =================== UNIT PENGAJU ===================
    public function get_all_unit_pengaju()
    {
        $this->db->order_by('id_unit', 'DESC');
        return $this->db->get('unit_pengaju')->result_array();
    }

    public function get_unit_pengaju_by_id($id_unit)
    {
        return $this->db->get_where('unit_pengaju', ['id_unit' => $id_unit])->row_array();
    }

    public function add_unit_pengaju($data)
    {
        return $this->db->insert('unit_pengaju', $data);
    }

    public function update_unit_pengaju($id_unit, $data)
    {
        $this->db->where('id_unit', $id_unit);
        return $this->db->update('unit_pengaju', $data);
    }

    public function delete_unit_pengaju($id_unit)
    {
        $this->db->where('id_unit', $id_unit);
        return $this->db->delete('unit_pengaju');
    }

    // =================== KLASIFIKASI PENGAJUAN ===================
    public function get_all_klasifikasi_pengajuan()
    {
        $this->db->select('kp.*, up.nama_unit');
        $this->db->from('klasifikasi_pengajuan kp');
        $this->db->join('unit_pengaju up', 'kp.id_unit = up.id_unit', 'left');
        $this->db->order_by('kp.id_klasifikasi', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_klasifikasi_pengajuan_by_id($id_klasifikasi)
    {
        return $this->db->get_where('klasifikasi_pengajuan', ['id_klasifikasi' => $id_klasifikasi])->row_array();
    }

    public function get_klasifikasi_pengajuan_by_kode($kode_klasifikasi)
    {
        return $this->db->get_where('klasifikasi_pengajuan', ['kode_klasifikasi' => $kode_klasifikasi])->row_array();
    }

    public function add_klasifikasi_pengajuan($data)
    {
        return $this->db->insert('klasifikasi_pengajuan', $data);
    }

    public function update_klasifikasi_pengajuan($id_klasifikasi, $data)
    {
        $this->db->where('id_klasifikasi', $id_klasifikasi);
        return $this->db->update('klasifikasi_pengajuan', $data);
    }

    public function delete_klasifikasi_pengajuan($id_klasifikasi)
    {
        $this->db->where('id_klasifikasi', $id_klasifikasi);
        return $this->db->delete('klasifikasi_pengajuan');
    }

    public function get_klasifikasi_pengajuan_by_unit($id_unit)
    {
        $this->db->select('kp.*, up.nama_unit');
        $this->db->from('klasifikasi_pengajuan kp');
        $this->db->join('unit_pengaju up', 'kp.id_unit = up.id_unit', 'left');
        $this->db->where('kp.id_unit', $id_unit);
        return $this->db->get()->result_array();
    }

    public function get_total_pending_pengajuan()
    {
        return $this->db->where('status_pengajuan', 'Menunggu')->count_all_results('pengajuan');
    }

    public function get_pengajuan_per_bulan()
    {
        $this->db->select("DATE_FORMAT(tanggal_pengajuan, '%M') AS bulan, COUNT(*) AS total");
        $this->db->group_by("bulan");
        $this->db->order_by("MONTH(tanggal_pengajuan)");
        $query = $this->db->get('pengajuan');
        return $query->result_array();
    }

    // ========================= DUPLICATE CHECK =========================

    public function is_duplicate($field, $value)
    {
        $this->db->where($field, $value);
        return $this->db->get('user')->num_rows() > 0;
    }

    public function is_duplicate_username($username, $exclude_id)
    {
        $this->db->where('username', $username);
        $this->db->where('id_user !=', $exclude_id);
        return $this->db->get('user')->num_rows() > 0;
    }

    public function is_duplicate_nik($nik, $exclude_id)
    {
        $this->db->where('nik', $nik);
        $this->db->where('id_user !=', $exclude_id);
        return $this->db->get('user')->num_rows() > 0;
    }

    public function is_duplicate_email($email, $exclude_id)
    {
        $this->db->where('email', $email);
        $this->db->where('id_user !=', $exclude_id);
        return $this->db->get('user')->num_rows() > 0;
    }

    public function is_duplicate_inisial($inisial, $exclude_id)
    {
        $this->db->where('inisial', $inisial);
        $this->db->where('id_user !=', $exclude_id);
        return $this->db->get('user')->num_rows() > 0;
    }

    // =================== DASHBOARD COUNT ===================
    public function get_total_users()
    {
        return $this->db->count_all('user');
    }

    public function get_total_roles()
    {
        return $this->db->count_all('role');
    }

    public function get_total_klasifikasi()
    {
        return $this->db->count_all('klasifikasi_pengajuan');
    }

    public function get_total_unit_pengaju()
    {
        return $this->db->count_all('unit_pengaju');
    }

    public function get_total_pengguna_peran()
    {
        $this->db->select('COUNT(DISTINCT id_user) as total');
        return $this->db->get('user_role')->row()->total;
    }

    public function get_pengajuan_terbaru()
    {
        $this->db->select('p.no_pengajuan, p.status_pengajuan, p.tanggal_pengajuan, 
                           kp.nama_klasifikasi, up.nama_unit, u.nama as nama_pengaju');
        $this->db->from('pengajuan p');
        $this->db->join('klasifikasi_pengajuan kp', 'p.id_klasifikasi = kp.id_klasifikasi');
        $this->db->join('unit_pengaju up', 'kp.id_unit = up.id_unit');
        $this->db->join('user u', 'p.id_user = u.id_user');
        $this->db->order_by('p.tanggal_pengajuan', 'DESC');
        $this->db->limit(10);
        return $this->db->get()->result_array();
    }
    public function get_pengajuan_per_hari_bulan_ini()
{
    $this->db->select("DATE_FORMAT(tanggal_pengajuan, '%d') AS hari, COUNT(*) AS total");
    $this->db->from('pengajuan');
    $this->db->where('MONTH(tanggal_pengajuan)', date('m'));
    $this->db->where('YEAR(tanggal_pengajuan)', date('Y'));
    $this->db->group_by("hari");
    $this->db->order_by("hari", "ASC");
    return $this->db->get()->result_array();
}
public function get_pengajuan_filtered($bulan = null, $tahun = null)
{
    $this->db->select('p.no_pengajuan, p.status_pengajuan, p.tanggal_pengajuan, 
                       kp.nama_klasifikasi, up.nama_unit, u.nama as nama_pengaju');
    $this->db->from('pengajuan p');
    $this->db->join('klasifikasi_pengajuan kp', 'p.id_klasifikasi = kp.id_klasifikasi');
    $this->db->join('unit_pengaju up', 'kp.id_unit = up.id_unit');
    $this->db->join('user u', 'p.id_user = u.id_user');

    if ($bulan) {
        $this->db->where('MONTH(p.tanggal_pengajuan)', $bulan);
    }
    if ($tahun) {
        $this->db->where('YEAR(p.tanggal_pengajuan)', $tahun);
    }

    $this->db->order_by('p.tanggal_pengajuan', 'DESC');
    return $this->db->get()->result_array();
}


}
