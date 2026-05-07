import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:lapor_infrastruktur/theme/app_theme.dart';

class BuatLaporanScreen extends StatefulWidget {
  const BuatLaporanScreen({super.key});

  @override
  State<BuatLaporanScreen> createState() => _BuatLaporanScreenState();
}

class _BuatLaporanScreenState extends State<BuatLaporanScreen>
    with SingleTickerProviderStateMixin {
  // State
  String? _selectedKategori = 'Kerusakan jalan';
  final _deskripsiController = TextEditingController();
  bool _isLoading = false;
  bool _hasFile = false;
  String? _namaFile;
  bool _deskrpsiFocused = false;

  // Animasi
  late AnimationController _animController;
  late Animation<double> _fadeAnim;
  late Animation<Offset> _slideAnim;

  // Data dummy
  final String _lokasi = 'Jl. Karanganyar-Matesih';
  final String _koordinat = '-7.534587, 110.838543';

  final List<String> _kategoriList = [
    'Kerusakan jalan',
    'Rambu lalu lintas dan marka jalan',
    'Lampu penerangan jalan',
    'Drainase',
    'Lainnya',
  ];

  @override
  void initState() {
    super.initState();
    _animController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 600),
    );
    _fadeAnim = Tween<double>(begin: 0.0, end: 1.0).animate(
      CurvedAnimation(parent: _animController, curve: Curves.easeOut),
    );
    _slideAnim = Tween<Offset>(
      begin: const Offset(0, 0.06),
      end: Offset.zero,
    ).animate(
      CurvedAnimation(parent: _animController, curve: Curves.easeOut),
    );
    _animController.forward();
  }

  @override
  void dispose() {
    _deskripsiController.dispose();
    _animController.dispose();
    super.dispose();
  }

  void _pickFile() {
    // Simulasi pilih file
    setState(() {
      _hasFile = true;
      _namaFile = 'foto_laporan.jpg';
    });
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: const Text('File berhasil dipilih: foto_laporan.jpg'),
        backgroundColor: AppColors.primaryBlue,
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
      ),
    );
  }

  void _handleKirimLaporan() async {
    if (!_hasFile) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: const Text('Harap pilih file bukti dokumen terlebih dahulu.'),
          backgroundColor: Colors.redAccent,
          behavior: SnackBarBehavior.floating,
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
        ),
      );
      return;
    }
    if (_deskripsiController.text.trim().isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: const Text('Harap isi deskripsi laporan.'),
          backgroundColor: Colors.redAccent,
          behavior: SnackBarBehavior.floating,
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
        ),
      );
      return;
    }

    setState(() => _isLoading = true);
    await Future.delayed(const Duration(seconds: 2));
    setState(() => _isLoading = false);

    if (mounted) {
      // Tampilkan dialog sukses
      showDialog(
        context: context,
        barrierDismissible: false,
        builder: (ctx) => _buildSuksesDialog(ctx),
      );
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
              decoration: BoxDecoration(
                color: const Color(0xFFDFF5E3),
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
                child: Text('Kembali ke Beranda', style: AppTextStyles.buttonText),
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
                  Text('Bukti Dokumen', style: AppTextStyles.label.copyWith(fontSize: 15)),
                  const SizedBox(height: 10),
                  _buildUploadArea(),

                  const SizedBox(height: 24),

                  // 3. Kategori
                  Text('Kategori', style: AppTextStyles.label.copyWith(fontSize: 15)),
                  const SizedBox(height: 10),
                  _buildKategoriDropdown(),

                  const SizedBox(height: 24),

                  // 4. Deskripsi
                  Text('Deskripsi', style: AppTextStyles.label.copyWith(fontSize: 15)),
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
                Text(
                  'LOKASI SAAT INI',
                  style: AppTextStyles.appSubtitle.copyWith(
                    fontSize: 10,
                    letterSpacing: 0.8,
                    fontWeight: FontWeight.w700,
                    color: AppColors.primaryBlue,
                  ),
                ),
                const SizedBox(height: 3),
                Text(
                  _lokasi,
                  style: AppTextStyles.label.copyWith(fontSize: 14, color: AppColors.textDark),
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
        height: 130,
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
        child: _hasFile
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
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
      decoration: BoxDecoration(
        color: const Color(0xFFE8ECF5),
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: const Color(0xFFCDD3E0), width: 1.2),
      ),
      child: DropdownButtonHideUnderline(
        child: DropdownButton<String>(
          value: _selectedKategori,
          icon: const Icon(
            Icons.keyboard_arrow_down_rounded,
            color: AppColors.textDark,
            size: 24,
          ),
          style: AppTextStyles.inputText.copyWith(color: AppColors.textDark),
          dropdownColor: Colors.white,
          borderRadius: BorderRadius.circular(12),
          onChanged: (value) => setState(() => _selectedKategori = value),
          items: _kategoriList.map((item) {
            return DropdownMenuItem<String>(
              value: item,
              child: Text(item, style: AppTextStyles.inputText),
            );
          }).toList(),
        ),
      ),
    );
  }

  // ─── DESKRIPSI FIELD ─────────────────────────────────────────────────────
  Widget _buildDeskripsiField() {
    return Focus(
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
          style: AppTextStyles.inputText,
          decoration: InputDecoration(
            hintText: 'Describe the issue in detail...',
            hintStyle: AppTextStyles.inputText.copyWith(color: AppColors.hintText),
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
        const Icon(
          Icons.info_outline_rounded,
          color: AppColors.textGrey,
          size: 16,
        ),
        const SizedBox(width: 6),
        Text(
          'Note: ',
          style: AppTextStyles.appSubtitle.copyWith(fontSize: 12),
        ),
        Text(
          'Max 3 reports per day',
          style: AppTextStyles.appSubtitle.copyWith(
            fontSize: 12,
            fontWeight: FontWeight.w700,
            color: AppColors.accentOrange,
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
