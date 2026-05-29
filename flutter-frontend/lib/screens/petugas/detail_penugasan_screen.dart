import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:image_picker/image_picker.dart';
import 'package:url_launcher/url_launcher.dart';
import 'package:lapor_infrastruktur/theme/app_theme.dart';
import 'package:lapor_infrastruktur/services/api_service.dart';

class DetailPenugasanScreen extends StatefulWidget {
  final Map<String, dynamic> penugasan;

  const DetailPenugasanScreen({super.key, required this.penugasan});

  @override
  State<DetailPenugasanScreen> createState() => _DetailPenugasanScreenState();
}

class _DetailPenugasanScreenState extends State<DetailPenugasanScreen> {
  final TextEditingController _catatanController = TextEditingController();
  bool _hasFile = false;
  String? _namaFile;
  Uint8List? _imageBytes;
  bool _isLoading = false;
  late String _currentStatus;

  @override
  void initState() {
    super.initState();
    _currentStatus = widget.penugasan['status'] ?? 'DIPROSES';
  }

  @override
  void dispose() {
    _catatanController.dispose();
    super.dispose();
  }

  // ── Status Colors ────────────────────────────────────────────────────────────
  Map<String, Color> _getStatusColors(String status) {
    switch (status) {
      case 'DIKERJAKAN':
        return {'bg': const Color(0xFFD6E4FF), 'text': const Color(0xFF0044C4)};
      case 'DIPROSES':
        return {'bg': const Color(0xFFFCE4CA), 'text': const Color(0xFF8D4F00)};
      case 'SELESAI':
        return {'bg': const Color(0xFFA6FA96), 'text': const Color(0xFF1B5E20)};
      case 'DIAJUKAN':
        return {'bg': const Color(0xFFEBEBEB), 'text': const Color(0xFF333333)};
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

  void _pickFile() {
    final ImagePicker picker = ImagePicker();

    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      builder: (ctx) => Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Text(
              'Pilih Sumber Gambar',
              style: AppTextStyles.label.copyWith(fontSize: 18),
            ),
            const SizedBox(height: 20),
            ListTile(
              leading: Container(
                width: 44,
                height: 44,
                decoration: BoxDecoration(
                  color: const Color(0xFFE8ECF5),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: const Icon(
                  Icons.camera_alt_rounded,
                  color: AppColors.primaryBlue,
                ),
              ),
              title: Text('Kamera', style: AppTextStyles.label.copyWith(fontSize: 16)),
              onTap: () {
                Navigator.pop(ctx);
                _pickFromSource(picker, ImageSource.camera);
              },
            ),
            ListTile(
              leading: Container(
                width: 44,
                height: 44,
                decoration: BoxDecoration(
                  color: const Color(0xFFE8ECF5),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: const Icon(
                  Icons.photo_library_rounded,
                  color: AppColors.primaryBlue,
                ),
              ),
              title: Text('Galeri', style: AppTextStyles.label.copyWith(fontSize: 16)),
              onTap: () {
                Navigator.pop(ctx);
                _pickFromSource(picker, ImageSource.gallery);
              },
            ),
            const SizedBox(height: 8),
          ],
        ),
      ),
    );
  }

  void _pickFromSource(ImagePicker picker, ImageSource source) async {
    try {
      final XFile? image = await picker.pickImage(
        source: source,
        maxWidth: 1920,
        maxHeight: 1080,
        imageQuality: 85,
      );
      if (image != null) {
        final bytes = await image.readAsBytes();
        setState(() {
          _hasFile = true;
          _namaFile = image.name;
          _imageBytes = bytes;
        });
        _showSnackBar('File berhasil dipilih: ${image.name}');
      }
    } catch (e) {
      _showSnackBar('Gagal memilih file: ${e.toString()}', isError: true);
    }
  }

  void _handleMulaiPengerjaan() async {
    final reportId = widget.penugasan['id'];
    if (reportId == null) {
      _showSnackBar('ID laporan tidak ditemukan.', isError: true);
      return;
    }

    setState(() => _isLoading = true);

    try {
      await ApiService.updateReportStatus(
        reportId: reportId,
        status: 'in_progress',
      );
      if (!mounted) return;
      setState(() => _currentStatus = 'DIKERJAKAN');
      _showSnackBar('Status berhasil diperbarui ke DIKERJAKAN');
    } catch (e) {
      if (!mounted) return;
      _showSnackBar(
        e.toString().replaceFirst('Exception: ', ''),
        isError: true,
      );
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  void _handleKonfirmasiSelesai() async {
    if (!_hasFile || _imageBytes == null) {
      _showSnackBar('Harap unggah bukti perbaikan terlebih dahulu.', isError: true);
      return;
    }

    final reportId = widget.penugasan['id'];
    if (reportId == null) {
      _showSnackBar('ID laporan tidak ditemukan.', isError: true);
      return;
    }

    setState(() => _isLoading = true);

    try {
      // 1. Kirim progres pekerjaan dengan catatan
      final catatan = _catatanController.text.trim();
      if (catatan.isNotEmpty) {
        await ApiService.addWorkProgress(
          reportId: reportId,
          note: catatan,
        );
      }

      // 2. Update status ke resolved
      await ApiService.updateReportStatus(
        reportId: reportId,
        status: 'resolved',
      );

      if (!mounted) return;
      setState(() => _currentStatus = 'SELESAI');

      showDialog(
        context: context,
        barrierDismissible: false,
        builder: (ctx) => _buildSuksesDialog(ctx),
      );
    } catch (e) {
      if (!mounted) return;
      _showSnackBar(
        e.toString().replaceFirst('Exception: ', ''),
        isError: true,
      );
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  Widget _buildSuksesDialog(BuildContext ctx) {
    return Dialog(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
      child: Padding(
        padding: const EdgeInsets.all(28),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              width: 72,
              height: 72,
              decoration: const BoxDecoration(
                color: Color(0xFFDFF5E3),
                shape: BoxShape.circle,
              ),
              child: const Icon(Icons.check_circle_outline_rounded,
                  color: Color(0xFF2E7D32), size: 40),
            ),
            const SizedBox(height: 16),
            Text(
              'Tugas Selesai!',
              style: AppTextStyles.label.copyWith(
                fontSize: 18,
                fontWeight: FontWeight.w800,
                color: AppColors.textDark,
              ),
            ),
            const SizedBox(height: 8),
            Text(
              'Laporan telah dikonfirmasi selesai dan menunggu verifikasi pelapor.',
              textAlign: TextAlign.center,
              style: AppTextStyles.appSubtitle.copyWith(fontSize: 13),
            ),
            const SizedBox(height: 24),
            SizedBox(
              width: double.infinity,
              height: 48,
              child: ElevatedButton(
                onPressed: () {
                  Navigator.pop(ctx);
                  Navigator.pop(context);
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primaryBlue,
                  shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(12)),
                  elevation: 0,
                ),
                child: Text('Kembali', style: AppTextStyles.buttonText),
              ),
            ),
          ],
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    SystemChrome.setSystemUIOverlayStyle(
      const SystemUiOverlayStyle(
        statusBarColor: Colors.transparent,
        statusBarIconBrightness: Brightness.dark,
      ),
    );

    final String status = _currentStatus;
    final String kategori = widget.penugasan['kategori'] ?? '-';
    final String tanggal = widget.penugasan['tanggal'] ?? '-';
    final String lokasiNama = widget.penugasan['lokasi_nama'] ?? '-';
    final String koordinat = widget.penugasan['koordinat'] ?? '';
    final String deskripsi = widget.penugasan['deskripsi'] ?? '-';
    final String? catatanAdmin = widget.penugasan['catatan_admin'];
    final bool isSelesai = status == 'SELESAI';
    final bool isDikerjakan = status == 'DIKERJAKAN';
    final bool isDiproses = status == 'DIPROSES';

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

              // ── Status + Kategori ──
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                decoration: BoxDecoration(
                  color: statusColors['bg'],
                  borderRadius: BorderRadius.circular(20),
                ),
                child: Text(
                  'STATUS SAAT INI: $status',
                  style: AppTextStyles.label.copyWith(
                    fontSize: 11,
                    fontWeight: FontWeight.w800,
                    color: statusColors['text'],
                    letterSpacing: 0.5,
                  ),
                ),
              ),

              const SizedBox(height: 10),

              Text(
                kategori,
                style: AppTextStyles.label.copyWith(
                  fontSize: 20,
                  fontWeight: FontWeight.w800,
                  color: Colors.black87,
                ),
              ),
              const SizedBox(height: 4),
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

              const SizedBox(height: 20),

              // ── Catatan Admin (jika ada) ──
              if (catatanAdmin != null && catatanAdmin.isNotEmpty) ...[
                _buildSectionLabel('CATATAN ADMIN'),
                const SizedBox(height: 10),
                Container(
                  width: double.infinity,
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(
                    color: const Color(0xFFF3F4F6),
                    borderRadius: BorderRadius.circular(16),
                  ),
                  child: Text(
                    '"$catatanAdmin"',
                    style: AppTextStyles.bodyText.copyWith(
                      fontSize: 13,
                      fontStyle: FontStyle.italic,
                      color: Colors.black87,
                      height: 1.5,
                    ),
                  ),
                ),
                const SizedBox(height: 16),
              ],

              // ── Tombol Mulai Pengerjaan (hanya jika DIPROSES) ──
              if (isDiproses) ...[
                SizedBox(
                  width: double.infinity,
                  height: 52,
                  child: ElevatedButton.icon(
                    onPressed: _isLoading ? null : _handleMulaiPengerjaan,
                    icon: const Icon(Icons.play_arrow_rounded,
                        color: Colors.white, size: 22),
                    label: Text(
                      'Mulai Pengerjaan',
                      style: AppTextStyles.buttonText.copyWith(fontSize: 15),
                    ),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.primaryBlue,
                      shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(14)),
                      elevation: 0,
                    ),
                  ),
                ),
                const SizedBox(height: 24),
              ],

              // ── Bukti Dokumentasi (Pelapor) ──
              _buildSectionLabel('BUKTI DOKUMENTASI'),
              const SizedBox(height: 12),
              Container(
                height: 200,
                width: double.infinity,
                decoration: BoxDecoration(
                  color: const Color(0xFFE8ECF5),
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: const Color(0xFFCDD3E0), width: 2),
                ),
                child: ClipRRect(
                  borderRadius: BorderRadius.circular(14),
                  child: widget.penugasan['foto_url'] != null
                      ? Image.network(
                          '${ApiService.baseUrl.replaceAll('/api', '')}${widget.penugasan['foto_url']}',
                          fit: BoxFit.cover,
                          width: double.infinity,
                          loadingBuilder: (context, child, loadingProgress) {
                            if (loadingProgress == null) return child;
                            return const Center(
                              child: CircularProgressIndicator(
                                color: AppColors.primaryBlue,
                              ),
                            );
                          },
                          errorBuilder: (context, error, stackTrace) {
                            return const Center(
                              child: Icon(
                                Icons.broken_image_outlined,
                                size: 60,
                                color: Color(0xFF9E9E9E),
                              ),
                            );
                          },
                        )
                      : const Center(
                          child: Icon(Icons.image_outlined,
                              size: 60, color: Color(0xFF9E9E9E)),
                        ),
                ),
              ),

              const SizedBox(height: 24),

              // ── Detail Kejadian ──
              _buildSectionLabel('DETAIL KEJADIAN'),
              const SizedBox(height: 10),
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: const Color(0xFFE2E2E2)),
                ),
                child: Text(
                  deskripsi,
                  style: AppTextStyles.bodyText.copyWith(
                    fontSize: 13,
                    color: Colors.black87,
                    height: 1.6,
                  ),
                ),
              ),

              const SizedBox(height: 24),

              // ── Lokasi Kejadian ──
              _buildSectionLabel('LOKASI KEJADIAN'),
              const SizedBox(height: 10),
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: const Color(0xFFE2E2E2)),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Container(
                          width: 40,
                          height: 40,
                          decoration: const BoxDecoration(
                            color: Color(0xFFE8ECF5),
                            shape: BoxShape.circle,
                          ),
                          child: const Icon(Icons.location_on_rounded,
                              color: AppColors.primaryBlue, size: 22),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                lokasiNama,
                                style: AppTextStyles.label.copyWith(
                                  fontSize: 14,
                                  fontWeight: FontWeight.w700,
                                  color: Colors.black87,
                                ),
                              ),
                              if (koordinat.isNotEmpty) ...[
                                const SizedBox(height: 2),
                                Text(
                                  koordinat,
                                  style: AppTextStyles.bodyText.copyWith(
                                    fontSize: 12,
                                    color: const Color(0xFF7A7A7A),
                                  ),
                                ),
                              ],
                            ],
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 14),
                    SizedBox(
                      width: double.infinity,
                      height: 44,
                      child: OutlinedButton.icon(
                        onPressed: () async {
                          final coords = koordinat.split(',');
                          if (coords.length == 2) {
                            final lat = coords[0].trim();
                            final lng = coords[1].trim();
                            final Uri mapsUri = Uri.parse(
                              'https://www.google.com/maps/search/?api=1&query=$lat,$lng',
                            );
                            try {
                              await launchUrl(mapsUri, mode: LaunchMode.externalApplication);
                            } catch (_) {
                              if (context.mounted) {
                                ScaffoldMessenger.of(context).showSnackBar(
                                  SnackBar(
                                    content: Text('Lokasi: $koordinat'),
                                    backgroundColor: AppColors.primaryBlue,
                                    behavior: SnackBarBehavior.floating,
                                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                                  ),
                                );
                              }
                            }
                          }
                        },
                        icon: const Icon(Icons.map_outlined,
                            color: AppColors.primaryBlue, size: 18),
                        label: Text(
                          'Buka Google Maps',
                          style: AppTextStyles.label.copyWith(
                            fontSize: 13,
                            color: AppColors.primaryBlue,
                          ),
                        ),
                        style: OutlinedButton.styleFrom(
                          side: const BorderSide(
                              color: AppColors.primaryBlue, width: 1.5),
                          shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(12)),
                        ),
                      ),
                    ),
                  ],
                ),
              ),

              const SizedBox(height: 24),

              // ── Form Detail Pekerjaan (hanya jika DIKERJAKAN atau DIPROSES) ──
              if (isDikerjakan || isDiproses) ...[
                _buildSectionLabel('DETAIL PEKERJAAN'),
                const SizedBox(height: 10),
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
                      // Upload Bukti
                      Text(
                        'Unggah Bukti Perbaikan',
                        style: AppTextStyles.label.copyWith(
                          fontSize: 14,
                          color: const Color(0xFF333333),
                        ),
                      ),
                      const SizedBox(height: 10),
                      GestureDetector(
                        onTap: _pickFile,
                        child: AnimatedContainer(
                          duration: const Duration(milliseconds: 200),
                          height: _hasFile && _imageBytes != null ? 180 : 110,
                          width: double.infinity,
                          decoration: BoxDecoration(
                            color: _hasFile
                                ? AppColors.primaryBlue.withValues(alpha: 0.07)
                                : Colors.white,
                            borderRadius: BorderRadius.circular(14),
                            border: Border.all(
                              color: _hasFile
                                  ? AppColors.primaryBlue
                                  : const Color(0xFFCDD3E0),
                              width: 1.5,
                            ),
                          ),
                          child: _hasFile && _imageBytes != null
                              ? ClipRRect(
                                  borderRadius: BorderRadius.circular(12),
                                  child: Stack(
                                    fit: StackFit.expand,
                                    children: [
                                      Image.memory(_imageBytes!, fit: BoxFit.cover),
                                      Positioned(
                                        bottom: 0,
                                        left: 0,
                                        right: 0,
                                        child: Container(
                                          padding: const EdgeInsets.symmetric(vertical: 8),
                                          decoration: BoxDecoration(
                                            gradient: LinearGradient(
                                              begin: Alignment.topCenter,
                                              end: Alignment.bottomCenter,
                                              colors: [
                                                Colors.transparent,
                                                Colors.black.withValues(alpha: 0.6),
                                              ],
                                            ),
                                          ),
                                          child: Text(
                                            'Ketuk untuk ganti file',
                                            textAlign: TextAlign.center,
                                            style: AppTextStyles.appSubtitle.copyWith(
                                              fontSize: 12,
                                              color: Colors.white,
                                            ),
                                          ),
                                        ),
                                      ),
                                    ],
                                  ),
                                )
                              : _hasFile
                              ? Column(
                                  mainAxisAlignment: MainAxisAlignment.center,
                                  children: [
                                    const Icon(Icons.check_circle_rounded,
                                        color: AppColors.primaryBlue, size: 32),
                                    const SizedBox(height: 6),
                                    Text(
                                      _namaFile ?? '',
                                      style: AppTextStyles.label.copyWith(
                                          color: AppColors.primaryBlue,
                                          fontSize: 13),
                                    ),
                                    Text(
                                      'Ketuk untuk ganti',
                                      style: AppTextStyles.appSubtitle
                                          .copyWith(fontSize: 11),
                                    ),
                                  ],
                                )
                              : Column(
                                  mainAxisAlignment: MainAxisAlignment.center,
                                  children: [
                                    Icon(
                                      Icons.cloud_upload_outlined,
                                      color: AppColors.textGrey
                                          .withValues(alpha: 0.7),
                                      size: 34,
                                    ),
                                    const SizedBox(height: 8),
                                    Text(
                                      'Pilih File Lampiran',
                                      style: AppTextStyles.label.copyWith(
                                          color: AppColors.textGrey,
                                          fontSize: 13),
                                    ),
                                    Text(
                                      'Format: JPG atau PNG (Maks. 5MB)',
                                      style: AppTextStyles.appSubtitle
                                          .copyWith(fontSize: 11),
                                    ),
                                  ],
                                ),
                        ),
                      ),

                      const SizedBox(height: 16),

                      // Catatan Penyelesaian
                      Text(
                        'Catatan Penyelesaian',
                        style: AppTextStyles.label.copyWith(
                          fontSize: 14,
                          color: const Color(0xFF333333),
                        ),
                      ),
                      const SizedBox(height: 10),
                      Container(
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(14),
                        ),
                        child: TextField(
                          controller: _catatanController,
                          maxLines: 4,
                          style: AppTextStyles.inputText.copyWith(fontSize: 13),
                          decoration: InputDecoration(
                            hintText: 'Jelaskan tindakan yang telah\ndilakukan...',
                            hintStyle: AppTextStyles.inputText.copyWith(
                              color: const Color(0xFFB0B0B0),
                              fontSize: 13,
                            ),
                            border: InputBorder.none,
                            contentPadding: const EdgeInsets.all(16),
                          ),
                        ),
                      ),

                      const SizedBox(height: 20),

                      // Tombol Konfirmasi Selesai
                      SizedBox(
                        width: double.infinity,
                        height: 52,
                        child: ElevatedButton.icon(
                          onPressed:
                              _isLoading ? null : _handleKonfirmasiSelesai,
                          icon: _isLoading
                              ? const SizedBox(
                                  width: 20,
                                  height: 20,
                                  child: CircularProgressIndicator(
                                    strokeWidth: 2.5,
                                    valueColor: AlwaysStoppedAnimation<Color>(
                                        Colors.white),
                                  ),
                                )
                              : const Icon(Icons.check_circle_outline_rounded,
                                  color: Colors.white, size: 22),
                          label: Text(
                            'Konfirmasi Selesai',
                            style:
                                AppTextStyles.buttonText.copyWith(fontSize: 15),
                          ),
                          style: ElevatedButton.styleFrom(
                            backgroundColor: AppColors.primaryBlue,
                            shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(14)),
                            elevation: 0,
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 16),
              ],

              // ── Tampilkan hasil jika SELESAI ──
              if (isSelesai) ...[
                _buildSectionLabel('LAPORAN PENYELESAIAN'),
                const SizedBox(height: 10),
                Container(
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
                      Row(
                        children: [
                          const Icon(Icons.verified_rounded,
                              color: Color(0xFF2E7D32), size: 18),
                          const SizedBox(width: 8),
                          Text(
                            'Tugas Telah Selesai Dikerjakan',
                            style: AppTextStyles.label.copyWith(
                              fontSize: 13,
                              color: const Color(0xFF1B5E20),
                              fontWeight: FontWeight.w700,
                            ),
                          ),
                        ],
                      ),
                      if (widget.penugasan['respon'] != null) ...[
                        const SizedBox(height: 12),
                        Text(
                          '"${widget.penugasan['respon']}"',
                          style: AppTextStyles.bodyText.copyWith(
                            fontSize: 13,
                            fontStyle: FontStyle.italic,
                            color: Colors.black87,
                            height: 1.5,
                          ),
                        ),
                      ],
                      const SizedBox(height: 12),
                      Row(
                        children: [
                          Container(
                            width: 32,
                            height: 32,
                            decoration: const BoxDecoration(
                              color: Color(0xFFD6E4FF),
                              shape: BoxShape.circle,
                            ),
                            child: const Icon(Icons.person_outline_rounded,
                                color: AppColors.primaryBlue, size: 18),
                          ),
                          const SizedBox(width: 10),
                          Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                widget.penugasan['petugas_nama'] ?? '-',
                                style: AppTextStyles.label.copyWith(
                                    fontSize: 13, color: Colors.black87),
                              ),
                              Text(
                                widget.penugasan['petugas_waktu'] ?? '-',
                                style: AppTextStyles.bodyText.copyWith(
                                    fontSize: 11,
                                    color: const Color(0xFF7A7A7A)),
                              ),
                            ],
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 16),
              ],

              // ── Rating dari Pelapor (jika SELESAI dan ada feedback) ──
              if (isSelesai) ...[
                _buildUserFeedbackSection(),
                const SizedBox(height: 16),
              ],

              const SizedBox(height: 40),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildUserFeedbackSection() {
    final feedbacks = (widget.penugasan['feedbacks'] as List?) ?? [];
    if (feedbacks.isEmpty) {
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
              const Icon(Icons.star_outline_rounded, color: Color(0xFFFFB300), size: 18),
              const SizedBox(width: 8),
              _buildSectionLabel('RATING PENGGUNA'),
            ]),
            const SizedBox(height: 12),
            Text(
              'Pelapor belum memberikan ulasan.',
              style: AppTextStyles.bodyText.copyWith(
                fontSize: 13, color: const Color(0xFF7A7A7A), fontStyle: FontStyle.italic,
              ),
            ),
          ],
        ),
      );
    }

    final feedback = feedbacks.first as Map<String, dynamic>;
    final rating = feedback['rating'] as int? ?? 0;
    final content = feedback['content'] as String? ?? '';
    final userName = feedback['user']?['name'] ?? 'Pelapor';

    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: const Color(0xFFFFFBF0),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: const Color(0xFFFFE082)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(children: [
            const Icon(Icons.star_rounded, color: Color(0xFFFFB300), size: 20),
            const SizedBox(width: 8),
            _buildSectionLabel('RATING PENGGUNA'),
          ]),
          const SizedBox(height: 12),
          Row(
            children: [
              ...List.generate(5, (i) => Icon(
                i < rating ? Icons.star_rounded : Icons.star_outline_rounded,
                color: const Color(0xFFFFB300), size: 24,
              )),
              const SizedBox(width: 8),
              Text(
                '$rating/5',
                style: AppTextStyles.label.copyWith(
                  fontSize: 14, fontWeight: FontWeight.w700, color: const Color(0xFF8D6E00),
                ),
              ),
            ],
          ),
          if (content.isNotEmpty) ...[
            const SizedBox(height: 12),
            Text(
              '"$content"',
              style: AppTextStyles.bodyText.copyWith(
                fontSize: 14, fontStyle: FontStyle.italic, color: Colors.black87, height: 1.5,
              ),
            ),
          ],
          const SizedBox(height: 12),
          Row(children: [
            Container(
              width: 28, height: 28,
              decoration: const BoxDecoration(color: Color(0xFFD6E4FF), shape: BoxShape.circle),
              child: const Icon(Icons.person_outline_rounded, color: AppColors.primaryBlue, size: 16),
            ),
            const SizedBox(width: 8),
            Text(
              userName,
              style: AppTextStyles.label.copyWith(fontSize: 12, color: const Color(0xFF555555)),
            ),
          ]),
        ],
      ),
    );
  }

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

  PreferredSizeWidget _buildAppBar(BuildContext context) {
    return AppBar(
      backgroundColor: Colors.white,
      elevation: 0,
      scrolledUnderElevation: 0,
      leading: GestureDetector(
        onTap: () => Navigator.pop(context),
        child: const Padding(
          padding: EdgeInsets.only(left: 12),
          child: Icon(Icons.chevron_left_rounded,
              color: AppColors.primaryBlue, size: 32),
        ),
      ),
      title: Text(
        'Detail Penugasan',
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
