import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:lapor_infrastruktur/theme/app_theme.dart';

class KeamananScreen extends StatefulWidget {
  const KeamananScreen({super.key});

  @override
  State<KeamananScreen> createState() => _KeamananScreenState();
}

class _KeamananScreenState extends State<KeamananScreen> {
  final _oldPasswordController = TextEditingController();
  final _newPasswordController = TextEditingController();
  final _confirmPasswordController = TextEditingController();

  @override
  void dispose() {
    _oldPasswordController.dispose();
    _newPasswordController.dispose();
    _confirmPasswordController.dispose();
    super.dispose();
  }

  void _handleSimpanPerubahan() {
    // Tampilkan snackbar sukses
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: const Text('Kata sandi berhasil diubah.'),
        backgroundColor: Colors.green,
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
      ),
    );
    Navigator.pop(context);
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
      backgroundColor: Colors.white,
      appBar: _buildAppBar(context),
      body: SafeArea(
        child: SingleChildScrollView(
          physics: const BouncingScrollPhysics(),
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 24.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const SizedBox(height: 24),
                
                // Judul
                Text(
                  'Ubah Kata Sandi',
                  style: AppTextStyles.label.copyWith(
                    fontSize: 18,
                    fontWeight: FontWeight.w800,
                    color: Colors.black87,
                  ),
                ),
                
                const SizedBox(height: 8),
                
                // Subjudul (Memperbaiki typo "bar" menjadi "baru" dari mockup)
                Text(
                  'Silakan masukkan kata sandi lama Anda dan buat kata sandi baru yang kuat untuk melindungi akses akun Anda.',
                  style: AppTextStyles.bodyText.copyWith(
                    fontSize: 13,
                    color: const Color(0xFF7A7A7A),
                    height: 1.4,
                  ),
                ),

                const SizedBox(height: 32),

                // Form Kata Sandi Lama
                _buildPasswordField('Kata Sandi Lama', _oldPasswordController),
                const SizedBox(height: 20),

                // Form Kata Sandi Baru
                // (Di mockup tertulis "Kata Sandi Lama" lagi akibat copas, disesuaikan agar logis)
                _buildPasswordField('Kata Sandi Baru', _newPasswordController),
                const SizedBox(height: 20),

                // Form Konfirmasi Kata Sandi Baru
                _buildPasswordField('Konfirmasi Kata Sandi', _confirmPasswordController),
                const SizedBox(height: 40),

                // Tombol Simpan
                _buildSimpanButton(),
                
                const SizedBox(height: 40),
              ],
            ),
          ),
        ),
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
          child: Icon(
            Icons.chevron_left_rounded,
            color: AppColors.primaryBlue,
            size: 32,
          ),
        ),
      ),
      title: Text(
        'Keamanan',
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

  Widget _buildPasswordField(String label, TextEditingController controller) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: AppTextStyles.label.copyWith(
            fontSize: 15,
            color: Colors.black87,
            fontWeight: FontWeight.w700,
          ),
        ),
        const SizedBox(height: 10),
        Container(
          height: 54,
          decoration: BoxDecoration(
            color: const Color(0xFFE2E2E2), // Light grey background like mockup
            borderRadius: BorderRadius.circular(14),
          ),
          child: TextField(
            controller: controller,
            obscureText: true,
            style: AppTextStyles.inputText,
            decoration: InputDecoration(
              hintText: '**********',
              hintStyle: AppTextStyles.inputText.copyWith(
                color: const Color(0xFF9E9E9E),
                letterSpacing: 2.0,
              ),
              border: InputBorder.none,
              contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildSimpanButton() {
    return SizedBox(
      width: double.infinity,
      height: 54,
      child: DecoratedBox(
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(16),
          color: const Color(0xFF0044C4), // Solid blue from mockup
          boxShadow: [
            BoxShadow(
              color: const Color(0xFF0044C4).withValues(alpha: 0.3),
              blurRadius: 16,
              offset: const Offset(0, 8),
            ),
          ],
        ),
        child: ElevatedButton(
          onPressed: _handleSimpanPerubahan,
          style: ElevatedButton.styleFrom(
            backgroundColor: Colors.transparent,
            shadowColor: Colors.transparent,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(16),
            ),
          ),
          child: Text(
            'Simpan Perubahan',
            style: AppTextStyles.buttonText.copyWith(
              fontSize: 16,
            ),
          ),
        ),
      ),
    );
  }
}
