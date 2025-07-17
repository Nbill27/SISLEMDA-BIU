<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Disposisi_m extends CI_Model
{
    public function getDisposisiMasuk()
    {
        $id_role_aktif = $this->session->userdata('active_role_id');
        if (!$id_role_aktif) return [];

        return $this->db->select('pengajuan.id_pengajuan,
                              pengajuan.no_pengajuan,
                              pengajuan.perihal,
                              user.nama AS nama_pengaju')
            ->from('disposisi')
            ->join('pengajuan', 'pengajuan.id_pengajuan = disposisi.id_pengajuan')
            ->join('user', 'user.id_user = disposisi.dari_user')
            ->where('disposisi.ke_role', $id_role_aktif)
            ->where_not_in('disposisi.status_disposisi', ['ditolak'])
            ->order_by('disposisi.tanggal_disposisi', 'DESC')
            ->get()->result_array();
    }



    public function get_detail_pengajuan_by_id($id)
    {
        if (!is_numeric($id)) {
            return null;
        }

        // Detail utama pengajuan
        $this->db->select('pengajuan.*, 
              user.nama as nama_user, 
              klasifikasi_pengajuan.kode_klasifikasi, 
              klasifikasi_pengajuan.nama_klasifikasi');
        $this->db->from('pengajuan');
        $this->db->join('user', 'pengajuan.id_user = user.id_user', 'left');
        $this->db->join('klasifikasi_pengajuan', 'klasifikasi_pengajuan.id_klasifikasi = pengajuan.id_klasifikasi', 'left');
        $this->db->where('pengajuan.id_pengajuan', $id);
        $pengajuan = $this->db->get()->row();

        if (!$pengajuan) return null;

        // Lampiran
        $this->db->from('lampiran');
        $this->db->where('id_pengajuan', $id);
        $pengajuan->lampiran = $this->db->get()->result();

        // Disposisi
        $this->db->select('d.*, 
              pengirim.nama AS nama_pengirim, 
              pengirim.inisial AS inisial_pengirim,
              penerima.nama AS nama_tujuan,
              r.nama_role AS role_tujuan,
              prodi.nama_prodi,
              fakultas.nama_fakultas');
        $this->db->from('disposisi d');
        $this->db->join('user AS pengirim', 'pengirim.id_user = d.dari_user', 'left');

        // role tujuan (kaprodi/dekan/tujuan lain)
        $this->db->join('role r', 'r.id_role = d.ke_role', 'left');

        // user tujuan dari kolom user_tujuan
        $this->db->join('user AS penerima', 'penerima.id_user = d.user_tujuan', 'left');

        // ambil prodi & fakultas user_tujuan
        $this->db->join('user_role ur', 'ur.id_user = d.user_tujuan AND ur.id_role = d.ke_role', 'left');
        $this->db->join('prodi', 'prodi.id_prodi = ur.id_prodi', 'left');
        $this->db->join('fakultas', 'fakultas.id_fakultas = ur.id_fakultas', 'left');

        $this->db->where('d.id_pengajuan', $id);
        $this->db->order_by('d.urutan', 'ASC');
        $pengajuan->disposisi = $this->db->get()->result();

        return $pengajuan;
    }


    public function getAllRoles()
    {
        return $this->db
            ->where('kode_role !=', 'adm')
            ->get('role')
            ->result_array();
    }

    //ambil roles berdasarkan kode
    public function get_roles_by_kode($kode_roles)
    {
        if (empty($kode_roles)) return [];

        return $this->db->select('u.id_user, u.nama, r.kode_role')
            ->from('user u')
            ->join('user_role ur', 'ur.id_user = u.id_user')
            ->join('role r', 'r.id_role = ur.id_role')
            ->where_in('r.kode_role', $kode_roles)
            ->get()->result_array();
    }



    public function getUnitByUserId($id_user)
    {
        $role = $this->db->select('id_role')
            ->from('user_role')
            ->where('id_user', $id_user)
            ->get()->row();

        return $role ? $role->id_role : null;
    }



    // ambil dekan 1 fakultas
    public function get_dekan_satu_fakultas($id_user)
    {
        $prodi = $this->db->select('id_prodi')
            ->from('user_role')
            ->where('id_user', $id_user)
            ->where_not_in('id_prodi', [null])
            ->get()->row();

        if (!$prodi) return [];

        $fakultas = $this->db->select('id_fakultas')
            ->from('prodi')
            ->where('id_prodi', $prodi->id_prodi)
            ->get()->row();

        if (!$fakultas) return [];

        return $this->db->select('u.id_user, u.nama, r.kode_role')
            ->from('user u')
            ->join('user_role ur', 'ur.id_user = u.id_user')
            ->join('role r', 'r.id_role = ur.id_role')
            ->where('r.kode_role', 'dekan')
            ->where('ur.id_fakultas', $fakultas->id_fakultas)
            ->get()->result_array();
    }

    // ambil kaprodi 1 fakultas
    public function get_kaprodi_satu_fakultas($id_user)
    {
        $fakultas = $this->db->select('id_fakultas')
            ->from('user_role')
            ->where('id_user', $id_user)
            ->where_not_in('id_fakultas', [null])
            ->get()->row();

        if (!$fakultas) return [];

        // Ambil prodi-prodi di fakultas tersebut
        $prodis = $this->db->select('id_prodi')
            ->from('prodi')
            ->where('id_fakultas', $fakultas->id_fakultas)
            ->get()->result();

        $id_prodis = array_column($prodis, 'id_prodi');

        return $this->db->select('u.id_user, u.nama, r.kode_role')
            ->from('user u')
            ->join('user_role ur', 'ur.id_user = u.id_user')
            ->join('role r', 'r.id_role = ur.id_role')
            ->where('r.kode_role', 'kaprodi')
            ->where_in('ur.id_prodi', $id_prodis)
            ->get()->result_array();
    }

    // ambil dosen 1 prodi
    public function get_dosen_satu_prodi($id_user)
    {
        $prodi = $this->db->select('id_prodi')
            ->from('user_role')
            ->where('id_user', $id_user)
            ->where_not_in('id_prodi', [null])
            ->get()->row();

        if (!$prodi) return [];

        return $this->db->select('u.id_user, u.nama, r.kode_role')
            ->from('user u')
            ->join('user_role ur', 'ur.id_user = u.id_user')
            ->join('role r', 'r.id_role = ur.id_role')
            ->where('r.kode_role', 'dosen')
            ->where('ur.id_prodi', $prodi->id_prodi)
            ->get()->result_array();
    }

    // mapping role
    private function getRoleTargetMapping()
    {
        return [
            'kaprodi' => ['dosen', 'dekan'],
            'dekan' => ['kaprodi', 'wr1'],
            'sijaring' => ['wr1', 'lab'],
            'perpes' => ['wr1', 'prcsistem', 'pgnsistem'],
            'baa' => ['wr1', 'pddikti', 'admperk'],
            'penpkm' => ['wr1', 'peneluar', 'pkmluar'],
            'sdm' => ['wr2', 'ktnaga', 'pgdosen'],
            'keuangan' => ['wr2', 'pkuang', 'anggaran', 'akuntansi'],
            'opsumum' => ['wr2', 'ops', 'umum'],
            'kemkons' => ['wr3', 'kemhas', 'konseling'],
            'markerja' => ['wr3', 'dmarketing', 'admisipmb', 'humas'],
            'dmarketing' => ['markerja', 'danalist', 'editor', 'kreator'],
            'bic' => ['wr3', 'magang', 'tracer'],
            'kwu' => ['wr3', 'inkubator', 'startup'],
            'wr1' => ['rektor', 'perpusinov', 'sijaring', 'perpes', 'dekan', 'baa', 'penpkm', 'bahasa'],
            'wr2' => ['rektor', 'sdm', 'keuangan', 'opsumum'],
            'wr3' => ['rektor', 'kemkons', 'markerja', 'bic', 'kwu'],
            'ppm' => ['rektor', 'monev', 'doklapor', 'spme'],
            'rektor' => ['yys', 'wr1', 'wr2', 'wr3', 'ppm'],
            'yys' => ['semua']
        ];
    }

    public function getAvailableTargetRoles($activeRoleCode, $id_user)
    {
        $mapping = $this->getRoleTargetMapping();
        $targets = $mapping[$activeRoleCode] ?? [];

        // Jika bisa akses semua
        if (in_array('semua', $targets)) {
            // Tambahkan struktur data seragam meskipun dari tabel role langsung
            $roles = $this->db->get('role')->result_array();
            foreach ($roles as &$r) {
                $r['nama_prodi'] = null;
                $r['nama_fakultas'] = null;
            }
            return $roles;
        }

        // Ambil data user aktif (utk filter prodi/fakultas)
        $user_role_data = $this->db->select('ur.*, r.kode_role')
            ->from('user_role ur')
            ->join('role r', 'r.id_role = ur.id_role')
            ->where('ur.id_user', $id_user)
            ->where('r.kode_role', $activeRoleCode)
            ->get()->row_array();

        if (!$user_role_data) {
            log_message('error', 'User role data not found for user ' . $id_user . ' and role ' . $activeRoleCode);
            return [];
        }

        $filtered_roles = [];

        foreach ($targets as $target_code) {
            if (in_array($target_code, ['dosen', 'kaprodi', 'dekan'])) {
                // ambil dari user_role
                $query = $this->db->select('ur.id_role, r.nama_role, r.kode_role, u.nama, ur.id_user, p.nama_prodi, f.nama_fakultas')
                    ->from('user_role ur')
                    ->join('user u', 'u.id_user = ur.id_user')
                    ->join('role r', 'r.id_role = ur.id_role')
                    ->join('prodi p', 'p.id_prodi = ur.id_prodi', 'left')
                    ->join('fakultas f', 'f.id_fakultas = ur.id_fakultas', 'left')
                    ->where('r.kode_role', $target_code);

                // Filter berdasar prodi atau fakultas user aktif
                if ($target_code === 'dosen' && !empty($user_role_data['id_prodi'])) {
                    $query->where('ur.id_prodi', $user_role_data['id_prodi']);
                } elseif (in_array($target_code, ['kaprodi', 'dekan']) && !empty($user_role_data['id_fakultas'])) {
                    $query->where('ur.id_fakultas', $user_role_data['id_fakultas']);
                }

                $result = $query->get()->result_array();
                log_message('debug', 'Target khusus (' . $target_code . '): ' . json_encode($result));
                $filtered_roles = array_merge($filtered_roles, $result);
            } else {
                // Ambil langsung dari role
                $role = $this->db->select('id_role, nama_role, kode_role')
                    ->from('role')
                    ->where('kode_role', $target_code)
                    ->get()->row_array();

                if ($role) {
                    // Buat struktur data konsisten
                    $role['nama'] = null;
                    $role['id_user'] = null;
                    $role['nama_prodi'] = null;
                    $role['nama_fakultas'] = null;
                    $filtered_roles[] = $role;
                }
            }
        }
        log_message('debug', 'Hasil akhir getAvailableTargetRoles: ' . json_encode($filtered_roles));

        return $filtered_roles;
    }





    public function insert($data)
    {
        return $this->db->insert('disposisi', $data);
    }

    public function getNextUrutan($id_pengajuan)
    {
        $urutan = $this->db->select_max('urutan')
            ->get_where('disposisi', ['id_pengajuan' => $id_pengajuan])
            ->row()->urutan;
        return $urutan ? $urutan + 1 : 1;
    }

    public function kirim_disposisi()
    {
        $id_pengajuan = $this->input->post('id_pengajuan');
        $catatan = $this->input->post('catatan');
        $status_input = $this->input->post('status_disposisi');
        $id_user = $this->session->userdata('id_user');
        $ke_role = $this->input->post('ke_role');
        $tanggal = date('Y-m-d H:i:s');
        $urutan = $this->getNextUrutan($id_pengajuan);

        // Tentukan status_disposisi untuk DB
        switch ($status_input) {
            case 'ditolak':
                $status_db = 'ditolak';
                break;
            case 'revisi':
                $status_db = 'revisi';
                break;
            case 'lanjut':
                $status_db = 'diproses';
                break;
            case 'setuju':
                $status_db = 'setuju';
                break;
            default:
                $status_db = 'diproses';
                break;
        }

        // Jika revisi atau ditolak, cari ke_role dari user pengaju
        if (in_array($status_input, ['revisi', 'ditolak'])) {
            $pengajuan = $this->getPengajuanById($id_pengajuan);
            $user_pengaju = $pengajuan['id_user'];
            $ke_role = $this->getUnitByUserId($user_pengaju); // Sekarang fungsi ini udah ada
        }

        // Siapkan data untuk insert
        $data = [
            'id_pengajuan' => $id_pengajuan,
            'dari_user' => $id_user,
            'ke_role' => $ke_role,
            'tanggal_disposisi' => $tanggal,
            'catatan' => $catatan,
            'status_disposisi' => $status_db,
            'urutan' => $urutan
        ];

        // Insert ke DB
        $this->insert($data);

        // Update status pengajuan
        $this->db->update('pengajuan', ['status_pengajuan' => $status_db], ['id_pengajuan' => $id_pengajuan]);

        // Flash message dan redirect
        $this->session->set_flashdata('success', 'Disposisi berhasil dikirim.');
        redirect('users/disposisi/detail/' . $id_pengajuan);
    }

    public function getNikUrutanPertama($id_pengajuan)
    {
        $this->db->select('u.nik, u.nama, u.inisial');
        $this->db->from('disposisi d');
        $this->db->join('user u', 'u.id_user = d.dari_user');
        $this->db->where('d.id_pengajuan', $id_pengajuan);
        $this->db->where('d.urutan', 1);
        $query = $this->db->get();
        return $query->row_array();
    }
}
