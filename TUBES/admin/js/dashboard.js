document.addEventListener("DOMContentLoaded", function () {
  // Cek data dari PHP
  if (typeof chartData === "undefined") {
    console.error("Error: Data grafik tidak ditemukan dari PHP.");
    return;
  }

  // ==========================================
  // 1. CONFIG LINE CHART (TREN HARIAN) - UPDATE!
  // ==========================================
  const ctxBar = document.getElementById("barChart"); // ID canvas tetap barChart
  if (ctxBar) {
    new Chart(ctxBar.getContext("2d"), {
      type: "line", // UBAH JADI LINE
      data: {
        labels: chartData.labelsBar,
        datasets: [
          {
            label: "Pegawai Hadir",
            data: chartData.dataBar,

            // Styling Garis agar terlihat Modern
            borderColor: "#0d6efd", // Warna Garis (Biru)
            backgroundColor: "rgba(13, 110, 253, 0.1)", // Warna Arsir Bawah (Transparan)
            borderWidth: 2, // Ketebalan garis
            tension: 0.4, // Kelengkungan garis (0 = kaku, 0.4 = halus)
            fill: true, // Isi area di bawah garis

            // Styling Titik Data
            pointBackgroundColor: "#ffffff", // Warna titik (Putih)
            pointBorderColor: "#0d6efd", // Garis pinggir titik (Biru)
            pointRadius: 5, // Besar titik
            pointHoverRadius: 7, // Besar titik saat kursor diarahkan
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1, // Pastikan angka bulat (orang)
              precision: 0,
            },
            grid: { borderDash: [2, 2] },
          },
          x: {
            grid: { display: false },
          },
        },
        plugins: {
          legend: { display: false }, // Sembunyikan legenda
          tooltip: {
            mode: "index",
            intersect: false,
          },
        },
        interaction: {
          mode: "nearest",
          axis: "x",
          intersect: false,
        },
      },
    });
  }

  // ==========================================
  // 2. CONFIG DOUGHNUT CHART (TETAP SAMA)
  // ==========================================
  const ctxDoughnut = document.getElementById("doughnutChart");
  if (ctxDoughnut) {
    new Chart(ctxDoughnut.getContext("2d"), {
      type: "doughnut",
      data: {
        labels: ["Tepat Waktu", "Terlambat", "Izin/Sakit"],
        datasets: [
          {
            data: [
              chartData.totalTepat,
              chartData.totalTelat,
              chartData.totalIzin,
            ],
            backgroundColor: [
              "#198754", // Hijau
              "#dc3545", // Merah
              "#ffc107", // Kuning
            ],
            hoverOffset: 4,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: "bottom",
            labels: {
              usePointStyle: true,
              padding: 20,
            },
          },
        },
      },
    });
  }
});
