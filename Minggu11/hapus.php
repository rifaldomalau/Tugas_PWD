<?php
//cek dahulu, apakah benar URL sudah ada GET id -> hapus.php?id=siswa_id
if(isset($_GET['id'])){
	
	//include atau memasukkan file koneksi ke database
	include('koneksi.php');
	
	//membuat variabel $id yg bernilai dari URL GET id -> hapus.php?id=siswa_id
	$id = $_GET['id'];
	
	//cek ke database apakah ada data siswa dengan siswa_id='$id'
	//PERBAIKAN: Menggunakan mysqli_query dan variabel $koneksi
	$cek = mysqli_query($koneksi, "SELECT siswa_id FROM siswa WHERE siswa_id='$id'") or die(mysqli_error($koneksi));
	
	//jika data siswa tidak ada
	//PERBAIKAN: Menggunakan mysqli_num_rows
	if(mysqli_num_rows($cek) == 0){
		
		//jika data tidak ada, maka redirect atau dikembalikan ke halaman beranda
		echo '<script>window.history.back()</script>';
	
	}else{
		
		//jika data ada di database, maka melakukan query DELETE table siswa dengan kondisi WHERE siswa_id='$id'
		//PERBAIKAN: Menggunakan mysqli_query
		$del = mysqli_query($koneksi, "DELETE FROM siswa WHERE siswa_id='$id'");
		
		//jika query DELETE berhasil
		if($del){
			
			echo 'Data siswa berhasil di hapus! ';		//Pesan jika proses hapus berhasil
			echo '<a href="index.php">Kembali</a>';	//membuat Link untuk kembali ke halaman beranda
			
		}else{
			
			echo 'Gagal menghapus data! ';		//Pesan jika proses hapus gagal
			echo '<a href="index.php">Kembali</a>';	//membuat Link untuk kembali ke halaman beranda
		
		}
		
	}
	
}else{
	
	//redirect atau dikembalikan ke halaman beranda
	echo '<script>window.history.back()</script>';
	
}
?>