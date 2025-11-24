<?php
// 1. Panggil Library FPDF
require('../assets/fpdf/fpdf.php');
include('../config/koneksi.php');

// 2. Setup Kertas PDF (P = Potrait, mm, A4)
$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();

// 3. Header Laporan
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'LAPORAN ABSENSI PEGAWAI E-STAFF',0,1,'C');
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,'Data Laporan Bulan Ini',0,1,'C');
$pdf->Cell(10,7,'',0,1); // Spasi

// 4. Header Tabel
$pdf->SetFont('Arial','B',10);
$pdf->SetFillColor(200,220,255); // Warna Background Header Biru Muda
$pdf->Cell(10,10,'No',1,0,'C',true);
$pdf->Cell(30,10,'Tanggal',1,0,'C',true);
$pdf->Cell(50,10,'Nama Pegawai',1,0,'C',true);
$pdf->Cell(30,10,'Jam Masuk',1,0,'C',true);
$pdf->Cell(70,10,'Koordinat Lokasi',1,1,'C',true); // 1 di akhir artinya ganti baris

// 5. Isi Data dari Database
$pdf->SetFont('Arial','',10);

$query = "SELECT absensi.*, users.nama_lengkap 
          FROM absensi 
          JOIN users ON absensi.user_id = users.id 
          ORDER BY absensi.tanggal DESC";
$result = mysqli_query($koneksi, $query);
$no = 1;

while($row = mysqli_fetch_assoc($result)){
    $pdf->Cell(10,10,$no++,1,0,'C');
    $pdf->Cell(30,10,date('d-m-Y', strtotime($row['tanggal'])),1,0,'C');
    $pdf->Cell(50,10,$row['nama_lengkap'],1,0,'L');
    $pdf->Cell(30,10,$row['jam_masuk'],1,0,'C');
    $pdf->Cell(70,10,$row['lokasi_lat'] . ',' . $row['lokasi_long'],1,1,'L');
}

// 6. Footer Tanda Tangan (Opsional biar keren)
$pdf->Cell(10,15,'',0,1);
$pdf->SetFont('Arial','I',10);
$pdf->Cell(0,10,'Dicetak pada: ' . date('d-m-Y H:i:s'),0,1,'R');

// 7. Output File
$pdf->Output();
?>