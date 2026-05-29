import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:image_picker/image_picker.dart';
import 'package:lapor_infrastruktur/theme/app_theme.dart';
import 'package:lapor_infrastruktur/services/api_service.dart';
import 'package:lapor_infrastruktur/services/location_service.dart';

class BuatLaporanScreen extends StatefulWidget {
  const BuatLaporanScreen({super.key});

  @override
  State<BuatLaporanScreen> createState() => _BuatLaporanScreenState();
}

class _BuatLaporanScreenState extends State<BuatLaporanScreen>
    with SingleTickerProviderStateMixin {
  // State
  int? _selectedKategoriId;
  final _deskripsiController = TextEditingController();
  bool _isLoading = false;
  bool _hasFile = false;
  String? _namaFile;
  Uint8List? _imageBytes;
  bool _deskrpsiFocused = false;

  // Animasi
  late AnimationController _animController;
  late Animation<double> _fadeAnim;
  late Animation<Offset> _slideAnim;

  // Lokasi (loaded from GPS)
  String _lokasi = 'Memuat lokasi...';
  String _koordinat = '...';
  double? _latitude;
  double? _longitude;
  bool _isLoadingLocation = true;

  // Kategori (otomatis dari API)
  List<Map<String, dynamic>> _kategoriList = [];
  bool _isLoadingKategori = true;

  // Daily limit
  int _remainingReports = 3;
  bool _isLimitReached = false;

  @override
  void initState() {
    super.initState();
    _animController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 600),
    );
    _fadeAnim = Tween<double>(
      begin: 0.0,
      end: 1.0,
    ).animate(CurvedAnimation(parent: _animController, curve: Curves.easeOut));
    _slideAnim = Tween<Offset>(
      begin: const Offset(0, 0.06),
      end: Offset.zero,
    ).animate(CurvedAnimation(parent: _animController, curve: Curves.easeOut));
    _animController.forward();
    _loadLocation();
    _loadCategories();
    _loadDailyLimit();
  }

  Future<void> _loadLocation() async {
    try {
      final location = await LocationService.getCurrentLocation();
      if (!mounted) return;
      setState(() {
        _lokasi = location.address;
        _koordinat = location.koordinat;
        _latitude = location.latitude;
        _longitude = location.longitude;
        _isLoadingLocation = false;
      });
    } catch (e) {
      if (!mounted) return;
      setState(() {
        _lokasi = 'Lokasi tidak tersedia';
        _koordinat = 'Izinkan akses lokasi di browser';
        _isLoadingLocation = false;
      });
    }
  }

  Future<void> _loadCategories() async {
    try {
      final categories = await ApiService.getCategories();
      if (!mounted) return;
      setState(() {
        _kategoriList = categories
            .map((c) => Map<String, dynamic>.from(c as Map))
            .toList();
        if (_kategoriList.isNotEmpty) {
          _selectedKategoriId = _kategoriList.first['id'] as int;
        }
        _isLoadingKategori = false;
      });
    } catch (e) {
      if (!mounted) return;
      setState(() => _isLoadingKategori = false);
      _showSnackBar(
        'Gagal memuat kategori: ${e.toString().replaceFirst("Exception: ", "")}',
        isError: true,
      );
    }
  }

  Future<void> _loadDailyLimit() async {
    try {
      final data = await ApiService.getDailyLimit();
      if (!mounted) return;
      setState(() {
        _remainingReports = data['remaining'] ?? 0;
        _isLimitReached = _remainingReports <= 0;
      });
    } catch (_) {
      // Jika endpoint gagal, biarkan default (3)
    }
  }

  @override
  void dispose() {
    _deskripsiController.dispose();
    _animController.dispose();
    super.dispose();
  }

  void _showSnackBar(String message, {bool isError = false}) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message, style: const TextStyle(color: Colors.white)),
        backgroundColor: isError ? Colors.redAccent : AppColors.primaryBlue,
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
      ),
    );
  }

  void _pickFile() async {
    final ImagePicker picker = ImagePicker();

    // Show options: Camera or Gallery
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
              title: Text(
                'Kamera',
                style: AppTextStyles.label.copyWith(fontSize: 16),
              ),
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
              title: Text(
                'Galeri',
                style: AppTextStyles.label.copyWith(fontSize: 16),
              ),
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

  void _handleKirimLaporan() async {
    if (_isLimitReached) {
      _showSnackBar(
        'Batas laporan harian sudah tercapai. Coba lagi besok.',
        isError: true,
      );
      return;
    }
    if (!_hasFile || _imageBytes == null) {
      _showSnackBar(
        'Harap pilih file bukti dokumen terlebih dahulu.',
        isError: true,
      );
      return;
    }
    if (_deskripsiController.text.trim().isEmpty) {
      _showSnackBar('Harap isi deskripsi laporan.', isError: true);
      return;
    }

    if (_latitude == null || _longitude == null) {
      _showSnackBar(
        'Lokasi belum tersedia. Harap izinkan akses lokasi.',
        isError: true,
      );
      return;
    }

    if (_selectedKategoriId == null) {
      _showSnackBar('Harap pilih kategori laporan.', isError: true);
      return;
    }

    setState(() => _isLoading = true);

    try {
      await ApiService.createReport(
        description: _deskripsiController.text.trim(),
        latitude: _latitude!,
        longitude: _longitude!,
        categoryId: _selectedKategoriId!,
        imageBytes: _imageBytes!,
        fileName: _namaFile ?? 'foto_laporan.jpg',
      );

      if (!mounted) return;
      // Tampilkan dialog sukses
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
              child: const Icon(
                Icons.check_circle_outline_rounded,
                color: Color(0xFF2E7D32),
                size: 40,
              ),
            ),
            const SizedBox(height: 16),
            Text(
              'Laporan Terkirim!',
              style: AppTextStyles.label.copyWith(
                fontSize: 18,
                fontWeight: FontWeight.w800,
                color: AppColors.textDark,
              ),
            ),
            const SizedBox(height: 8),
            Text(
              'Laporan Anda telah berhasil dikirim dan sedang diproses.',
              textAlign: TextAlign.center,
              style: AppTextStyles.appSubtitle.copyWith(fontSize: 13),
            ),
            const SizedBox(height: 24),
            SizedBox(
              width: double.infinity,
              height: 48,
              child: ElevatedButton(
                onPressed: () {
                  Navigator.pop(ctx); // tutup dialog
                  Navigator.pop(context); // kembali ke home
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primaryBlue,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                  elevation: 0,
                ),
                child: Text(
                  'Kembali ke Beranda',
                  style: AppTextStyles.buttonText,
                ),
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

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: _buildAppBar(),
      body: FadeTransition(
        opacity: _fadeAnim,
        child: SlideTransition(
          position: _slideAnim,
          child: SingleChildScrollView(
            physics: const BouncingScrollPhysics(),
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 20.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const SizedBox(height: 16),

                  // 1. Lokasi Card
                  _buildLokasiCard(),

                  const SizedBox(height: 24),

                  // 2. Bukti Dokumen
                  Text(
                    'Bukti Dokumen',
                    style: AppTextStyles.label.copyWith(fontSize: 15),
                  ),
                  const SizedBox(height: 10),
                  _buildUploadArea(),

                  const SizedBox(height: 24),

                  // 3. Kategori
                  Text(
                    'Kategori',
                    style: AppTextStyles.label.copyWith(fontSize: 15),
                  ),
                  const SizedBox(height: 10),
                  _buildKategoriDropdown(),

                  const SizedBox(height: 24),

                  // 4. Deskripsi
                  Text(
                    'Deskripsi',
                    style: AppTextStyles.label.copyWith(fontSize: 15),
                  ),
                  const SizedBox(height: 10),
                  _buildDeskripsiField(),

                  const SizedBox(height: 16),

                  // 5. Note
                  _buildNote(),

                  const SizedBox(height: 24),

                  // 6. Tombol Kirim
                  _buildKirimButton(),

                  const SizedBox(height: 32),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }

  // ─── APP BAR ──────────────────────────────────────────────────────────────
  PreferredSizeWidget _buildAppBar() {
    return AppBar(
      backgroundColor: AppColors.background,
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
        'Buat Laporan',
        style: AppTextStyles.label.copyWith(
          fontSize: 18,
          fontWeight: FontWeight.w700,
          color: AppColors.primaryBlue,
        ),
      ),
      titleSpacing: 0,
    );
  }

  // ─── LOKASI CARD ──────────────────────────────────────────────────────────
  Widget _buildLokasiCard() {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.05),
            blurRadius: 16,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Row(
        children: [
          Container(
            width: 40,
            height: 40,
            decoration: const BoxDecoration(
              color: AppColors.background,
              shape: BoxShape.circle,
            ),
            child: const Icon(
              Icons.location_on_rounded,
              color: AppColors.primaryBlue,
              size: 22,
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: [
                    Text(
                      'LOKASI SAAT INI',
                      style: AppTextStyles.appSubtitle.copyWith(
                        fontSize: 10,
                        letterSpacing: 0.8,
                        fontWeight: FontWeight.w700,
                        color: AppColors.primaryBlue,
                      ),
                    ),
                    if (_isLoadingLocation) ...[
                      const SizedBox(width: 8),
                      SizedBox(
                        width: 10,
                        height: 10,
                        child: CircularProgressIndicator(
                          strokeWidth: 1.5,
                          valueColor: AlwaysStoppedAnimation<Color>(
                            AppColors.primaryBlue,
                          ),
                        ),
                      ),
                    ],
                  ],
                ),
                const SizedBox(height: 3),
                Text(
                  _lokasi,
                  style: AppTextStyles.label.copyWith(
                    fontSize: 14,
                    color: AppColors.textDark,
                  ),
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                ),
                const SizedBox(height: 2),
                Text(
                  _koordinat,
                  style: AppTextStyles.appSubtitle.copyWith(fontSize: 12),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  // ─── UPLOAD AREA ──────────────────────────────────────────────────────────
  Widget _buildUploadArea() {
    return GestureDetector(
      onTap: _pickFile,
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 250),
        width: double.infinity,
        height: _hasFile && _imageBytes != null ? 200 : 130,
        decoration: BoxDecoration(
          color: _hasFile
              ? AppColors.primaryBlue.withValues(alpha: 0.06)
              : const Color(0xFFE8ECF5),
          borderRadius: BorderRadius.circular(14),
          border: Border.all(
            color: _hasFile ? AppColors.primaryBlue : const Color(0xFFCDD3E0),
            width: 1.5,
            style: BorderStyle.solid,
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
                  const Icon(
                    Icons.check_circle_rounded,
                    color: AppColors.primaryBlue,
                    size: 36,
                  ),
                  const SizedBox(height: 8),
                  Text(
                    _namaFile ?? '',
                    style: AppTextStyles.label.copyWith(
                      color: AppColors.primaryBlue,
                      fontSize: 13,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    'Ketuk untuk ganti file',
                    style: AppTextStyles.appSubtitle.copyWith(fontSize: 11),
                  ),
                ],
              )
            : Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(
                    Icons.cloud_upload_outlined,
                    color: AppColors.textGrey.withValues(alpha: 0.7),
                    size: 38,
                  ),
                  const SizedBox(height: 10),
                  Text(
                    'Pilih File Lampiran',
                    style: AppTextStyles.label.copyWith(
                      color: AppColors.textGrey,
                      fontSize: 13,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    'Format: JPG atau PNG (Maks. 5MB)',
                    style: AppTextStyles.appSubtitle.copyWith(fontSize: 11),
                  ),
                ],
              ),
      ),
    );
  }

  // ─── KATEGORI DROPDOWN ────────────────────────────────────────────────────
  Widget _buildKategoriDropdown() {
    if (_isLoadingKategori) {
      return Container(
        width: double.infinity,
        height: 52,
        padding: const EdgeInsets.symmetric(horizontal: 16),
        decoration: BoxDecoration(
          color: const Color(0xFFE8ECF5),
          borderRadius: BorderRadius.circular(14),
          border: Border.all(color: const Color(0xFFCDD3E0), width: 1.2),
        ),
        child: Row(
          children: [
            const SizedBox(
              width: 18, height: 18,
              child: CircularProgressIndicator(strokeWidth: 2),
            ),
            const SizedBox(width: 12),
            Text('Memuat kategori...', style: AppTextStyles.inputText.copyWith(color: AppColors.hintText)),
          ],
        ),
      );
    }

    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
      decoration: BoxDecoration(
        color: const Color(0xFFE8ECF5),
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: const Color(0xFFCDD3E0), width: 1.2),
      ),
      child: DropdownButtonHideUnderline(
        child: DropdownButton<int>(
          value: _selectedKategoriId,
          icon: const Icon(
            Icons.keyboard_arrow_down_rounded,
            color: AppColors.textDark,
            size: 24,
          ),
          style: AppTextStyles.inputText.copyWith(color: AppColors.textDark),
          dropdownColor: Colors.white,
          borderRadius: BorderRadius.circular(12),
          hint: Text('Pilih kategori', style: AppTextStyles.inputText.copyWith(color: AppColors.hintText)),
          onChanged: (value) => setState(() => _selectedKategoriId = value),
          items: _kategoriList.map((item) {
            return DropdownMenuItem<int>(
              value: item['id'] as int,
              child: Text(item['name'] as String, style: AppTextStyles.inputText),
            );
          }).toList(),
        ),
      ),
    );
  }

  // ─── DESKRIPSI FIELD ─────────────────────────────────────────────────────
  Widget _buildDeskripsiField() {
    return Focus(
      canRequestFocus: false,
      onFocusChange: (v) => setState(() => _deskrpsiFocused = v),
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        decoration: BoxDecoration(
          color: const Color(0xFFE8ECF5),
          borderRadius: BorderRadius.circular(14),
          border: Border.all(
            color: _deskrpsiFocused
                ? AppColors.inputActiveBorder
                : const Color(0xFFCDD3E0),
            width: _deskrpsiFocused ? 1.8 : 1.2,
          ),
        ),
        child: TextField(
          controller: _deskripsiController,
          maxLines: 5,
          textInputAction: TextInputAction.newline,
          style: AppTextStyles.inputText,
          decoration: InputDecoration(
            hintText: 'Jelaskan masalah infrastruktur secara detail...',
            hintStyle: AppTextStyles.inputText.copyWith(
              color: AppColors.hintText,
            ),
            border: InputBorder.none,
            contentPadding: const EdgeInsets.all(16),
          ),
        ),
      ),
    );
  }

  // ─── NOTE ─────────────────────────────────────────────────────────────────
  Widget _buildNote() {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.center,
      children: [
        Icon(
          _isLimitReached
              ? Icons.warning_amber_rounded
              : Icons.info_outline_rounded,
          color: _isLimitReached ? Colors.redAccent : AppColors.textGrey,
          size: 16,
        ),
        const SizedBox(width: 6),
        Text('Note: ', style: AppTextStyles.appSubtitle.copyWith(fontSize: 12)),
        Text(
          _isLimitReached
              ? 'Batas laporan harian tercapai'
              : 'Sisa $_remainingReports laporan hari ini',
          style: AppTextStyles.appSubtitle.copyWith(
            fontSize: 12,
            fontWeight: FontWeight.w700,
            color: _isLimitReached ? Colors.redAccent : AppColors.accentOrange,
          ),
        ),
      ],
    );
  }

  // ─── KIRIM BUTTON ─────────────────────────────────────────────────────────
  Widget _buildKirimButton() {
    return SizedBox(
      width: double.infinity,
      height: 54,
      child: DecoratedBox(
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(16),
          gradient: const LinearGradient(
            colors: [Color(0xFF1A3C9A), Color(0xFF2856C8)],
            begin: Alignment.centerLeft,
            end: Alignment.centerRight,
          ),
          boxShadow: [
            BoxShadow(
              color: AppColors.primaryBlue.withValues(alpha: 0.35),
              blurRadius: 14,
              offset: const Offset(0, 6),
            ),
          ],
        ),
        child: ElevatedButton(
          onPressed: _isLoading ? null : _handleKirimLaporan,
          style: ElevatedButton.styleFrom(
            backgroundColor: Colors.transparent,
            shadowColor: Colors.transparent,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(16),
            ),
          ),
          child: _isLoading
              ? const SizedBox(
                  width: 22,
                  height: 22,
                  child: CircularProgressIndicator(
                    strokeWidth: 2.5,
                    valueColor: AlwaysStoppedAnimation<Color>(Colors.white),
                  ),
                )
              : Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Text('Kirim Laporan', style: AppTextStyles.buttonText),
                    const SizedBox(width: 10),
                    const Icon(
                      Icons.send_rounded,
                      color: Colors.white,
                      size: 20,
                    ),
                  ],
                ),
        ),
      ),
    );
  }
}
