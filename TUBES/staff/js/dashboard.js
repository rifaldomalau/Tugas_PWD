document.addEventListener("DOMContentLoaded", function () {
  // ==========================================
  // 1. JAM DIGITAL
  // ==========================================
  const jamElement = document.getElementById("jam-digital");
  if (jamElement) {
    setInterval(() => {
      const now = new Date();
      jamElement.innerText = now.toLocaleTimeString();
    }, 1000);
  }

  // ==========================================
  // 2. GEOLOCATION (DETEKSI LOKASI)
  // ==========================================
  const lokasiInfo = document.getElementById("lokasi-info");
  const btnAbsen = document.getElementById("btn-absen");
  const inputLat = document.getElementById("latitude");
  const inputLong = document.getElementById("longitude");

  // Hanya jalankan jika elemen-elemen ini ada di halaman (artinya user belum absen)
  if (lokasiInfo && inputLat && inputLong) {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
      lokasiInfo.innerHTML = "Geolocation tidak didukung browser ini.";
    }
  }

  function showPosition(position) {
    inputLat.value = position.coords.latitude;
    inputLong.value = position.coords.longitude;

    lokasiInfo.innerHTML = `
        <span class="text-success fw-bold">
            📍 Lokasi Terkunci: ${position.coords.latitude}, ${position.coords.longitude}
        </span>`;

    // Aktifkan tombol absen
    if (btnAbsen) btnAbsen.disabled = false;
  }

  function showError(error) {
    switch (error.code) {
      case error.PERMISSION_DENIED:
        lokasiInfo.innerHTML =
          "Gagal: Anda menolak izin lokasi. Harap izinkan!";
        break;
      case error.POSITION_UNAVAILABLE:
        lokasiInfo.innerHTML = "Gagal: Informasi lokasi tidak tersedia.";
        break;
      case error.TIMEOUT:
        lokasiInfo.innerHTML = "Gagal: Waktu permintaan lokasi habis.";
        break;
      default:
        lokasiInfo.innerHTML = "Gagal mendeteksi lokasi (Error tak dikenal).";
    }
  }
});
