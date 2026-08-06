<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Thin wrapper around dompdf so controllers don't have to know its internals.
 * Usage:
 *     $this->load->library('pdf');
 *     $this->pdf->loadHtml($html);
 *     $this->pdf->download('invoice-42.pdf');   // sends headers + exit
 */
class Pdf
{
    /** @var \Dompdf\Dompdf|null */
    private $dompdf;

    public function __construct()
    {
        $autoload = APPPATH . 'third_party/dompdf/autoload.php';
        if (!file_exists($autoload)) {
            show_error('dompdf is not installed. Expected at: ' . $autoload, 500);
        }
        require_once $autoload;

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', false);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $this->dompdf = new \Dompdf\Dompdf($options);
    }

    public function loadHtml($html, $encoding = 'UTF-8')
    {
        $this->dompdf->loadHtml($html, $encoding);
    }

    public function setPaper($size = 'A4', $orientation = 'portrait')
    {
        $this->dompdf->setPaper($size, $orientation);
    }

    public function download($filename = 'invoice.pdf')
    {
        $this->dompdf->render();
        $this->dompdf->stream($filename, array('Attachment' => true));
        exit;
    }

    public function inline($filename = 'invoice.pdf')
    {
        $this->dompdf->render();
        $this->dompdf->stream($filename, array('Attachment' => false));
        exit;
    }
}
