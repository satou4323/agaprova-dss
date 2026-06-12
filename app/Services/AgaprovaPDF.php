<?php
namespace App\Services;

class AgaprovaPDF extends \TCPDF {

    public function Header() {
        $this->SetFont('helvetica', 'B', 14);
        $this->SetTextColor(46, 125, 50);
        $this->Cell(0, 8, 'DSS AGAPROVA', 0, 1, 'C');
        $this->SetFont('helvetica', '', 9);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 5, 'Sistema de Soporte a la Decision - Optimizacion Logistica', 0, 1, 'C');
        $this->SetDrawColor(46, 125, 50);
        $this->Line(15, 22, 195, 22);
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', '', 8);
        $this->SetTextColor(128, 128, 128);
        $this->Cell(0, 10, 'Pagina ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}
