<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LembarPengajuan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pengajuan_m');
        $this->load->model('User_m');
        $this->load->helper('url');
        // Load necessary models, libraries, or helpers here
    }


    public function index()
    {

        $data = [];
        $data['all_roles'] = $this->db->get('role')->result_array();
        $data['current_role'] = strtolower($this->User_m->get_user_role($this->session->userdata('id_user'))->kode_role);
        $data['title'] = 'Lembar Pengajuan';
        $data['active_role'] = $this->session->userdata('active_role');
        $data['roles'] = $this->User_m->get_user_roles($this->session->userdata('id_user'));
        $data['nama'] = $this->session->userdata('name');
        $data['klasifikasi'] = $this->Pengajuan_m->get_all_klasifikasi();
        $data['unitpengajuan'] = $this->Pengajuan_m->get_all_unitpengajuan();
        $data['no_pengajuan'] = $this->Pengajuan_m->generate_nomor_pengajuan(); // Generate nomor surat

        $current_user = $this->session->userdata('id_user');
        $user_role = $this->User_m->get_user_role($current_user);

        if (strtolower($user_role->kode_role) === 'dekan') {
            $kaprodi_satu_fakultas = $this->User_m->get_all_kaprodi_in_same_fakultas($user_role->id_fakultas);
        } else {
            $kaprodi_satu_fakultas = [];
        }
        $data['kaprodi_satu_fakultas'] = $kaprodi_satu_fakultas;

        $all_users = $this->User_m->get_all_non_admin_users();
        $data['all_users'] = $all_users;

        // Ambil role user dari session atau query
        $id_user = $this->session->userdata('id_user');
        $role_obj = $this->User_m->get_user_role($id_user); // Harus return objek role
        $data['role_pengaju'] = $role_obj ? $role_obj->id_role : '';

        $data['content_view'] = 'user/lembar_pengajuan/lembar_pengajuan';


        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_user', $data);
        $this->load->view('template/footer', $data);
    }

    public function get_users_by_role()
    {
        $role = strtolower($this->input->post('role'));
        $users = [];

        switch ($role) {
            case 'kaprodi':
                $users = $this->User_m->get_users_by_kode_role('kaprodi');
                break;
            case 'dosen':
                $users = $this->User_m->get_users_by_kode_role('dosen');
                break;
            case 'dekan':
                $users = $this->User_m->get_users_by_kode_role('dekan');
                break;
        }
        echo json_encode($users);
    }

    public function insert()
    {
        $this->load->model('Pengajuan_m');

        $max_retry = 5;
        $retry = 0;
        $inserted = false;

        // Ambil semua data dari form
        $id_user        = $this->input->post('user_id');
        $role_pengaju   = $this->input->post('role_pengaju');
        $id_klasifikasi = $this->input->post('kode');
        $perihal        = $this->input->post('perihal');
        $tanggal        = $this->input->post('tanggal');

        // untuk insert ke tabel pengajuan
        while (!$inserted && $retry < $max_retry) {
            $no_pengajuan = $this->Pengajuan_m->generate_nomor_pengajuan();

            $data = [
                'id_user'           => $id_user,
                'role_pengaju'      => $role_pengaju,
                'id_klasifikasi'    => $id_klasifikasi,
                'perihal'           => $perihal,
                'tanggal_pengajuan' => $tanggal,
                'status_pengajuan'  => 'Diproses',
                'no_pengajuan'      => $no_pengajuan
            ];

            try {
                $this->db->insert('pengajuan', $data);
                $inserted = true;
                //  Ambil ID dari insert yang sukses
                $pengajuan_id = $this->db->insert_id();
            } catch (Exception $e) {
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    $retry++;
                } else {
                    throw $e;
                }
            }
        }

        if (!$inserted) {
            show_error("Gagal menyimpan data pengajuan setelah {$max_retry} percobaan.");
        }

        // Simpan dokumen
        $this->load->library('upload');
        $files = $_FILES['dokumen'];
        $file_count = count($files['name']);

        for ($i = 0; $i < $file_count; $i++) {
            if (!empty($files['name'][$i])) {
                $_FILES['single_dokumen']['name']     = $files['name'][$i];
                $_FILES['single_dokumen']['type']     = $files['type'][$i];
                $_FILES['single_dokumen']['tmp_name'] = $files['tmp_name'][$i];
                $_FILES['single_dokumen']['error']    = $files['error'][$i];
                $_FILES['single_dokumen']['size']     = $files['size'][$i];

                // Ambil nama asli dan pisahkan nama & ekstensi
                $original_name = $files['name'][$i];
                $ext = pathinfo($original_name, PATHINFO_EXTENSION);
                $name = pathinfo($original_name, PATHINFO_FILENAME);

                // Bersihkan nama dari karakter aneh
                $safe_name = preg_replace('/[^A-Za-z0-9_\-]/', '_', $name);

                // Tambahkan timestamp di akhir (sebelum ekstensi)
                $final_name = $safe_name . '_' . date('YmdHis') . '.' . $ext;


                $config['upload_path']   = './assets/uploads/';
                $config['allowed_types'] = 'pdf|doc|docx|jpg|jpeg|png';
                $config['file_name']     = $final_name;
                $config['overwrite']     = false;
                $config['encrypt_name']  = false;

                $this->upload->initialize($config);

                if ($this->upload->do_upload('single_dokumen')) {
                    $data_upload = $this->upload->data();
                    $lampiran = [
                        'id_pengajuan' => $pengajuan_id,
                        'nama_file'    => $data_upload['client_name'],
                        'file_path' => $data_upload['file_name']
                    ];
                    $this->Pengajuan_m->insert_lampiran($lampiran);
                }
            }
        }

        // Cek apakah ada input link
        $link = $this->input->post('link_dokumen');
        if (!empty($link)) {
            $lampiran = [
                'id_pengajuan' => $pengajuan_id,
                'nama_file' => null,
                'file_path' => $link,
                'tipe' => 'link'
            ];
            $this->Pengajuan_m->insert_lampiran($lampiran);
        }

        $catatan = $this->input->post('disposisi');
        $dari_user_id = $this->input->post('user_id');
        $tanggal = $this->input->post('tanggal');
        $role_tujuan = $this->input->post('tujuan');


        // Ambil role pengirim
        $user_role = $this->User_m->get_user_role($dari_user_id); // return object { id_role, nama_role }
        $role_name = strtolower($user_role->kode_role);
        $tujuan_roles = null;
        // $tujuan_user = null;

        // Tentukan tujuan berdasarkan role
        switch ($role_name) {
            case 'dosen':
                $kaprodi = $this->db->select('ur.id_user, ur.id_role')
                    ->from('user_role ur')
                    ->join('role r', 'r.id_role = ur.id_role')
                    ->where('(r.kode_role)', 'kaprodi')
                    ->where('ur.id_prodi', $user_role->id_prodi)
                    ->get()->row();
                if ($kaprodi) {
                    // $tujuan_user = $kaprodi->id_user;
                    $tujuan_roles = [$kaprodi->id_role];
                }
                break;

            case 'dekan':
                if (!empty($role_tujuan)) {
                    $tujuan_roles[] = (int)$role_tujuan;
                    $user_tujuan = $this->input->post('user_tujuan') ?: null;

                    // Validasi jika tujuan adalah kaprodi tapi user_tujuan belum dipilih
                    if (strtolower($this->User_m->get_user_role($role_tujuan)->kode_role) === 'kaprodi' && !$user_tujuan) {
                        $this->session->set_flashdata('error', 'Silakan pilih nama kaprodi tujuan.');
                        redirect('users/lembarpengajuan');
                        return;
                    }
                }
                break;

            case 'kaprodi':
                if (!empty($role_tujuan)) {
                    // Ambil user tujuan berdasarkan id_role dari form
                    $user_tujuan_obj = $this->db->select('id_user')
                        ->from('user_role')
                        ->where('id_role', (int)$role_tujuan)
                        ->group_start()
                        ->where('id_fakultas', $user_role->id_fakultas)
                        ->or_where('id_prodi', $user_role->id_prodi)
                        ->group_end()
                        ->limit(1)
                        ->get()
                        ->row();

                    if ($user_tujuan_obj) {
                        $tujuan_roles[] = (int)$role_tujuan;
                        $user_tujuan = $user_tujuan_obj->id_user;
                    }
                }
                break;



            case 'perpustakaan dan inovasi':
                $warek1 = $this->User_m->get_role_by_name('Wakil Rektor 1');
                if ($warek1) $tujuan_roles[] = $warek1->id_role;
                break;

            case 'pengembangan bahasa':
                $warek1 = $this->User_m->get_role_by_name('Wakil Rektor 1');
                if ($warek1) $tujuan_roles[] = $warek1->id_role;
                break;

            case 'monitoring dan evaluasi':
                $ppm = $this->User_m->get_role_by_name('Pusat Penjamin Mutu');
                if ($ppm) $tujuan_roles[] = $ppm->id_role;
                break;

            case 'dokumen pelaporan':
                $ppm = $this->User_m->get_role_by_name('Pusat Penjamin Mutu');
                if ($ppm) $tujuan_roles[] = $ppm->id_role;
                break;

            case 'spme':
                $ppm = $this->User_m->get_role_by_name('Pusat Penjamin Mutu');
                if ($ppm) $tujuan_roles[] = $ppm->id_role;
                break;

            case 'lab':
                $siij = $this->User_m->get_role_by_name('Sistem Informasi dan Infrastruktur Jaringan');
                if ($siij) $tujuan_roles[] = $siij->id_role;
                break;

            case 'subag perencanaan sistem':
                $pps = $this->User_m->get_role_by_name('Perencanaan dan Pengembangan Sistem');
                if ($pps) $tujuan_roles[] = $pps->id_role;
                break;

            case 'subag pengembangan sistem':
                $pps = $this->User_m->get_role_by_name('Perencanaan dan Pengembangan Sistem');
                if ($pps) $tujuan_roles[] = $pps->id_role;
                break;

            case 'subag pddikti dan beasiswa':
                $baa = $this->User_m->get_role_by_name('Bagian Administrasi dan Akademik');
                if ($baa) $tujuan_roles[] = $baa->id_role;
                break;

            case 'subag administrasi perkuliahan':
                $baa = $this->User_m->get_role_by_name('Bagian Administrasi dan Akademik');
                if ($baa) $tujuan_roles[] = $baa->id_role;
                break;

            case 'subag penelitian dan luaran':
                $bpp = $this->User_m->get_role_by_name('Bagian Penelitian dan PKM');
                if ($bpp) $tujuan_roles[] = $bpp->id_role;
                break;

            case 'subag pkm dan luaran':
                $bpp = $this->User_m->get_role_by_name('Bagian Penelitian dan PKM');
                if ($bpp) $tujuan_roles[] = $bpp->id_role;
                break;

            case 'subag ketenagaan':
                $pds = $this->User_m->get_role_by_name('Pengembangan dan SDM');
                if ($pds) $tujuan_roles[] = $pds->id_role;
                break;

            case 'pengembangan karir dosen dan tendik':
                $pds = $this->User_m->get_role_by_name('Pengembangan dan SDM');
                if ($pds) $tujuan_roles[] = $pds->id_role;
                break;

            case 'subag penerimaan keuangan':
                $keuangan = $this->User_m->get_role_by_name('Keuangan');
                if ($keuangan) $tujuan_roles[] = $keuangan->id_role;
                break;

            case 'subag anggaran dan pengeluaran':
                $keuangan = $this->User_m->get_role_by_name('Keuangan');
                if ($keuangan) $tujuan_roles[] = $keuangan->id_role;
                break;

            case 'subag akuntansi':
                $keuangan = $this->User_m->get_role_by_name('Keuangan');
                if ($keuangan) $tujuan_roles[] = $keuangan->id_role;
                break;

            case 'subag operasional':
                $opu = $this->User_m->get_role_by_name('Operasional Pembelajaran dan Umum');
                if ($opu) $tujuan_roles[] = $opu->id_role;
                break;

            case 'subag umum':
                $opu = $this->User_m->get_role_by_name('Operasional Pembelajaran dan Umum');
                if ($opu) $tujuan_roles[] = $opu->id_role;
                break;

            case 'subag kemahasiswaan':
                $kk = $this->User_m->get_role_by_name('Kemahasiswaan dan Konseling');
                if ($kk) $tujuan_roles[] = $kk->id_role;
                break;

            case 'subag konseling':
                $kk = $this->User_m->get_role_by_name('Kemahasiswaan dan Konseling');
                if ($kk) $tujuan_roles[] = $kk->id_role;
                break;

            case 'subag admisi pmb':
                $pk = $this->User_m->get_role_by_name('Pemasaran dan Kerjasama');
                if ($pk) $tujuan_roles[] = $pk->id_role;
                break;

            case 'subag kerjasama marketing dan humas':
                $pk = $this->User_m->get_role_by_name('Pemasaran dan Kerjasama');
                if ($pk) $tujuan_roles[] = $pk->id_role;
                break;

            case 'data analyst':
                $digitalmarketing = $this->User_m->get_role_by_name('Subag Digital Marketing');
                if ($digitalmarketing) $tujuan_roles[] = $digitalmarketing->id_role;
                break;

            case 'editor':
                $digitalmarketing = $this->User_m->get_role_by_kode_role('Subag Digital Marketing');
                if ($digitalmarketing) $tujuan_roles[] = $digitalmarketing->id_role;
                break;

            case 'konten kreator':
                $digitalmarketing = $this->User_m->get_role_by_name('Subag Digital Marketing');
                if ($digitalmarketing) $tujuan_roles[] = $digitalmarketing->id_role;
                break;

            case 'subag pemagangan dan penempatan kerja':
                $bic = $this->User_m->get_role_by_name('Bina Insani Career');
                if ($bic) $tujuan_roles[] = $bic->id_role;
                break;

            case 'subag tracer study':
                $bic = $this->User_m->get_role_by_name('Bina Insani Career');
                if ($bic) $tujuan_roles[] = $bic->id_role;
                break;

            case 'subag inkubasi bisnis':
                $wirausaha = $this->User_m->get_role_by_name('Kewirausahaan');
                if ($wirausaha) $tujuan_roles[] = $wirausaha->id_role;
                break;

            case 'subag pengembangan starup':
                $wirausaha = $this->User_m->get_role_by_name('Kewirausahaan');
                if ($wirausaha) $tujuan_roles[] = $wirausaha->id_role;
                break;

            case 'wakil rektor 1':
                if (!empty($role_tujuan)) {
                    $tujuan_roles[] = (int)$role_tujuan;
                }
                break;

            case 'wakil rektor 2':
                if (!empty($role_tujuan)) {
                    $tujuan_roles[] = (int)$role_tujuan;
                }
                break;

            case 'wakil rektor 3':
                if (!empty($role_tujuan)) {
                    $tujuan_roles[] = (int)$role_tujuan;
                }
                break;

            case 'pusat penjamin mutu':
                if (!empty($role_tujuan)) {
                    $tujuan_roles[] = (int)$role_tujuan;
                }
                break;

            case 'rektor':
                $yayasan = $this->User_m->get_role_by_name('Yayasan');
                if ($yayasan) $tujuan_roles[] = $yayasan->id_role;
                break;

            case 'yys':
                if (!empty($role_tujuan)) {
                    $tujuan_roles[] = (int) $role_tujuan;

                    // Ambil user tujuan spesifik jika role tujuan adalah kaprodi, dosen, atau dekan
                    $user_tujuan_input = $this->input->post('user_tujuan_yayasan');
                    if (!empty($user_tujuan_input)) {
                        $tujuan_user = (int)$user_tujuan_input;
                    } else {
                        // fallback cari user_tujuan dari user_role
                        $user_obj = $this->db->select('id_user')->from('user_role')->where('id_role', $role_tujuan)->limit(1)->get()->row();
                        $tujuan_user = $user_obj ? $user_obj->id_user : null;
                    }
                }
                break;

            case 'sistem informasi dan infrastruktur jaringan':
                if (!empty($role_tujuan)) {
                    $tujuan_roles[] = (int)$role_tujuan;
                }
                break;

            case 'perencanaan dan pengembangan sistem':
                if (!empty($role_tujuan)) {
                    $tujuan_roles[] = (int)$role_tujuan;
                }
                break;

            case 'bagian administrasi dan akademik':
                if (!empty($role_tujuan)) {
                    $tujuan_roles[] = (int)$role_tujuan;
                }
                break;

            case 'bagian penelitian dan pkm':
                if (!empty($role_tujuan)) {
                    $tujuan_roles[] = (int)$role_tujuan;
                }
                break;

            case 'pengembangan dan sdm':
                if (!empty($role_tujuan)) {
                    $tujuan_roles[] = (int)$role_tujuan;
                }
                break;

            case 'keuangan':
                if (!empty($role_tujuan)) {
                    $tujuan_roles[] = (int)$role_tujuan;
                }
                break;

            case 'operasional pembelajaran dan umum':
                if (!empty($role_tujuan)) {
                    $tujuan_roles[] = (int)$role_tujuan;
                }
                break;

            case 'kemahasiswaan dan konseling':
                if (!empty($role_tujuan)) {
                    $tujuan_roles[] = (int)$role_tujuan;
                }
                break;

            case 'pemasaran dan kerjasama':
                if (!empty($role_tujuan)) {
                    $tujuan_roles[] = (int)$role_tujuan;
                }
                break;

            case 'subag digital marketing':
                if (!empty($role_tujuan)) {
                    $tujuan_roles[] = (int)$role_tujuan;
                }
                break;

            case 'bina insani career':
                if (!empty($role_tujuan)) {
                    $tujuan_roles[] = (int)$role_tujuan;
                }
                break;

            case 'kewirausahaan':
                if (!empty($role_tujuan)) {
                    $tujuan_roles[] = (int)$role_tujuan;
                }
                break;

            default:
                $this->session->set_flashdata('error', 'Role tidak diizinkan melakukan disposisi.');
                redirect('users/lembarpengajuan');
                return;
        }

        // Simpan disposisi berdasarkan role tujuan
        if (!empty($tujuan_roles)) {
            foreach ($tujuan_roles as $role_id) {
                // Siapkan builder
                if (empty($user_tujuan)) {
                    $this->db->select('id_user')
                        ->from('user_role')
                        ->where('id_role', $role_id);

                    if (in_array($role_name, ['kaprodi'])) {
                        $this->db->group_start()
                            ->where('id_prodi', $user_role->id_prodi)
                            ->or_where('id_fakultas', $user_role->id_fakultas)
                            ->group_end();
                    }
                    $user_tujuan_obj = $this->db->limit(1)->get()->row();
                    $user_tujuan = $user_tujuan_obj->id_user ?? null;
                }

                // Cek jika user_tujuan ditemukan
                if ($user_tujuan) {
                    $data = [
                        'id_pengajuan'      => $pengajuan_id,
                        'dari_user'         => $dari_user_id,
                        'ke_role'           => $role_id,
                        'user_tujuan'       => $user_tujuan,
                        'urutan'            => 1,
                        'tanggal_disposisi' => $tanggal,
                        'catatan'           => $catatan,
                        'status_disposisi'  => 'Diproses'
                    ];
                    $this->db->insert('disposisi', $data);
                } else {
                    // Logging untuk debug
                    log_message('error', "Disposisi gagal: user_tujuan tidak ditemukan untuk role_id = {$role_id} dan role_name = {$role_name}");
                }
            }

            $this->session->set_flashdata('success', 'Disposisi berhasil dikirim.');
        } else {
            $this->session->set_flashdata('error', 'Tujuan disposisi tidak ditemukan.');
        }


        // Redirect ke halaman sukses
        redirect('users/lembarpengajuan/success');
    }

    public function success()
    {
        $this->session->set_flashdata('success', 'Pengajuan berhasil dikirim.');
        redirect('users/lembarpengajuan/');
    }
}
