<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_m extends CI_Model
{
    public function __construct()

    {
        parent::__construct();
    }

    // Add your model methods here
    public function get_all_users()
    {
        $this->db->select('u.id_user, u.username, u.nama, f.nama_fakultas, p.nama_prodi');
        $this->db->from('users u');
        $this->db->join('fakultas f', 'u.id_fakultas = f.id_fakultas', 'left');
        $this->db->join('prodi p', 'u.id_prodi = p.id_prodi', 'left');
        return $this->db->get()->result_array();
    }
    public function get_all_users_with_roles()
    {
        $this->db->select('u.*, r.nama_role, p.nama_prodi as kaprodi_prodi_name, f1.nama_fakultas as dosen_fakultas_name, f2.nama_fakultas as dekan_fakultas_name, d.id_prodi as dosen_prodi_id, k.id_prodi as kaprodi_prodi_id, dk.id_fakultas as dekan_fakultas_id');
        $this->db->from('user u');
        $this->db->join('user_role ur', 'u.id_user = ur.id_user', 'left');
        $this->db->join('role r', 'ur.id_role = r.id_role', 'left');
        $this->db->join('dosen d', 'u.id_user = d.id_user', 'left');
        $this->db->join('prodi p', 'd.id_prodi = p.id_prodi', 'left');
        $this->db->join('kaprodi k', 'u.id_user = k.id_user', 'left');
        $this->db->join('prodi p2', 'k.id_prodi = p2.id_prodi', 'left');
        $this->db->join('dekan dk', 'u.id_user = dk.id_user', 'left');
        $this->db->join('fakultas f1', 'p.id_fakultas = f1.id_fakultas', 'left');
        $this->db->join('fakultas f2', 'dk.id_fakultas = f2.id_fakultas', 'left');
        $this->db->group_by('u.id_user');
        $query = $this->db->get();
        $results = $query->result_array();

        foreach ($results as &$result) {
            $roles = $this->get_user_roles($result['id_user']);
            $role_names = array_column($roles, 'nama_role');
            $result['roles'] = implode(', ', $role_names);

            if (!empty($result['kaprodi_prodi_id'])) {
                $prodi = $this->get_prodi_by_id($result['kaprodi_prodi_id']);
                $result['nama_prodi'] = $prodi['nama_prodi'] ?? '-';
            } else {
                $result['nama_prodi'] = '-';
            }

            $result['nama_fakultas'] = '-';
            if (in_array('dosen', $role_names) && !empty($result['dosen_prodi_id'])) {
                $prodi = $this->get_prodi_by_id($result['dosen_prodi_id']);
                if ($prodi && isset($prodi['id_fakultas'])) {
                    $this->db->select('nama_fakultas');
                    $this->db->from('fakultas');
                    $this->db->where('id_fakultas', $prodi['id_fakultas']);
                    $query = $this->db->get();
                    $fakultas = $query->row_array();
                    $result['nama_fakultas'] = $fakultas['nama_fakultas'] ?? '-';
                }
            } elseif (in_array('dekan', $role_names) && !empty($result['dekan_fakultas_id'])) {
                $this->db->select('nama_fakultas');
                $this->db->from('fakultas');
                $this->db->where('id_fakultas', $result['dekan_fakultas_id']);
                $query = $this->db->get();
                $fakultas = $query->row_array();
                $result['nama_fakultas'] = $fakultas['nama_fakultas'] ?? '-';
            } elseif (in_array('kaprodi', $role_names) && !empty($result['kaprodi_prodi_id'])) {
                $prodi = $this->get_prodi_by_id($result['kaprodi_prodi_id']);
                if ($prodi && isset($prodi['id_fakultas'])) {
                    $this->db->select('nama_fakultas');
                    $this->db->from('fakultas');
                    $this->db->where('id_fakultas', $prodi['id_fakultas']);
                    $query = $this->db->get();
                    $fakultas = $query->row_array();
                    $result['nama_fakultas'] = $fakultas['nama_fakultas'] ?? '-';
                }
            }
        }

        return $results;
    }

    public function get_total_users()
    {
        return $this->db->count_all('user');
    }

    public function get_user_by_id($id_user)
    {
        $this->db->select('u.*, r.nama_role');
        $this->db->from('user u');
        $this->db->join('user_role ur', 'u.id_user = ur.id_user', 'left');
        $this->db->join('role r', 'ur.id_role = r.id_role', 'left');
        $this->db->where('u.id_user', $id_user);
        $query = $this->db->get();
        $user = $query->row_array();
        $user['roles'] = $this->get_user_roles($id_user);
        return $user;
    }

    public function get_user_by_id_with_prodi_fakultas($id_user)
    {
        $this->db->select('u.*, d.id_prodi as dosen_prodi_id, k.id_prodi as kaprodi_prodi_id, dk.id_fakultas as dekan_fakultas_id');
        $this->db->from('user u');
        $this->db->join('dosen d', 'u.id_user = d.id_user', 'left');
        $this->db->join('kaprodi k', 'u.id_user = k.id_user', 'left');
        $this->db->join('dekan dk', 'u.id_user = dk.id_user', 'left');
        $this->db->where('u.id_user', $id_user);
        $query = $this->db->get();
        $user = $query->row_array();

        if ($user) {
            $user['roles'] = $this->get_user_roles($id_user);
            $user['id_prodi'] = $user['kaprodi_prodi_id'] ?: $user['dosen_prodi_id'];
            $user['id_fakultas'] = $user['dekan_fakultas_id'];

            if ($user['id_prodi']) {
                $prodi = $this->get_prodi_by_id($user['id_prodi']);
                $user['nama_prodi'] = $prodi['nama_prodi'] ?? '-';
            } else {
                $user['nama_prodi'] = '-';
            }

            $role_names = array_column($user['roles'], 'nama_role');
            $user['nama_fakultas'] = '-';
            if (in_array('dosen', $role_names) && $user['dosen_prodi_id']) {
                $prodi = $this->get_prodi_by_id($user['dosen_prodi_id']);
                if ($prodi && isset($prodi['id_fakultas'])) {
                    $this->db->select('nama_fakultas');
                    $this->db->from('fakultas');
                    $this->db->where('id_fakultas', $prodi['id_fakultas']);
                    $query = $this->db->get();
                    $fakultas = $query->row_array();
                    $user['nama_fakultas'] = $fakultas['nama_fakultas'] ?? '-';
                }
            } elseif (in_array('dekan', $role_names) && $user['dekan_fakultas_id']) {
                $this->db->select('nama_fakultas');
                $this->db->from('fakultas');
                $this->db->where('id_fakultas', $user['dekan_fakultas_id']);
                $query = $this->db->get();
                $fakultas = $query->row_array();
                $user['nama_fakultas'] = $fakultas['nama_fakultas'] ?? '-';
            } elseif (in_array('kaprodi', $role_names) && $user['kaprodi_prodi_id']) {
                $prodi = $this->get_prodi_by_id($user['kaprodi_prodi_id']);
                if ($prodi && isset($prodi['id_fakultas'])) {
                    $this->db->select('nama_fakultas');
                    $this->db->from('fakultas');
                    $this->db->where('id_fakultas', $prodi['id_fakultas']);
                    $query = $this->db->get();
                    $fakultas = $query->row_array();
                    $user['nama_fakultas'] = $fakultas['nama_fakultas'] ?? '-';
                }
            }
        }

        return $user;
    }

    // untuk mengambil semua role user dengan aray
    public function get_user_roles($id_user)
    {
        $this->db->select('r.*');
        $this->db->from('user_role ur');
        $this->db->join('role r', 'ur.id_role = r.id_role');
        $this->db->where('ur.id_user', $id_user);
        $query = $this->db->get();
        return $query->result();
    }

    // untuk mengambil satu role user
    public function get_user_role($user_id)
    {
        return $this->db->select('r.id_role, r.nama_role, r.kode_role, ur.id_prodi, ur.id_fakultas')
            ->from('user_role ur')
            ->join('role r', 'ur.id_role = r.id_role')
            ->where('ur.id_user', $user_id)
            ->get()
            ->row();
    }

    public function get_all_roles()
    {
        $this->db->select('*');
        $this->db->from('role');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function add_user($data)
    {
        $this->db->insert('user', $data);
        return $this->db->insert_id();
    }

    public function update_user($id_user, $data)
    {
        $this->db->where('id_user', $id_user);
        $this->db->update('user', $data);
    }

    public function delete_user($id_user)
    {
        $this->db->where('id_user', $id_user);
        $this->db->delete('user');
    }

    public function remove_role_from_user($user_id, $role_id)
    {
        $this->db->where('id_user', $user_id);
        $this->db->where('id_role', $role_id);
        return $this->db->delete('user_role');
    }

    public function assign_dosen($user_id, $prodi_id)
    {
        $this->db->where('id_user', $user_id);
        $this->db->delete('dosen');
        if ($prodi_id) {
            $data = ['id_user' => $user_id, 'id_prodi' => $prodi_id];
            $this->db->insert('dosen', $data);
        }
    }

    public function assign_kaprodi($user_id, $prodi_id)
    {
        $this->db->where('id_user', $user_id);
        $this->db->delete('kaprodi');
        if ($prodi_id) {
            $data = ['id_user' => $user_id, 'id_prodi' => $prodi_id];
            $this->db->insert('kaprodi', $data);
        }
    }

    public function assign_dekan($user_id, $fakultas_id)
    {
        $this->db->where('id_user', $user_id);
        $this->db->delete('dekan');
        if ($fakultas_id) {
            $data = ['id_user' => $user_id, 'id_fakultas' => $fakultas_id];
            $this->db->insert('dekan', $data);
        }
    }


    public function get_all_prodis()
    {
        $this->db->select('id_prodi, nama_prodi, id_fakultas');
        $this->db->from('prodi');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_all_fakultas()
    {
        $this->db->select('id_fakultas, nama_fakultas');
        $this->db->from('fakultas');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_kaprodi_role_by_dosen($dosen_user_id)
    {
        // Ambil id_prodi dari dosen
        $dosen = $this->db->get_where('Dosen', ['id_user' => $dosen_user_id])->row();
        if (!$dosen) return null;

        $kaprodi = $this->db->select('ur.id_role')
            ->from('kaprodi k')
            ->join('user_role ur', 'ur.id_user = k.id_user')
            ->where('k.id_prodi', $dosen->id_prodi)
            ->get()
            ->row();
        return $kaprodi;
    }

    // model untuk ambil kode role
    public function get_role_by_kode_role($kode_role)
    {
        return $this->db->where('LOWER(kode_role)', strtolower($kode_role))
            ->get('role')
            ->row();
    }

    public function get_all_kaprodi_in_same_fakultas($id_fakultas)
    {
        return $this->db->select('u.id_user, u.nama, pr.nama_prodi')
            ->from('user_role ur')
            ->join('user u', 'u.id_user = ur.id_user')
            ->join('role r', 'r.id_role = ur.id_role')
            ->join('prodi pr', 'pr.id_prodi = ur.id_prodi')
            ->where('pr.id_fakultas', $id_fakultas)
            ->where('LOWER(r.kode_role)', 'kaprodi') // pastikan lowercase
            ->get()
            ->result_array();
    }

    public function get_all_non_admin_users()
    {
        return $this->db->select('u.id_user, u.nama, r.nama_role, r.kode_role')
            ->from('user u')
            ->join('user_role ur', 'ur.id_user = u.id_user')
            ->join('role r', 'r.id_role = ur.id_role')
            ->where('LOWER(r.kode_role) !=', 'adm')
            ->get()->result_array();
    }

    public function get_users_by_kode_role($kode_role)
    {
        return $this->db->select('u.id_user, u.nama')
            ->from('user_role ur')
            ->join('user u', 'u.id_user = ur.id_user')
            ->join('role r', 'r.id_role = ur.id_role')
            ->where('LOWER(r.kode_role)', strtolower($kode_role))
            ->get()->result_array();
    }
}
