import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:lapor_infrastruktur/theme/app_theme.dart';
import 'package:lapor_infrastruktur/services/api_service.dart';

class RegisterScreen extends StatefulWidget {
  const RegisterScreen({super.key});

  @override
  State<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen>
    with SingleTickerProviderStateMixin {
  final _namaController = TextEditingController();
  final _emailController = TextEditingController();
  final _phoneController = TextEditingController();
  final _passwordController = TextEditingController();

  bool _obscurePassword = true;
  bool _isLoading = false;

  bool _namaFocused = false;
  bool _emailFocused = false;
  bool _phoneFocused = false;
  bool _passwordFocused = false;

  late AnimationController _animationController;
  late Animation<double> _fadeAnimation;
  late Animation<Offset> _slideAnimation;

  @override
  void initState() {
    super.initState();
    _animationController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 800),
    );
    _fadeAnimation = Tween<double>(begin: 0.0, end: 1.0).animate(
      CurvedAnimation(
        parent: _animationController,
        curve: const Interval(0.0, 0.7, curve: Curves.easeOut),
      ),
    );
    _slideAnimation = Tween<Offset>(
      begin: const Offset(0, 0.08),
      end: Offset.zero,
    ).animate(
      CurvedAnimation(
        parent: _animationController,
        curve: const Interval(0.0, 0.7, curve: Curves.easeOut),
      ),
    );
    _animationController.forward();
  }

  @override
  void dispose() {
    _namaController.dispose();
    _emailController.dispose();
    _phoneController.dispose();
    _passwordController.dispose();
    _animationController.dispose();
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

  void _handleRegister() async {
    final nama = _namaController.text.trim();
    final email = _emailController.text.trim();
    final phone = _phoneController.text.trim();
    final password = _passwordController.text.trim();

    // ── Validation ──────────────────────────────────────────────────────────
    if (nama.isEmpty || email.isEmpty || phone.isEmpty || password.isEmpty) {
      _showSnackBar('Harap isi semua field yang tersedia.', isError: true);
      return;
    }
    if (!RegExp(r'^[^@]+@[^@]+\.[^@]+$').hasMatch(email)) {
      _showSnackBar('Format email tidak valid.', isError: true);
      return;
    }
    if (password.length < 6) {
      _showSnackBar('Kata sandi minimal 6 karakter.', isError: true);
      return;
    }
    if (phone.length < 10 || phone.length > 13) {
      _showSnackBar('Nomor telepon harus 10-13 digit.', isError: true);
      return;
    }

    setState(() => _isLoading = true);

    try {
      await ApiService.register(
        name: nama,
        email: email,
        phone: phone,
        password: password,
      );

      if (!mounted) return;

      _showSnackBar('Akun berhasil didaftarkan! Silakan login.');
      // Kembali ke halaman login setelah daftar berhasil
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
      backgroundColor: AppColors.background,
      body: SafeArea(
        child: FadeTransition(
          opacity: _fadeAnimation,
          child: SlideTransition(
            position: _slideAnimation,
            child: SingleChildScrollView(
              physics: const BouncingScrollPhysics(),
              child: Padding(
                padding: const EdgeInsets.symmetric(horizontal: 24.0),
                child: Column(
                  children: [
                    const SizedBox(height: 48),

                    // --- Card Form Daftar ---
                    _buildRegisterCard(),

                    const SizedBox(height: 32),
                  ],
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildRegisterCard() {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: AppColors.cardBackground,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: const Color(0xFF1A3C9A).withValues(alpha: 0.08),
            blurRadius: 30,
            offset: const Offset(0, 8),
          ),
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.04),
            blurRadius: 10,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // --- Judul ---
          Text(
            'Daftar Akun',
            style: AppTextStyles.appTitle,
          ),
          const SizedBox(height: 8),
          Text(
            'Bergabung sekarang dan mulailah\nmelaporkan hari ini.',
            style: AppTextStyles.appSubtitle,
          ),

          const SizedBox(height: 28),

          // Nama Lengkap Field
          Text('Nama Lengkap', style: AppTextStyles.label),
          const SizedBox(height: 8),
          _buildInputField(
            controller: _namaController,
            hintText: 'Andika Risky Septiawan',
            icon: Icons.person_outline_rounded,
            isFocused: _namaFocused,
            onFocusChange: (v) => setState(() => _namaFocused = v),
            keyboardType: TextInputType.name,
          ),

          const SizedBox(height: 20),

          // Alamat Email Field
          Text('Alamat Email', style: AppTextStyles.label),
          const SizedBox(height: 8),
          _buildInputField(
            controller: _emailController,
            hintText: 'andikariskys@gmail.com',
            icon: Icons.mail_outline_rounded,
            isFocused: _emailFocused,
            onFocusChange: (v) => setState(() => _emailFocused = v),
            keyboardType: TextInputType.emailAddress,
          ),

          const SizedBox(height: 20),

          // No. Telephone Field
          Text('No. Telephone', style: AppTextStyles.label),
          const SizedBox(height: 8),
          _buildInputField(
            controller: _phoneController,
            hintText: '088123456789',
            icon: Icons.phone_outlined,
            isFocused: _phoneFocused,
            onFocusChange: (v) => setState(() => _phoneFocused = v),
            keyboardType: TextInputType.phone,
            inputFormatters: [
              FilteringTextInputFormatter.digitsOnly,
              LengthLimitingTextInputFormatter(13),
            ],
          ),

          const SizedBox(height: 20),

          // Kata Sandi Field
          Text('Kata Sandi', style: AppTextStyles.label),
          const SizedBox(height: 8),
          _buildPasswordField(),

          const SizedBox(height: 28),

          // Tombol Daftar
          _buildRegisterButton(),

          const SizedBox(height: 20),

          // Link Masuk
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Text('Sudah memiliki akun?  ', style: AppTextStyles.bodyText),
              GestureDetector(
                onTap: () => Navigator.pop(context),
                child: Text('Masuk di sini', style: AppTextStyles.linkText),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildInputField({
    required TextEditingController controller,
    required String hintText,
    required IconData icon,
    required bool isFocused,
    required ValueChanged<bool> onFocusChange,
    TextInputType keyboardType = TextInputType.text,
    List<TextInputFormatter>? inputFormatters,
  }) {
    return Focus(
      onFocusChange: onFocusChange,
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(12),
          border: Border.all(
            color: isFocused ? AppColors.inputActiveBorder : AppColors.inputBorder,
            width: isFocused ? 1.8 : 1.2,
          ),
          color: Colors.white,
        ),
        child: TextField(
          controller: controller,
          keyboardType: keyboardType,
          inputFormatters: inputFormatters,
          style: AppTextStyles.inputText,
          decoration: InputDecoration(
            hintText: hintText,
            hintStyle: AppTextStyles.inputText.copyWith(
              color: AppColors.hintText,
            ),
            prefixIcon: Icon(
              icon,
              color: isFocused ? AppColors.primaryBlue : AppColors.textGrey,
              size: 20,
            ),
            border: InputBorder.none,
            contentPadding: const EdgeInsets.symmetric(
              vertical: 14,
              horizontal: 4,
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildPasswordField() {
    return Focus(
      onFocusChange: (hasFocus) {
        setState(() => _passwordFocused = hasFocus);
      },
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(12),
          border: Border.all(
            color: _passwordFocused
                ? AppColors.inputActiveBorder
                : AppColors.inputBorder,
            width: _passwordFocused ? 1.8 : 1.2,
          ),
          color: Colors.white,
        ),
        child: TextField(
          controller: _passwordController,
          obscureText: _obscurePassword,
          style: AppTextStyles.inputText,
          decoration: InputDecoration(
            hintText: '••••••••••',
            hintStyle: AppTextStyles.inputText.copyWith(
              color: AppColors.hintText,
              letterSpacing: 2,
            ),
            prefixIcon: Icon(
              Icons.lock_outline_rounded,
              color: _passwordFocused
                  ? AppColors.primaryBlue
                  : AppColors.textGrey,
              size: 20,
            ),
            suffixIcon: GestureDetector(
              onTap: () {
                setState(() => _obscurePassword = !_obscurePassword);
              },
              child: Icon(
                _obscurePassword
                    ? Icons.visibility_off_outlined
                    : Icons.visibility_outlined,
                color: AppColors.textGrey,
                size: 20,
              ),
            ),
            border: InputBorder.none,
            contentPadding: const EdgeInsets.symmetric(
              vertical: 14,
              horizontal: 4,
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildRegisterButton() {
    return SizedBox(
      width: double.infinity,
      height: 52,
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(14),
          gradient: const LinearGradient(
            colors: [Color(0xFF1A3C9A), Color(0xFF2856C8)],
            begin: Alignment.centerLeft,
            end: Alignment.centerRight,
          ),
          boxShadow: [
            BoxShadow(
              color: const Color(0xFF1A3C9A).withValues(alpha: 0.35),
              blurRadius: 14,
              offset: const Offset(0, 6),
            ),
          ],
        ),
        child: ElevatedButton(
          onPressed: _isLoading ? null : _handleRegister,
          style: ElevatedButton.styleFrom(
            backgroundColor: Colors.transparent,
            shadowColor: Colors.transparent,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(14),
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
                    Text('Daftar', style: AppTextStyles.buttonText),
                    const SizedBox(width: 8),
                    const Icon(
                      Icons.arrow_forward_rounded,
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
