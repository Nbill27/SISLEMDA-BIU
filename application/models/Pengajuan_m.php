<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengajuan_m extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_klasifikasi()
    {
        $query = $this->db->get('klasifikasi_pengajuan');
        return $query->result_array();
    }

    public function get_all_unitpengajuan()
    {
        $query = $this->db->get('unit_pengaju');
        return $query->result_array();
    }

    public function insert_pengajuan($data)
    {
        $this->db->insert('pengajuan', $data);
        return $this->db->insert_id();
    }

    public function insert_lampiran($data = [])
    {
        $this->db->insert('lampiran', $data);
        return $this->db->insert_id();
    }

    public function generate_nomor_pengajuan()
    {
        $tahun = date('Y');

        // Ambil semua nomor pengajuan yang sudah ada di tahun ini
        $this->db->select("no_pengajuan");
        $this->db->from("pengajuan");
        $this->db->where("YEAR(tanggal_pengajuan)", $tahun);
        $query = $this->db->get();

        // Kumpulkan semua nomor urut dalam array
        $used_numbers = [];
        foreach ($query->result() as $row) {
            $parts = explode('/', $row->no_pengajuan);
            if (isset($parts[0]) && is_numeric($parts[0])) {
                $used_numbers[] = (int)$parts[0];
            }
        }

        // Cari nomor terkecil yang belum digunakan mulai dari 1
        $new_number = 1;
        while (in_array($new_number, $used_numbers)) {
            $new_number++;
        }

        // Format nomor dengan padding 3 digit + tahun
        $formatted_number = str_pad($new_number, 4, '0', STR_PAD_LEFT) . '/' . $tahun;

        return $formatted_number;
    }

    public function get_prodi()
    {
        $this->db->select('id_prodi, nama_prodi');
        $this->db->from('prodi');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_pengajuan_by_id($id)
    {
        return $this->db->get_where('pengajuan', ['id_pengajuan' => $id])->row();
    }

    public function get_lampiran_by_pengajuan($id)
    {
        return $this->db->get_where('lampiran', ['id_pengajuan' => $id])->result();
    }

    public function insert_disposisi($data)
    {
        $this->db->insert('disposisi', $data);
    }
}
