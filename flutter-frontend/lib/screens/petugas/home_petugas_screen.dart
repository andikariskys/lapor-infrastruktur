import 'package:flutter/material.dart';
import 'package:lapor_infrastruktur/theme/app_theme.dart';
import 'package:lapor_infrastruktur/screens/petugas/detail_penugasan_screen.dart';


class HomePetugasScreen extends StatelessWidget {
  final String namaUser;
  final String jabatan;
  final String instansi;

  const HomePetugasScreen({
    super.key,
    this.namaUser = 'Andika Risky Septiawan',
    this.jabatan = 'Petugas Bina Marga',
    this.instansi = 'DPUPR Kabupaten Karanganyar',
  });

  // ── Data Dummy ──────────────────────────────────────────────────────────────
  static final Map<String, dynamic> _sedangDikerjakan = {
    'kategori': 'Jalan',
    'tanggal': '23 Apr 2026 • 12:35 WIB',
    'lokasi_nama': 'Jl. Mojosongo Raya, Kec. Jebres, Surakarta',
    'lokasi': 'Kec. Jebres, Surakarta\n-6.134357, 112.138223',
    'koordinat': '-6.134357, 112.138223',
    'status': 'DIKERJAKAN',
    'deskripsi': 'Terdapat lubang yang cukup besar di tengah persimpangan. Sangat berbahaya bagi pengendara pada malam hari karena minim penerangan dan sering tergenang air saat hujan.',
    'catatan_admin': 'Tolong untuk segera diperbaiki karena pada tiga hari lagi akan ditinjau oleh Bapak Presiden dan Wakil Presiden.',
    'icon': Icons.add_road_rounded,
    'iconBg': Color(0xFFE4EFFF),
    'iconColor': Color(0xFF0F3E9F),
  };

  static final List<Map<String, dynamic>> _penugasanTerbaru = [
    {
      'kategori': 'Jalan',
      'tanggal': '26 Apr 2026 • 08:45 WIB',
      'lokasi_nama': 'Jl. Karanganyar-Matesih, Kab. Karanganyar',
      'lokasi': 'Kec. Matesih, Kab. Karanganyar\n-7.534587, 110.838543',
      'koordinat': '-7.534587, 110.838543',
      'status': 'DIPROSES',
      'deskripsi': 'Kerusakan jalan berlubang di persimpangan utama yang cukup berbahaya bagi pengendara.',
      'catatan_admin': 'Harap segera ditangani sebelum akhir bulan.',
      'icon': Icons.add_road_rounded,
      'iconBg': Color(0xFFE4EFFF),
      'iconColor': Color(0xFF0F3E9F),
    },
  ];

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      physics: const BouncingScrollPhysics(),
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 20.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const SizedBox(height: 20),

            // ── Header ──
            _buildHeader(),
            const SizedBox(height: 20),

            // ── Kartu Instansi ──
            _buildInstansiCard(),
            const SizedBox(height: 28),

            // ── Sedang Dikerjakan ──
            Text(
              'Sedang Dikerjakan',
              style: AppTextStyles.label.copyWith(
                fontSize: 18,
                color: const Color(0xFF1A1A1A),
              ),
            ),
            const SizedBox(height: 14),
            _buildTugasCard(context, _sedangDikerjakan),
            const SizedBox(height: 28),

            // ── Penugasan Terbaru ──
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  'Penugasan Terbaru',
                  style: AppTextStyles.label.copyWith(
                    fontSize: 18,
                    color: const Color(0xFF1A1A1A),
                  ),
                ),
                GestureDetector(
                  onTap: () {
                    // Navigate to TugasScreen
                  },
                  child: Text(
                    'Lihat Semua',
                    style: AppTextStyles.label.copyWith(
                      fontSize: 14,
                      color: AppColors.primaryBlue,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 14),
            ..._penugasanTerbaru.map(
              (item) => _buildTugasCard(context, item),
            ),
            const SizedBox(height: 100),
          ],
        ),
      ),
    );
  }

  // ── Header ──────────────────────────────────────────────────────────────────
  Widget _buildHeader() {
    return Row(
      children: [
        Container(
          width: 48,
          height: 48,
          decoration: BoxDecoration(
            color: AppColors.primaryBlue,
            shape: BoxShape.circle,
            boxShadow: [
              BoxShadow(
                color: AppColors.primaryBlue.withValues(alpha: 0.25),
                blurRadius: 12,
                offset: const Offset(0, 4),
              ),
            ],
          ),
          child: const Icon(Icons.person_rounded, color: Colors.white, size: 26),
        ),
        const SizedBox(width: 12),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'HALO, SELAMAT DATANG',
                style: AppTextStyles.appSubtitle.copyWith(
                  fontSize: 11,
                  letterSpacing: 0.5,
                  fontWeight: FontWeight.w600,
                ),
              ),
              const SizedBox(height: 2),
              Text(
                namaUser,
                style: AppTextStyles.label.copyWith(
                  fontSize: 15,
                  fontWeight: FontWeight.w700,
                  color: AppColors.textDark,
                ),
                maxLines: 1,
                overflow: TextOverflow.ellipsis,
              ),
            ],
          ),
        ),
      ],
    );
  }

  // ── Instansi Card ────────────────────────────────────────────────────────────
  Widget _buildInstansiCard() {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [Color(0xFF1A3C9A), Color(0xFF2856C8)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(18),
        boxShadow: [
          BoxShadow(
            color: AppColors.primaryBlue.withValues(alpha: 0.35),
            blurRadius: 20,
            offset: const Offset(0, 8),
          ),
        ],
      ),
      child: Row(
        children: [
          Container(
            width: 48,
            height: 48,
            decoration: BoxDecoration(
              color: Colors.white.withValues(alpha: 0.2),
              borderRadius: BorderRadius.circular(12),
            ),
            child: const Icon(
              Icons.account_balance_rounded,
              color: Colors.white,
              size: 26,
            ),
          ),
          const SizedBox(width: 14),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  jabatan,
                  style: AppTextStyles.label.copyWith(
                    fontSize: 16,
                    fontWeight: FontWeight.w800,
                    color: Colors.white,
                  ),
                ),
                const SizedBox(height: 2),
                Text(
                  instansi,
                  style: AppTextStyles.appSubtitle.copyWith(
                    fontSize: 12,
                    color: Colors.white.withValues(alpha: 0.85),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  // ── Tugas Card ───────────────────────────────────────────────────────────────
  Widget _buildTugasCard(BuildContext context, Map<String, dynamic> item) {
    final String status = item['status'];

    Color badgeBg;
    Color badgeText;
    switch (status) {
      case 'DIKERJAKAN':
        badgeBg = const Color(0xFFD6E4FF);
        badgeText = const Color(0xFF0044C4);
        break;
      case 'DIPROSES':
        badgeBg = const Color(0xFFFCE4CA);
        badgeText = const Color(0xFF8D4F00);
        break;
      case 'DIAJUKAN':
        badgeBg = const Color(0xFFEBEBEB);
        badgeText = const Color(0xFF333333);
        break;
      case 'SELESAI':
        badgeBg = const Color(0xFFA6FA96);
        badgeText = const Color(0xFF1B5E20);
        break;
      default:
        badgeBg = const Color(0xFFEBEBEB);
        badgeText = const Color(0xFF333333);
    }

    return GestureDetector(
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => DetailPenugasanScreen(penugasan: item),
          ),
        );
      },
      child: Container(
        margin: const EdgeInsets.only(bottom: 14),
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withValues(alpha: 0.04),
              blurRadius: 12,
              offset: const Offset(0, 4),
            ),
          ],
          border: Border.all(color: const Color(0xFFF0F0F0)),
        ),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Icon
            Container(
              width: 52,
              height: 52,
              decoration: BoxDecoration(
                color: item['iconBg'],
                borderRadius: BorderRadius.circular(12),
              ),
              child: Icon(item['icon'], color: item['iconColor'], size: 26),
            ),
            const SizedBox(width: 14),

            // Content
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Kategori + Badge
                  Row(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Expanded(
                        child: Text(
                          item['kategori'],
                          style: AppTextStyles.label.copyWith(
                            fontSize: 16,
                            color: Colors.black87,
                          ),
                        ),
                      ),
                      const SizedBox(width: 8),
                      Row(
                        children: [
                          Container(
                            padding: const EdgeInsets.symmetric(
                                horizontal: 10, vertical: 4),
                            decoration: BoxDecoration(
                              color: badgeBg,
                              borderRadius: BorderRadius.circular(12),
                            ),
                            child: Text(
                              status,
                              style: AppTextStyles.label.copyWith(
                                fontSize: 10,
                                fontWeight: FontWeight.w800,
                                color: badgeText,
                              ),
                            ),
                          ),
                          const SizedBox(width: 4),
                          const Icon(Icons.chevron_right_rounded,
                              color: Color(0xFF7A7A7A), size: 20),
                        ],
                      ),
                    ],
                  ),
                  const SizedBox(height: 6),

                  // Tanggal
                  Row(
                    children: [
                      const Icon(Icons.calendar_today_outlined,
                          color: Color(0xFF7A7A7A), size: 13),
                      const SizedBox(width: 5),
                      Text(
                        item['tanggal'],
                        style: AppTextStyles.bodyText.copyWith(
                          fontSize: 12,
                          color: const Color(0xFF7A7A7A),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 8),

                  // Lokasi
                  Row(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Padding(
                        padding: EdgeInsets.only(top: 2),
                        child: Icon(Icons.location_on_outlined,
                            color: Color(0xFF7A7A7A), size: 13),
                      ),
                      const SizedBox(width: 5),
                      Expanded(
                        child: Text(
                          item['lokasi'],
                          style: AppTextStyles.bodyText.copyWith(
                            fontSize: 12,
                            color: const Color(0xFF7A7A7A),
                            height: 1.4,
                          ),
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
