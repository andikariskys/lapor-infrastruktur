import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:lapor_infrastruktur/theme/app_theme.dart';

class DetailLaporanScreen extends StatelessWidget {
  final Map<String, dynamic> laporan;

  const DetailLaporanScreen({super.key, required this.laporan});

  // ─── STATUS BADGE COLOR ───────────────────────────────────────────────────
  Map<String, Color> _getStatusColors(String status) {
    switch (status) {
      case 'SELESAI':
        return {
          'bg': const Color(0xFF98F59B),
          'text': const Color(0xFF1B5E20),
        };
      case 'DIAJUKAN':
        return {
          'bg': const Color(0xFFFCE4CA),
          'text': const Color(0xFF8D4F00),
        };
      case 'DIPROSES':
        return {
          'bg': const Color(0xFFD6E4FF),
          'text': const Color(0xFF0044C4),
        };
      case 'DITOLAK':
        return {
          'bg': const Color(0xFFFFD5D5),
          'text': const Color(0xFF9F0F0F),
        };
      default:
        return {
          'bg': const Color(0xFFEBEBEB),
          'text': const Color(0xFF333333),
        };
    }
  }

  @override
  Widget build(BuildContext context) {
    SystemChrome.setSystemUIOverlayStyle(
      const SystemUiOverlayStyle(
        statusBarColor: Colors.transparent,
        statusBarIconBrightness: Brightness.dark,
      ),
    );

    final String status = laporan['status'] ?? 'DIAJUKAN';
    final String kategori = laporan['kategori'] ?? '-';
    final String tanggal = laporan['tanggal'] ?? '-';
    final String lokasi = laporan['lokasi_nama'] ?? laporan['lokasi'] ?? '-';
    final String koordinat = laporan['koordinat'] ?? '';
    final String deskripsi = laporan['deskripsi'] ?? '-';
    final String? respon = laporan['respon'];
    final String? alasanTolak = laporan['alasan_tolak'];
    final String petugasNama = laporan['petugas_nama'] ?? 'Admin Petugas';
    final String petugasWaktu = laporan['petugas_waktu'] ?? '-';

    final statusColors = _getStatusColors(status);

    return Scaffold(
      backgroundColor: Colors.white,
      appBar: _buildAppBar(context),
      body: SingleChildScrollView(
        physics: const BouncingScrollPhysics(),
        child: Padding(
          padding: const EdgeInsets.symmetric(horizontal: 24.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const SizedBox(height: 16),

              // ── Status Badge + Kategori ──
              Row(
                crossAxisAlignment: CrossAxisAlignment.center,
                children: [
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                    decoration: BoxDecoration(
                      color: statusColors['bg'],
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: Text(
                      status,
                      style: AppTextStyles.label.copyWith(
                        fontSize: 12,
                        fontWeight: FontWeight.w800,
                        color: statusColors['text'],
                        letterSpacing: 0.5,
                      ),
                    ),
                  ),
                ],
              ),

              const SizedBox(height: 10),

              // ── Judul Kategori ──
              Text(
                kategori,
                style: AppTextStyles.label.copyWith(
                  fontSize: 20,
                  fontWeight: FontWeight.w800,
                  color: Colors.black87,
                ),
              ),

              const SizedBox(height: 4),

              // ── Tanggal Laporan ──
              Row(
                children: [
                  const Icon(Icons.calendar_today_outlined,
                      color: Color(0xFF7A7A7A), size: 13),
                  const SizedBox(width: 6),
                  Text(
                    tanggal,
                    style: AppTextStyles.bodyText.copyWith(
                      fontSize: 12,
                      color: const Color(0xFF7A7A7A),
                    ),
                  ),
                ],
              ),

              const SizedBox(height: 24),

              // ── Bukti Dokumentasi ──
              _buildSectionLabel('BUKTI DOKUMENTASI'),
              const SizedBox(height: 12),
              Container(
                height: 180,
                width: double.infinity,
                decoration: BoxDecoration(
                  color: const Color(0xFFE8ECF5),
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(
                    color: const Color(0xFFCDD3E0),
                    width: 2,
                  ),
                ),
                child: ClipRRect(
                  borderRadius: BorderRadius.circular(14),
                  // TODO: ganti dengan Image.network(laporan['foto_url']) saat API siap
                  child: const Icon(
                    Icons.image_outlined,
                    size: 60,
                    color: Color(0xFF9E9E9E),
                  ),
                ),
              ),

              const SizedBox(height: 24),

              // ── Detail Kejadian ──
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(20),
                  border: Border.all(color: const Color(0xFFE2E2E2)),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    _buildSectionLabel('DETAIL KEJADIAN'),
                    const SizedBox(height: 8),
                    Text(
                      deskripsi,
                      style: AppTextStyles.bodyText.copyWith(
                        fontSize: 13,
                        color: Colors.black87,
                        height: 1.6,
                      ),
                    ),
                  ],
                ),
              ),

              const SizedBox(height: 16),

              // ── Lokasi Kejadian ──
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(20),
                  border: Border.all(color: const Color(0xFFE2E2E2)),
                ),
                child: Row(
                  children: [
                    Container(
                      width: 44,
                      height: 44,
                      decoration: const BoxDecoration(
                        color: Color(0xFFE8ECF5),
                        shape: BoxShape.circle,
                      ),
                      child: const Icon(
                        Icons.location_on_rounded,
                        color: AppColors.primaryBlue,
                        size: 24,
                      ),
                    ),
                    const SizedBox(width: 16),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          _buildSectionLabel('LOKASI KEJADIAN'),
                          const SizedBox(height: 4),
                          Text(
                            lokasi,
                            style: AppTextStyles.label.copyWith(
                              fontSize: 15,
                              fontWeight: FontWeight.w700,
                              color: Colors.black87,
                            ),
                          ),
                          if (koordinat.isNotEmpty) ...[
                            const SizedBox(height: 2),
                            Text(
                              koordinat,
                              style: AppTextStyles.bodyText.copyWith(
                                fontSize: 13,
                                color: const Color(0xFF7A7A7A),
                              ),
                            ),
                          ],
                        ],
                      ),
                    ),
                  ],
                ),
              ),

              const SizedBox(height: 16),

              // ── Kondisional berdasarkan Status ──

              // SELESAI → Tampil respon petugas
              if (status == 'SELESAI' && respon != null) ...[
                _buildResponPetugasCard(respon, petugasNama, petugasWaktu),
                const SizedBox(height: 16),
              ],

              // DITOLAK → Tampil alasan penolakan
              if (status == 'DITOLAK') ...[
                _buildAlasanTolakCard(alasanTolak ?? 'Tidak ada keterangan.'),
                const SizedBox(height: 16),
              ],

              // DIAJUKAN / DIPROSES → Tampil status tracking
              if (status == 'DIAJUKAN' || status == 'DIPROSES') ...[
                _buildStatusTrackingCard(status),
                const SizedBox(height: 16),
              ],

              const SizedBox(height: 40),
            ],
          ),
        ),
      ),
    );
  }

  // ─── SECTION LABEL ────────────────────────────────────────────────────────
  Widget _buildSectionLabel(String text) {
    return Text(
      text,
      style: AppTextStyles.label.copyWith(
        fontSize: 11,
        fontWeight: FontWeight.w800,
        color: AppColors.primaryBlue,
        letterSpacing: 1.0,
      ),
    );
  }

  // ─── RESPON PETUGAS CARD ──────────────────────────────────────────────────
  Widget _buildResponPetugasCard(
      String respon, String petugasNama, String petugasWaktu) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: const Color(0xFFF3F4F6),
        borderRadius: BorderRadius.circular(20),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              const Icon(Icons.verified_user_outlined,
                  color: AppColors.primaryBlue, size: 18),
              const SizedBox(width: 8),
              _buildSectionLabel('RESPON PETUGAS'),
            ],
          ),
          const SizedBox(height: 12),
          Text(
            '"$respon"',
            style: AppTextStyles.bodyText.copyWith(
              fontSize: 14,
              fontStyle: FontStyle.italic,
              color: Colors.black87,
              height: 1.5,
            ),
          ),
          const SizedBox(height: 16),
          // Bukti foto petugas placeholder
          Container(
            height: 140,
            width: double.infinity,
            decoration: BoxDecoration(
              color: const Color(0xFFE2E2E2),
              borderRadius: BorderRadius.circular(16),
            ),
            child: const Center(
              child: Icon(Icons.image_outlined, color: Colors.grey, size: 40),
            ),
          ),
          const SizedBox(height: 16),
          Row(
            children: [
              Container(
                width: 36,
                height: 36,
                decoration: const BoxDecoration(
                  color: Color(0xFFD6E4FF),
                  shape: BoxShape.circle,
                ),
                child: const Icon(
                  Icons.person_outline_rounded,
                  color: AppColors.primaryBlue,
                  size: 20,
                ),
              ),
              const SizedBox(width: 12),
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    petugasNama,
                    style: AppTextStyles.label.copyWith(
                      fontSize: 13,
                      fontWeight: FontWeight.w700,
                      color: Colors.black87,
                    ),
                  ),
                  Text(
                    petugasWaktu,
                    style: AppTextStyles.bodyText.copyWith(
                      fontSize: 11,
                      color: const Color(0xFF7A7A7A),
                    ),
                  ),
                ],
              ),
            ],
          ),
        ],
      ),
    );
  }

  // ─── ALASAN TOLAK CARD ────────────────────────────────────────────────────
  Widget _buildAlasanTolakCard(String alasan) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: const Color(0xFFFFF3F3),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: const Color(0xFFFFD5D5)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              const Icon(Icons.cancel_outlined, color: Color(0xFF9F0F0F), size: 18),
              const SizedBox(width: 8),
              Text(
                'ALASAN PENOLAKAN',
                style: AppTextStyles.label.copyWith(
                  fontSize: 11,
                  fontWeight: FontWeight.w800,
                  color: const Color(0xFF9F0F0F),
                  letterSpacing: 1.0,
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          Text(
            alasan,
            style: AppTextStyles.bodyText.copyWith(
              fontSize: 14,
              color: Colors.black87,
              height: 1.5,
            ),
          ),
        ],
      ),
    );
  }

  // ─── STATUS TRACKING CARD ─────────────────────────────────────────────────
  Widget _buildStatusTrackingCard(String status) {
    final steps = [
      {'label': 'Laporan Dikirim', 'done': true},
      {'label': 'Sedang Ditinjau', 'done': status == 'DIPROSES'},
      {'label': 'Perbaikan Berlangsung', 'done': false},
      {'label': 'Selesai', 'done': false},
    ];

    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: const Color(0xFFF3F4F6),
        borderRadius: BorderRadius.circular(20),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              const Icon(Icons.track_changes_rounded,
                  color: AppColors.primaryBlue, size: 18),
              const SizedBox(width: 8),
              _buildSectionLabel('PROGRES LAPORAN'),
            ],
          ),
          const SizedBox(height: 16),
          ...steps.asMap().entries.map((entry) {
            final i = entry.key;
            final step = entry.value;
            final isDone = step['done'] as bool;
            final isLast = i == steps.length - 1;

            return Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Column(
                  children: [
                    Container(
                      width: 24,
                      height: 24,
                      decoration: BoxDecoration(
                        color: isDone
                            ? AppColors.primaryBlue
                            : const Color(0xFFDDDDDD),
                        shape: BoxShape.circle,
                      ),
                      child: Icon(
                        isDone ? Icons.check_rounded : Icons.circle_outlined,
                        color: Colors.white,
                        size: 14,
                      ),
                    ),
                    if (!isLast)
                      Container(
                        width: 2,
                        height: 32,
                        color: isDone
                            ? AppColors.primaryBlue.withValues(alpha: 0.3)
                            : const Color(0xFFDDDDDD),
                      ),
                  ],
                ),
                const SizedBox(width: 14),
                Padding(
                  padding: const EdgeInsets.only(top: 3),
                  child: Text(
                    step['label'] as String,
                    style: AppTextStyles.label.copyWith(
                      fontSize: 14,
                      fontWeight:
                          isDone ? FontWeight.w700 : FontWeight.w500,
                      color: isDone ? Colors.black87 : const Color(0xFF9E9E9E),
                    ),
                  ),
                ),
              ],
            );
          }),
        ],
      ),
    );
  }

  // ─── APP BAR ──────────────────────────────────────────────────────────────
  PreferredSizeWidget _buildAppBar(BuildContext context) {
    return AppBar(
      backgroundColor: Colors.white,
      elevation: 0,
      scrolledUnderElevation: 0,
      leading: GestureDetector(
        onTap: () => Navigator.pop(context),
        child: const Padding(
          padding: EdgeInsets.only(left: 12),
          child: Icon(
            Icons.chevron_left_rounded,
            color: AppColors.primaryBlue,
            size: 32,
          ),
        ),
      ),
      title: Text(
        'Detail Laporan',
        style: AppTextStyles.label.copyWith(
          fontSize: 18,
          fontWeight: FontWeight.w800,
          color: AppColors.primaryBlue,
        ),
      ),
      titleSpacing: 0,
      centerTitle: false,
    );
  }
}
