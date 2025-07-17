<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Arsip extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Arsip_m');
        $this->load->helper('url');
        $this->load->model('User_m');

        if (!class_exists('Dompdf\Dompdf')) {
            $this->load->library('Dompdf_lib');
        }
        // Load models, libraries, etc. if needed
    }

    public function index()
    {
        $data['roles'] = $this->User_m->get_user_roles($this->session->userdata('id_user'));
        $data['active_role'] = $this->session->userdata('active_role');
        $data['nama'] = $this->session->userdata('name');
        $data['title'] = 'Arsip Pengajuan';


        // Ambil filter dari input
        $dari = $this->input->post('dari');
        $sampai = $this->input->post('sampai');
        $status = $this->input->post('status');

        $data['dari'] = $dari;
        $data['sampai'] = $sampai;
        $data['status'] = $status;

        // Ambil data berdasarkan filter
        $data['arsip'] = $this->Arsip_m->get_pengajuan_arsip_filtered($dari, $sampai, $status);
        $data['content_view'] = 'user/arsip/index.php';

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_user', $data);
        $this->load->view('template/footer', $data);
    }

    public function detail_arsip($id)
    {
        $data['title'] = 'Detail Arsip Pengajuan';
        $data['nama'] = $this->session->userdata('nama');
        $data['roles'] = $this->User_m->get_user_roles($this->session->userdata('id_user'));
        $data['active_role'] = $this->session->userdata('active_role');
        $data['nama'] = $this->session->userdata('name');
        // Ambil data utama + lampiran + disposisi
        $data['arsip'] = $this->Arsip_m->get_detail_pengajuan_by_id($id);

        // Tambahkan URL file disposisi jika ada
        $file_path = FCPATH . 'uploads/disposisi/disposisi_' . $id . '.pdf';
        if (file_exists($file_path)) {
            $data['file_disposisi'] = base_url('uploads/disposisi/disposisi_' . $id . '.pdf');
        } else {
            $data['file_disposisi'] = null;
        }

        $data['content_view'] = 'user/arsip/detail_arsip.php';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_user', $data);
        $this->load->view('template/footer', $data);
    }

    public function unduh_arsip($id_pengajuan)
    {
        // Ambil data utama
        $data['row'] = $this->Arsip_m->get_pengajuan_detail($id_pengajuan);

        if (empty($data['row'])) {
            show_404();
            return;
        }

        // Hitung jumlah revisi
        $this->db->from('disposisi');
        $this->db->where('id_pengajuan', $id_pengajuan);
        $this->db->where('status_disposisi', 'revisi');
        $jumlah_revisi = $this->db->count_all_results();


        $data['jumlah_revisi'] = $jumlah_revisi;
        $data['riwayat_disposisi'] = $this->Arsip_m->get_riwayat_disposisi($id_pengajuan);

        // Render view HTML
        $html = $this->load->view('user/arsip/pdf_arsip', $data, true);

        // Generate PDF
        if (class_exists('Dompdf\Dompdf')) {
            $dompdf = new Dompdf\Dompdf();
            $options = new Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            $dompdf->setOptions($options);

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream("Lembar_Kendali_Disposisi_{$id_pengajuan}.pdf", ["Attachment" => false]);
        } else {
            $this->dompdf_lib->createPdf(
                $html,
                "Lembar_Kendali_Disposisi_{$id_pengajuan}.pdf",
                'A4',
                'portrait',
                true
            );
        }
        exit();
    }
}
