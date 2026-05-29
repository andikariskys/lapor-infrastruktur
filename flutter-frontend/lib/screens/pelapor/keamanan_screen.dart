import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:lapor_infrastruktur/theme/app_theme.dart';
import 'package:lapor_infrastruktur/services/api_service.dart';

class KeamananScreen extends StatefulWidget {
  const KeamananScreen({super.key});

  @override
  State<KeamananScreen> createState() => _KeamananScreenState();
}

class _KeamananScreenState extends State<KeamananScreen> {
  final _oldPasswordController = TextEditingController();
  final _newPasswordController = TextEditingController();
  final _confirmPasswordController = TextEditingController();
  bool _isLoading = false;
  bool _obscureOld = true;
  bool _obscureNew = true;
  bool _obscureConfirm = true;

  bool _oldPasswordFocused = false;
  bool _newPasswordFocused = false;
  bool _confirmPasswordFocused = false;

  @override
  void dispose() {
    _oldPasswordController.dispose();
    _newPasswordController.dispose();
    _confirmPasswordController.dispose();
    super.dispose();
  }

  void _showSnackBar(String message, {bool isError = false}) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message, style: const TextStyle(color: Colors.white)),
        backgroundColor: isError ? Colors.redAccent : Colors.green,
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
      ),
    );
  }

  void _handleSimpanPerubahan() async {
    final oldPass = _oldPasswordController.text.trim();
    final newPass = _newPasswordController.text.trim();
    final confirmPass = _confirmPasswordController.text.trim();

    // ── Validation ──────────────────────────────────────────────────────────
    if (oldPass.isEmpty || newPass.isEmpty || confirmPass.isEmpty) {
      _showSnackBar('Harap isi semua field.', isError: true);
      return;
    }
    if (newPass.length < 6) {
      _showSnackBar('Kata sandi baru minimal 6 karakter.', isError: true);
      return;
    }
    if (newPass != confirmPass) {
      _showSnackBar('Konfirmasi kata sandi tidak cocok.', isError: true);
      return;
    }
    if (oldPass == newPass) {
      _showSnackBar('Kata sandi baru harus berbeda dari yang lama.', isError: true);
      return;
    }

    setState(() => _isLoading = true);

    try {
      await ApiService.changePassword(
        oldPassword: oldPass,
        newPassword: newPass,
      );
      if (!mounted) return;
      _showSnackBar('Kata sandi berhasil diubah.');
      Navigator.pop(context);
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
                
                // Subjudul
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
                _buildPasswordField(
                  'Kata Sandi Lama',
                  _oldPasswordController,
                  _obscureOld,
                  () => setState(() => _obscureOld = !_obscureOld),
                  _oldPasswordFocused,
                  (hasFocus) => setState(() => _oldPasswordFocused = hasFocus),
                ),
                const SizedBox(height: 20),

                // Form Kata Sandi Baru
                _buildPasswordField(
                  'Kata Sandi Baru',
                  _newPasswordController,
                  _obscureNew,
                  () => setState(() => _obscureNew = !_obscureNew),
                  _newPasswordFocused,
                  (hasFocus) => setState(() => _newPasswordFocused = hasFocus),
                ),
                const SizedBox(height: 20),

                // Form Konfirmasi Kata Sandi Baru
                _buildPasswordField(
                  'Konfirmasi Kata Sandi',
                  _confirmPasswordController,
                  _obscureConfirm,
                  () => setState(() => _obscureConfirm = !_obscureConfirm),
                  _confirmPasswordFocused,
                  (hasFocus) => setState(() => _confirmPasswordFocused = hasFocus),
                ),
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

  Widget _buildPasswordField(
    String label,
    TextEditingController controller,
    bool obscure,
    VoidCallback toggleObscure,
    bool isFocused,
    ValueChanged<bool> onFocusChange,
  ) {
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
        Focus(
          canRequestFocus: false,
          onFocusChange: onFocusChange,
          child: Container(
            height: 54,
            decoration: BoxDecoration(
              color: const Color(0xFFE2E2E2),
              borderRadius: BorderRadius.circular(14),
            ),
            child: TextField(
              controller: controller,
              obscureText: obscure,
              style: AppTextStyles.inputText,
              decoration: InputDecoration(
                hintText: isFocused ? '' : '**********',
                hintStyle: AppTextStyles.inputText.copyWith(
                  color: const Color(0xFF9E9E9E),
                  letterSpacing: 2.0,
                ),
                suffixIcon: GestureDetector(
                  onTap: toggleObscure,
                  child: Icon(
                    obscure
                        ? Icons.visibility_off_outlined
                        : Icons.visibility_outlined,
                    color: const Color(0xFF9E9E9E),
                    size: 20,
                  ),
                ),
                border: InputBorder.none,
                contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
              ),
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
          color: const Color(0xFF0044C4),
          boxShadow: [
            BoxShadow(
              color: const Color(0xFF0044C4).withValues(alpha: 0.3),
              blurRadius: 16,
              offset: const Offset(0, 8),
            ),
          ],
        ),
        child: ElevatedButton(
          onPressed: _isLoading ? null : _handleSimpanPerubahan,
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
              : Text(
                  'Simpan Perubahan',
                  style: AppTextStyles.buttonText.copyWith(fontSize: 16),
                ),
        ),
      ),
    );
  }
}
