<?php
$base_path = dirname(__DIR__);
$tcpdf_path = $base_path . '/includes/tcpdf/tcpdf.php';

if (!file_exists($tcpdf_path)) {
    die('Error: TCPDF library not found. Please download from https://github.com/tecnickcom/tcpdf and place in includes/tcpdf/ folder');
}

require_once $tcpdf_path;

class ProfessionalPDF extends TCPDF {
    
    private $company_name = 'Inventory Teknisi WiFi';
    private $total_masuk = 0;
    private $total_keluar = 0;
    private $filters = [];
    
    public function setFilters($filters) {
        $this->filters = $filters;
    }

    public function Header() {
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0, 0, 0);
        $this->SetLineWidth(0.5);
        $this->SetDrawColor(220, 220, 220);
        $this->Line(15, 10, 195, 10);   
        $this->Ln(3);
        $this->SetFont('helvetica', 'B', 18);
        $this->Cell(0, 8, 'LAPORAN TRANSAKSI', 0, 1, 'C');
        $this->SetFont('helvetica', 'B', 12);
        $this->SetTextColor(60, 60, 60);
        $this->Cell(0, 6, $this->company_name, 0, 1, 'C');
        $this->SetFont('helvetica', '', 9);
        $this->SetTextColor(100, 100, 100);
        
        if (!empty($this->filters['start_date']) && !empty($this->filters['end_date'])) {
            $periode = 'Periode: ' . $this->formatTanggal($this->filters['start_date']) . 
                      ' s/d ' . $this->formatTanggal($this->filters['end_date']);
            $this->Cell(0, 5, $periode, 0, 1, 'C');
        } else {
            $this->Cell(0, 5, 'Tanggal Cetak: ' . $this->formatTanggal(date('Y-m-d')), 0, 1, 'C');
        }
        
        if (!empty($this->filters['tipe'])) {
            $this->Cell(0, 5, 'Tipe: ' . ucfirst($this->filters['tipe']), 0, 1, 'C');
        }
        $this->Ln(2);
        $this->SetLineWidth(0.5);
        $this->Line(15, $this->GetY(), 195, $this->GetY());       
        $this->Ln(5);
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetLineWidth(0.3);
        $this->SetDrawColor(220, 220, 220);
        $this->Line(15, $this->GetY(), 195, $this->GetY());    
        $this->Ln(2);
        $this->SetFont('helvetica', 'I', 8);
        $this->SetTextColor(128, 128, 128);
        $this->Cell(90, 5, 'Dicetak: ' . date('d/m/Y H:i'), 0, 0, 'L');
        $this->Cell(90, 5, 'Halaman ' . $this->getAliasNumPage() . ' dari ' . $this->getAliasNbPages(), 0, 0, 'R');
    }

    public function createCleanReport($data, $filters = []) {
        $this->setFilters($filters);
        $this->SetCreator('Sistem Inventory');
        $this->SetAuthor('Inventory Teknisi WiFi');
        $this->SetTitle('Laporan Transaksi');
        $this->SetSubject('Laporan Transaksi Barang');
        $this->SetMargins(15, 40, 15);
        $this->SetHeaderMargin(10);
        $this->SetFooterMargin(15);
        $this->SetAutoPageBreak(TRUE, 25);
        $this->AddPage();
        $this->createCleanTable($data);
        $this->addCleanSignature();
    }
    
    private function createCleanTable($data) {
        if (empty($data)) {
            $this->SetFont('helvetica', 'I', 11);
            $this->SetTextColor(150, 150, 150);
            $this->Cell(0, 40, 'Tidak ada data transaksi untuk ditampilkan', 0, 1, 'C');
            return;
        }
        $w = array(10, 38, 25, 20, 50, 25, 22);
        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(41, 128, 185);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(41, 128, 185);
        $this->SetLineWidth(0.3);
        
        $this->Cell($w[0], 8, 'No', 1, 0, 'C', true);
        $this->Cell($w[1], 8, 'Kode Transaksi', 1, 0, 'C', true);
        $this->Cell($w[2], 8, 'Tanggal', 1, 0, 'C', true);
        $this->Cell($w[3], 8, 'Tipe', 1, 0, 'C', true);
        $this->Cell($w[4], 8, 'Nama Barang', 1, 0, 'C', true);
        $this->Cell($w[5], 8, 'Teknisi', 1, 0, 'C', true);
        $this->Cell($w[6], 8, 'Jumlah', 1, 1, 'C', true);

        $this->SetFont('helvetica', '', 8.5);
        $this->SetTextColor(50, 50, 50);
        $this->SetDrawColor(220, 220, 220);
        
        $no = 1;
        $this->total_masuk = 0;
        $this->total_keluar = 0;
        $fill = false;

        foreach($data as $row) {
            if ($this->GetY() > 240) {
                $this->AddPage();
                $this->SetFont('helvetica', 'B', 9);
                $this->SetFillColor(41, 128, 185);
                $this->SetTextColor(255, 255, 255);
                $this->SetDrawColor(41, 128, 185);
                
                $this->Cell($w[0], 8, 'No', 1, 0, 'C', true);
                $this->Cell($w[1], 8, 'Kode Transaksi', 1, 0, 'C', true);
                $this->Cell($w[2], 8, 'Tanggal', 1, 0, 'C', true);
                $this->Cell($w[3], 8, 'Tipe', 1, 0, 'C', true);
                $this->Cell($w[4], 8, 'Nama Barang', 1, 0, 'C', true);
                $this->Cell($w[5], 8, 'Teknisi', 1, 0, 'C', true);
                $this->Cell($w[6], 8, 'Jumlah', 1, 1, 'C', true);
                
                $this->SetFont('helvetica', '', 8.5);
                $this->SetTextColor(50, 50, 50);
                $this->SetDrawColor(220, 220, 220);
                $fill = false;
            }

            if ($row['tipe'] == 'masuk') {
                $this->total_masuk += $row['jumlah'];
            } else {
                $this->total_keluar += $row['jumlah'];
            }

            if ($fill) {
                $this->SetFillColor(248, 249, 250);
            } else {
                $this->SetFillColor(255, 255, 255);
            }

            $this->Cell($w[0], 7, $no++, 1, 0, 'C', true);

            $this->SetFont('helvetica', '', 7.5);
            $this->Cell($w[1], 7, $row['kode_transaksi'], 1, 0, 'L', true);
            $this->SetFont('helvetica', '', 8.5);

            $this->Cell($w[2], 7, date('d/m/Y', strtotime($row['tanggal'])), 1, 0, 'C', true);

            $tipe_text = ucfirst($row['tipe']);
            if ($row['tipe'] == 'masuk') {
                $this->SetTextColor(39, 174, 96); // Green
            } else {
                $this->SetTextColor(231, 76, 60); // Red
            }
            $this->SetFont('helvetica', 'B', 8.5);
            $this->Cell($w[3], 7, $tipe_text, 1, 0, 'C', true);
            $this->SetFont('helvetica', '', 8.5);
            $this->SetTextColor(50, 50, 50);

            $barang_name = $this->truncateText($row['nama_barang'], 32);
            $this->Cell($w[4], 7, $barang_name, 1, 0, 'L', true);

            $teknisi_name = $this->truncateText($row['nama_teknisi'] ?: '-', 18);
            $this->Cell($w[5], 7, $teknisi_name, 1, 0, 'L', true);

            $jumlah_text = number_format($row['jumlah'], 0, ',', '.') . ' ' . $row['satuan'];
            $this->SetFont('helvetica', 'B', 8.5);
            $this->Cell($w[6], 7, $jumlah_text, 1, 1, 'R', true);
            $this->SetFont('helvetica', '', 8.5);
            
            $fill = !$fill;
        }

        $this->addTableSummary($w);
        
        $this->Ln(10);
    }

    private function addTableSummary($w) {
        $summary_label_width = $w[0] + $w[1] + $w[2] + $w[3] + $w[4] + $w[5];
        $summary_value_width = $w[6]; 

        $this->SetFont('helvetica', 'B', 9);
        $this->SetDrawColor(180, 180, 180); 
        $this->SetLineWidth(0.3);
        
        // Total Masuk
        $this->SetFillColor(220, 220, 220); // Light green
        $this->SetTextColor(50, 50, 50); // Green
        $this->Cell($summary_label_width, 8, 'Total Barang Masuk:', 1, 0, 'R', true);
        $this->SetFont('helvetica', 'B', 10);
        $this->Cell($summary_value_width, 8, number_format($this->total_masuk, 0, ',', '.'), 1, 1, 'R', true);
        
        // Total Keluar
        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(220, 220, 220); 
        $this->SetTextColor(50, 50, 50);
        $this->Cell($summary_label_width, 8, 'Total Barang Keluar:', 1, 0, 'R', true);
        $this->SetFont('helvetica', 'B', 10);
        $this->Cell($summary_value_width, 8, number_format($this->total_keluar, 0, ',', '.'), 1, 1, 'R', true);
        
        $balance = $this->total_masuk - $this->total_keluar;
        $this->SetFont('helvetica', 'B', 9);
        
        if ($balance >= 0) {
            $this->SetFillColor(220, 220, 220); 
            $this->SetTextColor(50, 50, 50); 
        } else {
            $this->SetFillColor(220, 220, 220); 
            $this->SetTextColor(50, 50, 50);
        }
        
        $this->Cell($summary_label_width, 8, 'Total (Masuk - Keluar):', 1, 0, 'R', true);
        $this->SetFont('helvetica', 'B', 10);
        $this->Cell($summary_value_width, 8, number_format($balance, 0, ',', '.'), 1, 1, 'R', true);
    }

    private function addCleanSignature() {
        $current_y = $this->GetY();

        if ($current_y > 240) {
            $this->AddPage();
            $current_y = $this->GetY();
        }
        
        $this->SetFont('helvetica', '', 10);
        $this->SetTextColor(50, 50, 50);
        $box_width = 85;
        $box_height = 40;
        $this->SetXY(15, $current_y);
        $this->SetDrawColor(200, 200, 200);
        $this->SetLineWidth(0.3);
        $this->Cell($box_width, 6, 'Mengetahui,', 0, 1, 'C');
        $left_x = $this->GetX();
        $left_y = $this->GetY();
        $this->SetXY(110, $current_y);
        $this->Cell($box_width, 6, 'Dicetak oleh,', 0, 1, 'C');
        $this->Ln(25);

        $this->SetFont('helvetica', 'B', 10);
 
        $this->SetXY(15, $this->GetY());
        $this->Cell($box_width, 6, '(_________________)', 0, 0, 'C');

        $this->SetXY(110, $this->GetY());
        $this->Cell($box_width, 6, '(_________________)', 0, 1, 'C');
        
        $this->Ln(2);

        $this->SetFont('helvetica', '', 9);
        $this->SetTextColor(100, 100, 100);
        
        $this->SetX(15);
        $this->Cell($box_width, 5, 'Pimpinan', 0, 0, 'C');
        
        $this->SetX(110);
        $this->Cell($box_width, 5, 'Admin', 0, 1, 'C');
    }
    
    private function truncateText($text, $length) {
        if (mb_strlen($text) > $length) {
            return mb_substr($text, 0, $length - 3) . '...';
        }
        return $text;
    }
    
    private function formatTanggal($date) {
        $months = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
        
        $parts = explode('-', $date);
        if (count($parts) === 3) {
            return $parts[2] . ' ' . $months[$parts[1]] . ' ' . $parts[0];
        }
        
        return $date;
    }
}

function generateCleanPDF($data, $filters = [], $filename = null) {
    if (!$filename) {
        $filename = 'Laporan_Transaksi_' . date('Y-m-d') . '.pdf';
    }

    $pdf = new ProfessionalPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);

    $pdf->setPrintHeader(true);
    $pdf->setPrintFooter(true);

    $pdf->createCleanReport($data, $filters);
    
    $pdf->Output($filename, 'I');
    
    return true;
}

function isTCPDFAvailable() {
    $tcpdf_path = dirname(__DIR__) . '/includes/tcpdf/tcpdf.php';
    return file_exists($tcpdf_path);
}

function generateTransactionPDF($data, $filters = [], $filename = null) {
    return generateCleanPDF($data, $filters, $filename);
}
?>