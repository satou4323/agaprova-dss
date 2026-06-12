<?php
namespace App\Services;

class AgaprovaPDF extends \TCPDF {

    public function Header() {
        $y0 = $this->GetY();
        
        $this->SetFont('helvetica', 'B', 13);
        $this->SetTextColor(46, 125, 50);
        $this->Cell(0, 7, 'DSS AGAPROVA', 0, 1, 'C');
        
        $this->SetFont('helvetica', '', 8);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 4, 'Sistema de Soporte a la Decision — Optimizacion Logistica de Ganado Bovino', 0, 1, 'C');
        
        $this->SetDrawColor(46, 125, 50);
        $this->SetLineWidth(0.5);
        $this->Line(15, $this->GetY() + 1, 195, $this->GetY() + 1);
        $this->SetLineWidth(0.2);
        
        $this->SetY(max($y0 + 15, $this->GetY() + 3));
    }

    public function Footer() {
        $this->SetY(-14);
        $this->SetDrawColor(46, 125, 50);
        $this->SetLineWidth(0.3);
        $this->Line(15, $this->GetY(), 195, $this->GetY());
        $this->SetLineWidth(0.2);
        
        $this->SetY($this->GetY() + 1);
        $this->SetFont('helvetica', '', 7);
        $this->SetTextColor(130, 130, 130);
        $this->Cell(0, 4, 'Pagina ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 0, 0, 'C');
        
        $this->SetFont('helvetica', 'I', 6);
        $this->Cell(0, 4, 'AGAPROVA © ' . date('Y'), 0, 0, 'R');
    }
}
