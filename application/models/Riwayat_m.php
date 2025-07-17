<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Riwayat_m extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_riwayat_pengajuan($user_id = null)
    {
        $this->db->select('
        pengajuan.id_pengajuan, 
        user.nama, 
        klasifikasi_pengajuan.nama_klasifikasi, 
        pengajuan.no_pengajuan, 
        pengajuan.perihal, 
        pengajuan.tanggal_pengajuan, 
        pengajuan.status_pengajuan,
        MAX(disposisi.tanggal_disposisi) AS tanggal_status_update
    ');
        $this->db->from('pengajuan');
        $this->db->join('user', 'user.id_user = pengajuan.id_user');
        $this->db->join('klasifikasi_pengajuan', 'klasifikasi_pengajuan.id_klasifikasi = pengajuan.id_klasifikasi');
        $this->db->join('disposisi', 'disposisi.id_pengajuan = pengajuan.id_pengajuan', 'left');

        if ($user_id !== null) {
            $this->db->where('pengajuan.id_user', $user_id);
        }

        // Kecualikan status tertentu
        $this->db->where_not_in('pengajuan.status_pengajuan', ['direvisi', 'diproses']);

        // Penting: karena pakai MAX()
        $this->db->group_by('pengajuan.id_pengajuan');

        // Urut berdasarkan tanggal disposisi terakhir
        $this->db->order_by('tanggal_status_update', 'DESC');

        return $this->db->get()->result_array();
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

    public function get_pengajuan_detail($id_pengajuan)
    {
        $this->db->select('
        p.*,
        u.nama as user_nama,
        u.inisial,
        kp.nama_klasifikasi,
        p.status_pengajuan');

        $this->db->from('pengajuan p');
        $this->db->join('user u', 'p.id_user = u.id_user', 'left');
        $this->db->join('klasifikasi_pengajuan kp', 'p.id_klasifikasi = kp.id_klasifikasi', 'left');
        $this->db->where('p.id_pengajuan', $id_pengajuan);
        return $this->db->get()->row();
    }

    public function get_riwayat_disposisi($id_pengajuan)
    {
        $this->db->select('
        d.*,
        dari.nama AS dari_nama,
        dari.inisial AS dari_inisial,
        ke.nama_role AS ke_role,
        ke_user.nama AS ke_user_nama,
        p.nama_prodi AS ke_user_prodi,
        f.nama_fakultas AS ke_user_fakultas');
        $this->db->from('disposisi d');
        $this->db->join('user dari', 'd.dari_user = dari.id_user', 'left');
        $this->db->join('role ke', 'd.ke_role = ke.id_role', 'left');
        $this->db->join('user ke_user', 'ke_user.id_user = d.user_tujuan', 'left');

        // Subquery user_role tanpa id_user_role
        $this->db->join(
            '(SELECT id_user, id_role, id_prodi, id_fakultas 
          FROM user_role 
          GROUP BY id_user, id_role) ur',
            'ur.id_user = d.user_tujuan AND ur.id_role = d.ke_role',
            'left'
        );

        $this->db->join('prodi p', 'ur.id_prodi = p.id_prodi', 'left');
        $this->db->join('fakultas f', 'ur.id_fakultas = f.id_fakultas', 'left');

        $this->db->where('d.id_pengajuan', $id_pengajuan);
        $this->db->order_by('d.tanggal_disposisi', 'ASC');

        return $this->db->get()->result();
    }


    // Add your model methods here
}
