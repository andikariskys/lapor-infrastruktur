import 'package:flutter/material.dart';
import 'package:lapor_infrastruktur/theme/app_theme.dart';
import 'package:lapor_infrastruktur/screens/petugas/detail_penugasan_screen.dart';
import 'package:lapor_infrastruktur/services/api_service.dart';
import 'package:lapor_infrastruktur/services/location_service.dart';


class HomePetugasScreen extends StatefulWidget {
  final String namaUser;
  final String jabatan;
  final String instansi;
  final VoidCallback? onViewAllTugas;

  const HomePetugasScreen({
    super.key,
    this.namaUser = 'Petugas',
    this.jabatan = 'Petugas Lapangan',
    this.instansi = 'Dinas Pekerjaan Umum',
    this.onViewAllTugas,
  });

  @override
  State<HomePetugasScreen> createState() => _HomePetugasScreenState();
}

class _HomePetugasScreenState extends State<HomePetugasScreen> {
  List<Map<String, dynamic>> _tugasList = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadAssignedReports();
  }

  Future<void> _loadAssignedReports() async {
    try {
      final reports = await ApiService.getAssignedReports();
      setState(() {
        _tugasList = reports.map((r) {
          final report = r as Map<String, dynamic>;
          final kategoriName = report['category']?['name'] ?? 'Lainnya';
          final status = _mapStatus(report['status'] ?? 'pending');
          final lat = report['latitude'] ?? 0.0;
          final lon = report['longitude'] ?? 0.0;

          return {
            'id': report['id'],
            'kategori': kategoriName,
            'tanggal': _formatDate(report['created_at']),
            'lokasi': 'Memuat lokasi...',
            'lokasi_nama': 'Memuat...',
            'koordinat': '${(lat as num).toStringAsFixed(6)}, ${(lon as num).toStringAsFixed(6)}',
            'latitude': lat,
            'longitude': lon,
            'deskripsi': report['description'] ?? '-',
            'status': status,
            'foto_url': report['photo_url'],
            'catatan_admin': report['assignments'] != null && (report['assignments'] as List).isNotEmpty
                ? (report['assignments'] as List).first['note'] ?? ''
                : '',
            'icon': Icons.add_road_rounded,
            'iconBg': const Color(0xFFE4EFFF),
            'iconColor': const Color(0xFF0F3E9F),
          };
        }).toList();
        _isLoading = false;
      });
      _reverseGeocodeAll();
    } catch (e) {
      setState(() {
        _tugasList = [];
        _isLoading = false;
      });
    }
  }

  Future<void> _reverseGeocodeAll() async {
    for (int i = 0; i < _tugasList.length; i++) {
      final lat = _tugasList[i]['latitude'];
      final lon = _tugasList[i]['longitude'];
      if (lat != null && lon != null && lat != 0.0 && lon != 0.0) {
        try {
          final address = await LocationService.reverseGeocode(
            (lat as num).toDouble(),
            (lon as num).toDouble(),
          );
          if (!mounted) return;
          setState(() {
            _tugasList[i]['lokasi'] = '$address\n${_tugasList[i]['koordinat']}';
            _tugasList[i]['lokasi_nama'] = address;
          });
        } catch (_) {
          if (!mounted) return;
          setState(() {
            _tugasList[i]['lokasi'] = _tugasList[i]['koordinat'];
            _tugasList[i]['lokasi_nama'] = _tugasList[i]['koordinat'];
          });
        }
      }
    }
  }

  String _mapStatus(String apiStatus) {
    switch (apiStatus) {
      case 'pending':
        return 'DIAJUKAN';
      case 'verified':
        return 'DIPROSES';
      case 'in_progress':
        return 'DIKERJAKAN';
      case 'resolved':
        return 'SELESAI';
      case 'spam':
        return 'DITOLAK';
      default:
        return 'DIAJUKAN';
    }
  }

  String _formatDate(String? dateStr) {
    if (dateStr == null) return '-';
    try {
      final date = DateTime.parse(dateStr);
      final months = [
        '', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
        'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
      ];
      return '${date.day} ${months[date.month]} ${date.year} • ${date.hour.toString().padLeft(2, '0')}:${date.minute.toString().padLeft(2, '0')} WIB';
    } catch (_) {
      return dateStr;
    }
  }

  @override
  Widget build(BuildContext context) {
    // Separate by status
    final sedangDikerjakan = _tugasList.where((t) => t['status'] == 'DIKERJAKAN').toList();
    final penugasanLain = _tugasList.where((t) => t['status'] != 'DIKERJAKAN').toList();

    return RefreshIndicator(
      onRefresh: _loadAssignedReports,
      color: AppColors.primaryBlue,
      child: SingleChildScrollView(
        physics: const AlwaysScrollableScrollPhysics(
          parent: BouncingScrollPhysics(),
        ),
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

              if (_isLoading)
                const Center(
                  child: Padding(
                    padding: EdgeInsets.symmetric(vertical: 40),
                    child: CircularProgressIndicator(color: AppColors.primaryBlue),
                  ),
                )
              else ...[
                // ── Sedang Dikerjakan ──
                if (sedangDikerjakan.isNotEmpty) ...[
                  Text(
                    'Sedang Dikerjakan',
                    style: AppTextStyles.label.copyWith(
                      fontSize: 18,
                      color: const Color(0xFF1A1A1A),
                    ),
                  ),
                  const SizedBox(height: 14),
                  ...sedangDikerjakan.map((item) => _buildTugasCard(context, item)),
                  const SizedBox(height: 28),
                ],

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
                      onTap: widget.onViewAllTugas,
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
                if (penugasanLain.isEmpty && sedangDikerjakan.isEmpty)
                  Center(
                    child: Padding(
                      padding: const EdgeInsets.symmetric(vertical: 40),
                      child: Column(
                        children: [
                          Icon(Icons.assignment_outlined,
                              size: 48, color: Colors.grey.withValues(alpha: 0.5)),
                          const SizedBox(height: 12),
                          Text(
                            'Belum ada penugasan.',
                            style: AppTextStyles.label.copyWith(
                              color: const Color(0xFF7A7A7A),
                            ),
                          ),
                        ],
                      ),
                    ),
                  )
                else
                  ...penugasanLain.map((item) => _buildTugasCard(context, item)),
              ],

              const SizedBox(height: 100),
            ],
          ),
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
                widget.namaUser,
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
                  widget.jabatan,
                  style: AppTextStyles.label.copyWith(
                    fontSize: 16,
                    fontWeight: FontWeight.w800,
                    color: Colors.white,
                  ),
                ),
                const SizedBox(height: 2),
                Text(
                  widget.instansi,
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
