import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:lapor_infrastruktur/theme/app_theme.dart';
import 'package:lapor_infrastruktur/screens/auth/register_screen.dart';
import 'package:lapor_infrastruktur/screens/pelapor/home_screen.dart';
import 'package:lapor_infrastruktur/screens/petugas/main_petugas_screen.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen>
    with SingleTickerProviderStateMixin {
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _obscurePassword = true;
  bool _isLoading = false;
  bool _emailFocused = false;
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
    _slideAnimation =
        Tween<Offset>(begin: const Offset(0, 0.08), end: Offset.zero).animate(
          CurvedAnimation(
            parent: _animationController,
            curve: const Interval(0.0, 0.7, curve: Curves.easeOut),
          ),
        );
    _animationController.forward();
  }

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    _animationController.dispose();
    super.dispose();
  }

  void _handleLogin() async {
    setState(() => _isLoading = true);
    await Future.delayed(const Duration(seconds: 2));
    setState(() => _isLoading = false);

    if (!mounted) return;

    // ── Role-based routing (dummy) ──────────────────────────────────────────
    // TODO: Ganti dengan pengecekan role dari API response
    // Contoh: final role = apiResponse['role'];
    final email = _emailController.text.trim().toLowerCase();
    final isPetugas = email.contains('petugas');

    if (isPetugas) {
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(
          builder: (context) => const MainPetugasScreen(
            namaUser: 'Andika Risky Septiawan',
            jabatan: 'Petugas Bina Marga',
            instansi: 'DPUPR Kabupaten Karanganyar',
          ),
        ),
      );
    } else {
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(
          builder: (context) => HomeScreen(
            namaUser: email.isEmpty ? 'Andika Risky Septiawan' : email,
          ),
        ),
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

                    // --- Logo & Judul ---
                    _buildHeader(),

                    const SizedBox(height: 32),

                    // --- Card Form Login ---
                    _buildLoginCard(),

                    const SizedBox(height: 32),

                    // --- Hubungi Admin ---
                    _buildContactAdmin(),

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

  Widget _buildHeader() {
    return Column(
      children: [
        // Logo
        Image.asset(
          'assets/images/logo.png',
          width: 100,
          height: 100,
          fit: BoxFit.contain,
          errorBuilder: (context, error, stackTrace) {
            return const Icon(
              Icons.campaign_rounded,
              size: 48,
              color: AppColors.primaryBlue,
            );
          },
        ),
        const SizedBox(height: 20),

        // App Title
        Text(
          'Lapor\nInfrastruktur',
          textAlign: TextAlign.center,
          style: AppTextStyles.appTitle,
        ),
        const SizedBox(height: 8),

        // Subtitle
        Text(
          'Sistem Pelaporan Infrastruktur Kota',
          textAlign: TextAlign.center,
          style: AppTextStyles.appSubtitle,
        ),
      ],
    );
  }

  Widget _buildLoginCard() {
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
          // Email Field
          Text('Email', style: AppTextStyles.label),
          const SizedBox(height: 8),
          _buildEmailField(),

          const SizedBox(height: 20),

          // Password Label Row
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text('Kata Sandi', style: AppTextStyles.label),
              GestureDetector(
                onTap: () {},
                child: Text(
                  'Lupa Kata Sandi?',
                  style: AppTextStyles.forgotPassword,
                ),
              ),
            ],
          ),
          const SizedBox(height: 8),
          _buildPasswordField(),

          const SizedBox(height: 24),

          // Login Button
          _buildLoginButton(),

          const SizedBox(height: 20),

          // Register Link
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Text('Belum punya akun?  ', style: AppTextStyles.bodyText),
              GestureDetector(
                onTap: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) => const RegisterScreen(),
                    ),
                  );
                },
                child: Text('Daftar di sini', style: AppTextStyles.linkText),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildEmailField() {
    return Focus(
      onFocusChange: (hasFocus) {
        setState(() => _emailFocused = hasFocus);
      },
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(12),
          border: Border.all(
            color: _emailFocused
                ? AppColors.inputActiveBorder
                : AppColors.inputBorder,
            width: _emailFocused ? 1.8 : 1.2,
          ),
          color: Colors.white,
        ),
        child: TextField(
          controller: _emailController,
          keyboardType: TextInputType.emailAddress,
          style: AppTextStyles.inputText,
          decoration: InputDecoration(
            hintText: 'andikariskys@gmail.com',
            hintStyle: AppTextStyles.inputText.copyWith(
              color: AppColors.hintText,
            ),
            prefixIcon: Icon(
              Icons.mail_outline_rounded,
              color: _emailFocused ? AppColors.primaryBlue : AppColors.textGrey,
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

  Widget _buildLoginButton() {
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
          onPressed: _isLoading ? null : _handleLogin,
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
                    Text('Masuk', style: AppTextStyles.buttonText),
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

  Widget _buildContactAdmin() {
    return Column(
      children: [
        Text('Butuh bantuan teknis?', style: AppTextStyles.bodyText),
        const SizedBox(height: 10),
        GestureDetector(
          onTap: () {},
          child: Container(
            padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(50),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withValues(alpha: 0.06),
                  blurRadius: 12,
                  offset: const Offset(0, 4),
                ),
              ],
            ),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                const Icon(
                  Icons.headset_mic_rounded,
                  color: AppColors.accentOrange,
                  size: 20,
                ),
                const SizedBox(width: 8),
                Text('Hubungi Admin', style: AppTextStyles.adminText),
              ],
            ),
          ),
        ),
      ],
    );
  }
}
