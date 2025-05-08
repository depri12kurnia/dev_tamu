<?php

use Dompdf\Dompdf;
use Dompdf\Options;

class Dompdf_gen
{
    public $dompdf;

    public function __construct()
    {
        require_once APPPATH . '../vendor/autoload.php';
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $this->dompdf = new Dompdf($options);
    }
}
