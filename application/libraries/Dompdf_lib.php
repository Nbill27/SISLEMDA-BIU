<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/third_party/dompdf/autoload.inc.php'; // Sesuaikan path jika berbeda

use Dompdf\Dompdf;
use Dompdf\Options;

class Dompdf_lib {
    protected $ci;
    protected $dompdf;

    public function __construct() {
        $this->ci =& get_instance();
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true); // Penting untuk gambar
        $this->dompdf = new Dompdf($options);
    }

    public function createPdf($html, $filename, $paper = 'A4', $orientation = 'portrait', $stream = TRUE) {
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper($paper, $orientation);
        $this->dompdf->render();

        if ($stream) {
            $this->dompdf->stream($filename, array("Attachment" => 0)); // 0 = open in browser, 1 = download
        } else {
            return $this->dompdf->output();
        }
    }
}