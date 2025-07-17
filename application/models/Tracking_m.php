<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tracking_m extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Ambil semua data pengajuan
     * @return CI_DB_result
     */
    public function get_data()
    {
        return $this->db->get('pengajuan');
    }

    /**
     * Simpan data ke tabel manapun
     * @param string $table
     * @param array  $data
     * @return bool
     */
    public function simpan($table, array $data)
    {
        return $this->db->insert($table, $data);
    }

    /**
     * Hapus satu pengajuan berdasarkan id
     * @param int $id_pengajuan
     * @return bool
     */
    public function delete_pengajuan($id_pengajuan)
    {
        return $this->db
            ->where('id_pengajuan', $id_pengajuan)
            ->delete('pengajuan');
    }

    /**
     * Update data pada tabel manapun
     * @param array  $where  kondisi ['kolom' => 'nilai']
     * @param array  $data   data yang akan di‐update
     * @param string $table
     * @return bool
     */
    public function update($where, array $data, $table)
    {
        return $this->db
            ->where($where)
            ->update($table, $data);
    }

    /**
     * Ambil satu baris dari tabel manapun (misal untuk edit)
     * @param array  $where  kondisi ['kolom' => 'nilai']
     * @param string $table
     * @return CI_DB_result
     */
    public function get_where($where, $table)
    {
        return $this->db->get_where($table, $where);
    }

    /**
     * Ambil riwayat tracking untuk satu surat (pengajuan)
     * @param int $id_pengajuan
     * @return array
     */
    public function get_tracking($id_pengajuan)
    {
        $this->db->select(
            'p.id_pengajuan,
             p.no_pengajuan,
             p.perihal,
             p.tanggal_pengajuan,
             p.status_pengajuan,
    
             k.id_klasifikasi,
             k.kode_klasifikasi,
             k.nama_klasifikasi,
    
             d.id_disposisi,
             d.dari_user,
             du.nama AS dari_user,
             d.ke_role,
             ku.nama AS ke_user,
             r.id_role,
             r.nama_role,
             d.tanggal_disposisi,
             d.status_disposisi,
             d.catatan'
        );

        $this->db->from('pengajuan p');
        $this->db->join('klasifikasi_pengajuan k', 'p.id_klasifikasi = k.id_klasifikasi', 'left');
        $this->db->join('disposisi d',         'd.id_pengajuan = p.id_pengajuan', 'left');
        $this->db->join('user du',             'du.id_user = d.dari_user', 'left');
        $this->db->join('user ku',             'ku.id_user = d.ke_role',   'left');
        $this->db->join('user_role ur',        'ur.id_user = d.ke_role', 'left'); // role untuk ke_unit
        $this->db->join('role r',              'r.id_role = ur.id_role', 'left'); // ambil nama role ke_unit

        $this->db->where('p.id_pengajuan', $id_pengajuan);
        $this->db->order_by('d.tanggal_disposisi', 'ASC');

        return $this->db->get()->result();
    }



    /**
     * Ambil satu pengajuan berdasarkan id
     * @param int $id_pengajuan
     * @return object|null
     */
    // Surat_model.php
    public function get_single_pengajuan($id_user)
    {
        return $this->db
            ->where('id_user', $id_user)
            ->get('pengajuan')
            ->result();
    }

    public function get_pengajuan_by_id($id_pengajuan)
    {
        $this->db->select('pengajuan.*, klasifikasi_pengajuan.nama_klasifikasi, disposisi.status_disposisi, disposisi.catatan');
        $this->db->from('pengajuan');
        $this->db->join('klasifikasi_pengajuan', 'klasifikasi_pengajuan.id_klasifikasi = pengajuan.id_klasifikasi', 'left');
        $this->db->join('disposisi', 'disposisi.id_pengajuan = pengajuan.id_pengajuan', 'left');
        $this->db->where('pengajuan.id_pengajuan', $id_pengajuan);
        $this->db->order_by('disposisi.id_disposisi', 'DESC'); // ambil yang terakhir
        $this->db->limit(1); // hanya 1 baris (yang paling akhir)
        return $this->db->get()->row();
    }
    public function get_disposisi_by_pengajuan($id_pengajuan)
    {
        $this->db->from('pengajuan p');
        $this->db->join('klasifikasi_surat k', 'p.id_klasifikasi_surat = k.id_klasifikasi_surat', 'left');
        $this->db->join('disposisi d',         'd.id_pengajuan = p.id_pengajuan', 'left');
        $this->db->join('user du',             'du.id_user = d.dari_user', 'left');
        $this->db->join('user ku',             'ku.id_user = d.ke_unit',   'left');
        $this->db->join('user_role ur',        'ur.id_user = d.ke_unit', 'left'); // role untuk ke_unit
        $this->db->join('role r',              'r.id_role = ur.id_role', 'left'); // ambil nama role ke_unit
        $this->db->join('notifikasi n',        'n.id_pengajuan = p.id_pengajuan', 'left');

        $this->db->where('p.id_pengajuan', $id_pengajuan);
        $this->db->order_by('d.tanggal_disposisi', 'ASC');
        $this->db->order_by('n.waktu_tanggal', 'ASC');

        return $this->db->get()->result();
    }

    public function get_pengajuan_by_user_and_role($id_user, $role_pengaju)
    {
        return $this->db->get_where('pengajuan', [
            'id_user' => $id_user,
            'role_pengaju' => $role_pengaju
        ])->result();
    }
}
