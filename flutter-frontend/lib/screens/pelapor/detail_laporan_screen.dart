import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:url_launcher/url_launcher.dart';
import 'package:lapor_infrastruktur/theme/app_theme.dart';
import 'package:lapor_infrastruktur/services/api_service.dart';

class DetailLaporanScreen extends StatefulWidget {
  final Map<String, dynamic> laporan;

  const DetailLaporanScreen({super.key, required this.laporan});

  @override
  State<DetailLaporanScreen> createState() => _DetailLaporanScreenState();
}

class _DetailLaporanScreenState extends State<DetailLaporanScreen> {
  Map<String, dynamic>? _detail;
  bool _isLoadingDetail = true;

  // Feedback state
  int _selectedRating = 0;
  final TextEditingController _feedbackController = TextEditingController();
  bool _isSendingFeedback = false;
  bool _hasFeedback = false;

  @override
  void initState() {
    super.initState();
    _loadDetail();
  }

  @override
  void dispose() {
    _feedbackController.dispose();
    super.dispose();
  }

  Future<void> _loadDetail() async {
    final reportId = widget.laporan['id'];
    if (reportId == null) {
      setState(() => _isLoadingDetail = false);
      return;
    }
    try {
      final detail = await ApiService.getReportDetail(reportId);
      if (!mounted) return;
      setState(() {
        _detail = detail;
        _isLoadingDetail = false;
        final feedbacks = detail['feedbacks'] as List? ?? [];
        _hasFeedback = feedbacks.isNotEmpty;
      });
    } catch (_) {
      if (!mounted) return;
      setState(() => _isLoadingDetail = false);
    }
  }

  String _getDisplayStatus(String apiStatus) {
    switch (apiStatus) {
      case 'pending':
        return 'DIAJUKAN';
      case 'verified':
        return 'DIPROSES';
      case 'in_progress':
        return 'PERBAIKAN';
      case 'resolved':
        return 'SELESAI';
      case 'spam':
        return 'TIDAK VALID';
      default:
        return 'DIAJUKAN';
    }
  }

  Map<String, Color> _getStatusColors(String displayStatus) {
    switch (displayStatus) {
      case 'SELESAI':
        return {'bg': const Color(0xFF98F59B), 'text': const Color(0xFF1B5E20)};
      case 'DIAJUKAN':
        return {'bg': const Color(0xFFFCE4CA), 'text': const Color(0xFF8D4F00)};
      case 'DIPROSES':
        return {'bg': const Color(0xFFD6E4FF), 'text': const Color(0xFF0044C4)};
      case 'PERBAIKAN':
        return {'bg': const Color(0xFFFFF3E0), 'text': const Color(0xFFE65100)};
      case 'TIDAK VALID':
        return {'bg': const Color(0xFFFFD5D5), 'text': const Color(0xFF9F0F0F)};
      default:
        return {'bg': const Color(0xFFEBEBEB), 'text': const Color(0xFF333333)};
    }
  }

  void _showSnackBar(String message, {bool isError = false}) {
    if (!mounted) return;
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message, style: const TextStyle(color: Colors.white)),
        backgroundColor: isError ? Colors.redAccent : AppColors.primaryBlue,
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
      ),
    );
  }

  Future<void> _handleSendFeedback() async {
    if (_selectedRating == 0) {
      _showSnackBar('Harap pilih rating terlebih dahulu.', isError: true);
      return;
    }
    if (_feedbackController.text.trim().isEmpty) {
      _showSnackBar('Harap isi ulasan terlebih dahulu.', isError: true);
      return;
    }

    final reportId = widget.laporan['id'] ?? _detail?['id'];
    if (reportId == null) return;

    setState(() => _isSendingFeedback = true);
    try {
      await ApiService.createFeedback(
        reportId: reportId,
        content: _feedbackController.text.trim(),
        rating: _selectedRating,
      );
      if (!mounted) return;
      setState(() {
        _isSendingFeedback = false;
        _hasFeedback = true;
      });
      _showSnackBar('Ulasan berhasil dikirim!');
      _loadDetail();
    } catch (e) {
      if (!mounted) return;
      setState(() => _isSendingFeedback = false);
      _showSnackBar(
        e.toString().replaceFirst('Exception: ', ''),
        isError: true,
      );
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

    // Use API detail if loaded, otherwise fallback to passed data
    final apiStatus = _detail?['status'] ?? widget.laporan['api_status'] ?? 'pending';
    final String status = _getDisplayStatus(apiStatus);
    final String kategori = widget.laporan['kategori'] ?? _detail?['category']?['name'] ?? '-';
    final String tanggal = widget.laporan['tanggal'] ?? '-';
    final String lokasi = widget.laporan['lokasi_nama'] ?? widget.laporan['lokasi'] ?? '-';
    final String koordinat = widget.laporan['koordinat'] ?? '';
    final String deskripsi = widget.laporan['deskripsi'] ?? _detail?['description'] ?? '-';
    final String? fotoUrl = widget.laporan['foto_url'] ?? _detail?['photo_url'];

    final statusColors = _getStatusColors(status);

    return Scaffold(
      backgroundColor: Colors.white,
      appBar: _buildAppBar(context),
      body: _isLoadingDetail
          ? const Center(child: CircularProgressIndicator(color: AppColors.primaryBlue))
          : SingleChildScrollView(
              physics: const BouncingScrollPhysics(),
              child: Padding(
                padding: const EdgeInsets.symmetric(horizontal: 24.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const SizedBox(height: 16),
                    // Status Badge
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
                    const SizedBox(height: 10),
                    // Kategori
                    Text(
                      kategori,
                      style: AppTextStyles.label.copyWith(
                        fontSize: 20, fontWeight: FontWeight.w800, color: Colors.black87,
                      ),
                    ),
                    const SizedBox(height: 4),
                    // Tanggal
                    Row(children: [
                      const Icon(Icons.calendar_today_outlined, color: Color(0xFF7A7A7A), size: 13),
                      const SizedBox(width: 6),
                      Text(tanggal, style: AppTextStyles.bodyText.copyWith(fontSize: 12, color: const Color(0xFF7A7A7A))),
                    ]),
                    const SizedBox(height: 24),

                    // SPAM → Pesan tidak valid
                    if (apiStatus == 'spam') ...[
                      _buildSpamCard(),
                      const SizedBox(height: 16),
                    ],

                    // Bukti Dokumentasi
                    _buildSectionLabel('BUKTI DOKUMENTASI'),
                    const SizedBox(height: 12),
                    _buildFotoCard(fotoUrl),
                    const SizedBox(height: 24),

                    // Detail Kejadian
                    _buildDetailKejadianCard(deskripsi),
                    const SizedBox(height: 16),

                    // Lokasi
                    _buildLokasiCard(lokasi, koordinat),
                    const SizedBox(height: 16),

                    // SELESAI → Respon petugas + rating
                    if (apiStatus == 'resolved') ...[
                      _buildResolvedSection(),
                      const SizedBox(height: 16),
                    ],

                    // Tracking untuk status selain selesai dan spam
                    if (apiStatus != 'resolved' && apiStatus != 'spam') ...[
                      _buildStatusTrackingCard(apiStatus),
                      const SizedBox(height: 16),
                    ],

                    const SizedBox(height: 40),
                  ],
                ),
              ),
            ),
    );
  }

  // ─── SPAM CARD ──────────────────────────────────────────────────────────
  Widget _buildSpamCard() {
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
          Row(children: [
            const Icon(Icons.warning_amber_rounded, color: Color(0xFF9F0F0F), size: 20),
            const SizedBox(width: 8),
            Text(
              'LAPORAN TIDAK VALID / HOAX',
              style: AppTextStyles.label.copyWith(
                fontSize: 12, fontWeight: FontWeight.w800,
                color: const Color(0xFF9F0F0F), letterSpacing: 0.8,
              ),
            ),
          ]),
          const SizedBox(height: 12),
          Text(
            'Laporan Anda telah ditinjau dan dinyatakan tidak valid atau terindikasi hoax. Jika Anda merasa ini keliru, silakan buat laporan baru dengan informasi yang lebih lengkap.',
            style: AppTextStyles.bodyText.copyWith(fontSize: 13, color: Colors.black87, height: 1.6),
          ),
        ],
      ),
    );
  }

  // ─── FOTO CARD ──────────────────────────────────────────────────────────
  Widget _buildFotoCard(String? fotoUrl) {
    return Container(
      height: 200,
      width: double.infinity,
      decoration: BoxDecoration(
        color: const Color(0xFFE8ECF5),
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFCDD3E0), width: 2),
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(14),
        child: fotoUrl != null
            ? Image.network(
                '${ApiService.baseUrl.replaceAll('/api', '')}$fotoUrl',
                fit: BoxFit.cover, width: double.infinity,
                loadingBuilder: (context, child, p) {
                  if (p == null) return child;
                  return const Center(child: CircularProgressIndicator(color: AppColors.primaryBlue));
                },
                errorBuilder: (_, __, ___) => const Center(
                  child: Icon(Icons.broken_image_outlined, size: 60, color: Color(0xFF9E9E9E)),
                ),
              )
            : const Center(child: Icon(Icons.image_outlined, size: 60, color: Color(0xFF9E9E9E))),
      ),
    );
  }

  // ─── DETAIL KEJADIAN ────────────────────────────────────────────────────
  Widget _buildDetailKejadianCard(String deskripsi) {
    return Container(
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
          Text(deskripsi, style: AppTextStyles.bodyText.copyWith(fontSize: 13, color: Colors.black87, height: 1.6)),
        ],
      ),
    );
  }

  // ─── LOKASI CARD ────────────────────────────────────────────────────────
  Widget _buildLokasiCard(String lokasi, String koordinat) {
    return Container(
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
          Row(children: [
            Container(
              width: 44, height: 44,
              decoration: const BoxDecoration(color: Color(0xFFE8ECF5), shape: BoxShape.circle),
              child: const Icon(Icons.location_on_rounded, color: AppColors.primaryBlue, size: 24),
            ),
            const SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  _buildSectionLabel('LOKASI KEJADIAN'),
                  const SizedBox(height: 4),
                  Text(lokasi, style: AppTextStyles.label.copyWith(fontSize: 15, fontWeight: FontWeight.w700, color: Colors.black87)),
                  if (koordinat.isNotEmpty) ...[
                    const SizedBox(height: 2),
                    Text(koordinat, style: AppTextStyles.bodyText.copyWith(fontSize: 13, color: const Color(0xFF7A7A7A))),
                  ],
                ],
              ),
            ),
          ]),
          if (koordinat.isNotEmpty) ...[
            const SizedBox(height: 14),
            SizedBox(
              width: double.infinity, height: 44,
              child: OutlinedButton.icon(
                onPressed: () async {
                  final coords = koordinat.split(',');
                  if (coords.length == 2) {
                    final lat = coords[0].trim();
                    final lng = coords[1].trim();
                    final Uri mapsUri = Uri.parse('https://www.google.com/maps/search/?api=1&query=$lat,$lng');
                    try { await launchUrl(mapsUri, mode: LaunchMode.externalApplication); } catch (_) {}
                  }
                },
                icon: const Icon(Icons.map_outlined, color: AppColors.primaryBlue, size: 18),
                label: Text('Buka Google Maps', style: AppTextStyles.label.copyWith(fontSize: 13, color: AppColors.primaryBlue)),
                style: OutlinedButton.styleFrom(
                  side: const BorderSide(color: AppColors.primaryBlue, width: 1.5),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                ),
              ),
            ),
          ],
        ],
      ),
    );
  }

  // ─── RESOLVED SECTION (Respon Petugas + Rating) ─────────────────────────
  Widget _buildResolvedSection() {
    final assignments = (_detail?['assignments'] as List?) ?? [];
    final resolutionPhoto = _detail?['resolution_photo'];
    final feedbacks = (_detail?['feedbacks'] as List?) ?? [];

    // Get officer info from assignments
    String petugasNama = 'Petugas';
    String? responText;
    if (assignments.isNotEmpty) {
      final assignment = assignments.first as Map<String, dynamic>;
      final officer = assignment['officer'] as Map<String, dynamic>?;
      petugasNama = officer?['name'] ?? 'Petugas';
      responText = assignment['note'];
    }

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        // Respon Petugas Card
        Container(
          width: double.infinity,
          padding: const EdgeInsets.all(20),
          decoration: BoxDecoration(
            color: const Color(0xFFF3F4F6),
            borderRadius: BorderRadius.circular(20),
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(children: [
                const Icon(Icons.verified_user_outlined, color: AppColors.primaryBlue, size: 18),
                const SizedBox(width: 8),
                _buildSectionLabel('RESPON PETUGAS'),
              ]),
              const SizedBox(height: 12),
              if (responText != null && responText.isNotEmpty)
                Text(
                  '"$responText"',
                  style: AppTextStyles.bodyText.copyWith(
                    fontSize: 14, fontStyle: FontStyle.italic, color: Colors.black87, height: 1.5,
                  ),
                ),
              if (responText == null || responText.isEmpty)
                Text(
                  'Perbaikan telah diselesaikan.',
                  style: AppTextStyles.bodyText.copyWith(fontSize: 14, color: Colors.black87, height: 1.5),
                ),
              const SizedBox(height: 16),
              // Resolution photo
              Container(
                height: 160,
                width: double.infinity,
                decoration: BoxDecoration(
                  color: const Color(0xFFE2E2E2),
                  borderRadius: BorderRadius.circular(16),
                ),
                child: ClipRRect(
                  borderRadius: BorderRadius.circular(16),
                  child: resolutionPhoto != null && resolutionPhoto.toString().isNotEmpty
                      ? Image.network(
                          '${ApiService.baseUrl.replaceAll('/api', '')}$resolutionPhoto',
                          fit: BoxFit.cover, width: double.infinity,
                          loadingBuilder: (context, child, p) {
                            if (p == null) return child;
                            return const Center(child: CircularProgressIndicator(color: AppColors.primaryBlue));
                          },
                          errorBuilder: (_, __, ___) => const Center(
                            child: Icon(Icons.broken_image_outlined, color: Colors.grey, size: 40),
                          ),
                        )
                      : const Center(child: Icon(Icons.image_outlined, color: Colors.grey, size: 40)),
                ),
              ),
              const SizedBox(height: 16),
              // Petugas info
              Row(children: [
                Container(
                  width: 36, height: 36,
                  decoration: const BoxDecoration(color: Color(0xFFD6E4FF), shape: BoxShape.circle),
                  child: const Icon(Icons.person_outline_rounded, color: AppColors.primaryBlue, size: 20),
                ),
                const SizedBox(width: 12),
                Text(petugasNama, style: AppTextStyles.label.copyWith(fontSize: 13, fontWeight: FontWeight.w700, color: Colors.black87)),
              ]),
            ],
          ),
        ),
        const SizedBox(height: 16),

        // Existing feedback display
        if (feedbacks.isNotEmpty) ...[
          _buildExistingFeedback(feedbacks.first as Map<String, dynamic>),
        ],

        // Feedback form (only if no feedback yet)
        if (!_hasFeedback && feedbacks.isEmpty) ...[
          _buildFeedbackForm(),
        ],
      ],
    );
  }

  // ─── EXISTING FEEDBACK ──────────────────────────────────────────────────
  Widget _buildExistingFeedback(Map<String, dynamic> feedback) {
    final rating = feedback['rating'] as int? ?? 0;
    final content = feedback['content'] as String? ?? '';

    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: const Color(0xFFF0FFF4),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: const Color(0xFFA6FA96)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(children: [
            const Icon(Icons.star_rounded, color: Color(0xFFFFB300), size: 20),
            const SizedBox(width: 8),
            _buildSectionLabel('ULASAN ANDA'),
          ]),
          const SizedBox(height: 12),
          Row(
            children: List.generate(5, (i) => Icon(
              i < rating ? Icons.star_rounded : Icons.star_outline_rounded,
              color: const Color(0xFFFFB300), size: 24,
            )),
          ),
          const SizedBox(height: 8),
          Text('"$content"', style: AppTextStyles.bodyText.copyWith(
            fontSize: 14, fontStyle: FontStyle.italic, color: Colors.black87, height: 1.5,
          )),
        ],
      ),
    );
  }

  // ─── FEEDBACK FORM ──────────────────────────────────────────────────────
  Widget _buildFeedbackForm() {
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
          Row(children: [
            const Icon(Icons.rate_review_outlined, color: AppColors.primaryBlue, size: 18),
            const SizedBox(width: 8),
            _buildSectionLabel('BERI ULASAN'),
          ]),
          const SizedBox(height: 16),
          Text('Rating', style: AppTextStyles.label.copyWith(fontSize: 13, color: Colors.black87)),
          const SizedBox(height: 8),
          Row(
            children: List.generate(5, (i) {
              return GestureDetector(
                onTap: () => setState(() => _selectedRating = i + 1),
                child: Padding(
                  padding: const EdgeInsets.only(right: 4),
                  child: Icon(
                    i < _selectedRating ? Icons.star_rounded : Icons.star_outline_rounded,
                    color: const Color(0xFFFFB300), size: 32,
                  ),
                ),
              );
            }),
          ),
          const SizedBox(height: 16),
          Text('Ulasan', style: AppTextStyles.label.copyWith(fontSize: 13, color: Colors.black87)),
          const SizedBox(height: 8),
          Container(
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(14),
            ),
            child: TextField(
              controller: _feedbackController,
              maxLines: 3,
              style: AppTextStyles.inputText.copyWith(fontSize: 13),
              decoration: InputDecoration(
                hintText: 'Tulis ulasan Anda tentang penanganan...',
                hintStyle: AppTextStyles.inputText.copyWith(color: const Color(0xFFB0B0B0), fontSize: 13),
                border: InputBorder.none,
                contentPadding: const EdgeInsets.all(16),
              ),
            ),
          ),
          const SizedBox(height: 16),
          SizedBox(
            width: double.infinity, height: 48,
            child: ElevatedButton.icon(
              onPressed: _isSendingFeedback ? null : _handleSendFeedback,
              icon: _isSendingFeedback
                  ? const SizedBox(width: 18, height: 18, child: CircularProgressIndicator(strokeWidth: 2, valueColor: AlwaysStoppedAnimation<Color>(Colors.white)))
                  : const Icon(Icons.send_rounded, color: Colors.white, size: 18),
              label: Text('Kirim Ulasan', style: AppTextStyles.buttonText.copyWith(fontSize: 14)),
              style: ElevatedButton.styleFrom(
                backgroundColor: AppColors.primaryBlue,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                elevation: 0,
              ),
            ),
          ),
        ],
      ),
    );
  }

  // ─── STATUS TRACKING ────────────────────────────────────────────────────
  Widget _buildStatusTrackingCard(String apiStatus) {
    // Determine which steps are done based on API status
    final bool isPending = apiStatus == 'pending';
    final bool isVerified = apiStatus == 'verified';
    final bool isInProgress = apiStatus == 'in_progress';

    final steps = [
      {'label': 'Laporan Dikirim', 'done': true},
      {'label': 'Sedang Ditinjau', 'done': isVerified || isInProgress},
      {'label': 'Perbaikan Berlangsung', 'done': isInProgress},
      {'label': 'Selesai', 'done': false},
    ];

    // If pending, only first step is done
    if (isPending) {
      // already correct from defaults above
    }

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
          Row(children: [
            const Icon(Icons.track_changes_rounded, color: AppColors.primaryBlue, size: 18),
            const SizedBox(width: 8),
            _buildSectionLabel('PROGRES LAPORAN'),
          ]),
          const SizedBox(height: 16),
          ...steps.asMap().entries.map((entry) {
            final i = entry.key;
            final step = entry.value;
            final isDone = step['done'] as bool;
            final isLast = i == steps.length - 1;

            return Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Column(children: [
                  Container(
                    width: 24, height: 24,
                    decoration: BoxDecoration(
                      color: isDone ? AppColors.primaryBlue : const Color(0xFFDDDDDD),
                      shape: BoxShape.circle,
                    ),
                    child: Icon(
                      isDone ? Icons.check_rounded : Icons.circle_outlined,
                      color: Colors.white, size: 14,
                    ),
                  ),
                  if (!isLast) Container(
                    width: 2, height: 32,
                    color: isDone ? AppColors.primaryBlue.withValues(alpha: 0.3) : const Color(0xFFDDDDDD),
                  ),
                ]),
                const SizedBox(width: 14),
                Padding(
                  padding: const EdgeInsets.only(top: 3),
                  child: Text(
                    step['label'] as String,
                    style: AppTextStyles.label.copyWith(
                      fontSize: 14,
                      fontWeight: isDone ? FontWeight.w700 : FontWeight.w500,
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

  // ─── HELPERS ────────────────────────────────────────────────────────────
  Widget _buildSectionLabel(String text) {
    return Text(
      text,
      style: AppTextStyles.label.copyWith(
        fontSize: 11, fontWeight: FontWeight.w800,
        color: AppColors.primaryBlue, letterSpacing: 1.0,
      ),
    );
  }

  PreferredSizeWidget _buildAppBar(BuildContext context) {
    return AppBar(
      backgroundColor: Colors.white,
      elevation: 0,
      scrolledUnderElevation: 0,
      leading: GestureDetector(
        onTap: () => Navigator.pop(context),
        child: const Padding(
          padding: EdgeInsets.only(left: 12),
          child: Icon(Icons.chevron_left_rounded, color: AppColors.primaryBlue, size: 32),
        ),
      ),
      title: Text(
        'Detail Laporan',
        style: AppTextStyles.label.copyWith(
          fontSize: 18, fontWeight: FontWeight.w800, color: AppColors.primaryBlue,
        ),
      ),
      titleSpacing: 0,
      centerTitle: false,
    );
  }
}
