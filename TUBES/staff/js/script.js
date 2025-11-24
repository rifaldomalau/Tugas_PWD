// 1. Jam Digital Berjalan
setInterval(() => {
  const now = new Date();
  document.getElementById("jam-digital").innerText = now.toLocaleTimeString();
}, 1000);

// 2. Geolocation Script
const lokasiInfo = document.getElementById("lokasi-info");
const btnAbsen = document.getElementById("btn-absen");
const inputLat = document.getElementById("latitude");
const inputLong = document.getElementById("longitude");

if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(showPosition, showError);
} else {
  lokasiInfo.innerHTML = "Geolocation tidak didukung browser ini.";
}

function showPosition(position) {
  // Isi nilai input hidden dengan koordinat
  inputLat.value = position.coords.latitude;
  inputLong.value = position.coords.longitude;

  // Update UI
  lokasiInfo.innerHTML = `<span class="text-success">Lokasi Terdeteksi: <br> Lat: ${position.coords.latitude}, Long: ${position.coords.longitude}</span>`;

  // Aktifkan tombol
  if (btnAbsen) btnAbsen.disabled = false;
}

function showError(error) {
  switch (error.code) {
    case error.PERMISSION_DENIED:
      lokasiInfo.innerHTML = "Anda menolak izin lokasi. Harap izinkan!";
      break;
    case error.POSITION_UNAVAILABLE:
      lokasiInfo.innerHTML = "Informasi lokasi tidak tersedia.";
      break;
    case error.TIMEOUT:
      lokasiInfo.innerHTML = "Waktu permintaan lokasi habis.";
      break;
    default:
      lokasiInfo.innerHTML = "Terjadi kesalahan tidak diketahui.";
  }
}
