import 'package:flutter/material.dart';
import 'package:url_launcher/url_launcher.dart';
import 'package:lapor_infrastruktur/theme/app_theme.dart';
import 'package:lapor_infrastruktur/screens/auth/login_screen.dart';
import 'package:lapor_infrastruktur/screens/pelapor/keamanan_screen.dart';
import 'package:lapor_infrastruktur/screens/pelapor/edit_profil_screen.dart';
import 'package:lapor_infrastruktur/services/api_service.dart';

class ProfilScreen extends StatefulWidget {
  const ProfilScreen({super.key});

  @override
  State<ProfilScreen> createState() => _ProfilScreenState();
}

class _ProfilScreenState extends State<ProfilScreen> {
  Map<String, dynamic>? _userData;
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadUserData();
  }

  Future<void> _loadUserData() async {
    setState(() => _isLoading = true);
    try {
      final data = await ApiService.getUserData();
      if (data != null) {
        setState(() {
          _userData = data;
          _isLoading = false;
        });
      } else {
        // Try fetching from API
        final profile = await ApiService.getMyProfile();
        await ApiService.saveUserData(profile);
        setState(() {
          _userData = profile;
          _isLoading = false;
        });
      }
    } catch (_) {
      setState(() => _isLoading = false);
    }
  }

  void _handleLogout(BuildContext context) {
    showDialog(
      context: context,
      builder: (ctx) => Dialog(
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
                  color: Color(0xFFFFF0F0),
                  shape: BoxShape.circle,
                ),
                child: const Icon(
                  Icons.logout_rounded,
                  color: Color(0xFFC62828),
                  size: 36,
                ),
              ),
              const SizedBox(height: 16),
              Text(
                'Keluar dari Akun?',
                style: AppTextStyles.label.copyWith(
                  fontSize: 18,
                  fontWeight: FontWeight.w800,
                  color: AppColors.textDark,
                ),
              ),
              const SizedBox(height: 8),
              Text(
                'Anda yakin ingin keluar dari akun ini?',
                textAlign: TextAlign.center,
                style: AppTextStyles.appSubtitle.copyWith(fontSize: 13),
              ),
              const SizedBox(height: 24),
              Row(
                children: [
                  Expanded(
                    child: SizedBox(
                      height: 48,
                      child: OutlinedButton(
                        onPressed: () => Navigator.pop(ctx),
                        style: OutlinedButton.styleFrom(
                          side: const BorderSide(color: Color(0xFFE0E0E0)),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(12),
                          ),
                        ),
                        child: Text(
                          'Batal',
                          style: AppTextStyles.label.copyWith(
                            fontSize: 14,
                            color: Colors.black87,
                          ),
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: SizedBox(
                      height: 48,
                      child: ElevatedButton(
                        onPressed: () async {
                          Navigator.pop(ctx);
                          ApiService.resetDio();
                          await ApiService.clearToken();
                          if (!context.mounted) return;
                          Navigator.pushAndRemoveUntil(
                            context,
                            MaterialPageRoute(
                              builder: (_) => const LoginScreen(),
                            ),
                            (route) => false,
                          );
                        },
                        style: ElevatedButton.styleFrom(
                          backgroundColor: const Color(0xFFC62828),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(12),
                          ),
                          elevation: 0,
                        ),
                        child: Text('Keluar',
                            style: AppTextStyles.buttonText
                                .copyWith(fontSize: 14)),
                      ),
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }

  void _handlePusatBantuan() async {
    final Uri url = Uri.parse('https://wa.me/6281234567890?text=Halo%20Admin%2C%20saya%20butuh%20bantuan.');
    try {
      if (await canLaunchUrl(url)) {
        await launchUrl(url, mode: LaunchMode.externalApplication);
      } else {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: const Text('Hubungi admin di: admin@laporinfrastruktur.id'),
              backgroundColor: AppColors.primaryBlue,
              behavior: SnackBarBehavior.floating,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
            ),
          );
        }
      }
    } catch (_) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: const Text('Hubungi admin di: admin@laporinfrastruktur.id'),
            backgroundColor: AppColors.primaryBlue,
            behavior: SnackBarBehavior.floating,
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
          ),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final String nama = _userData?['name'] ?? 'Memuat...';
    final String email = _userData?['email'] ?? '...';
    final String role = _userData?['role'] ?? 'citizen';

    return SafeArea(
      child: SingleChildScrollView(
        physics: const BouncingScrollPhysics(),
        child: Padding(
          padding: const EdgeInsets.symmetric(horizontal: 24.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              const SizedBox(height: 24),
              
              // Header Title (Left aligned)
              Align(
                alignment: Alignment.centerLeft,
                child: Text(
                  'Profil Saya',
                  style: AppTextStyles.appTitle.copyWith(fontSize: 22),
                ),
              ),

              const SizedBox(height: 40),

              // Avatar
              Container(
                width: 100,
                height: 100,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: AppColors.background,
                  boxShadow: [
                    BoxShadow(
                      color: Colors.black.withValues(alpha: 0.1),
                      blurRadius: 20,
                      offset: const Offset(0, 8),
                    ),
                  ],
                  border: Border.all(color: Colors.white, width: 4),
                ),
                child: const CircleAvatar(
                  radius: 46,
                  backgroundColor: Color(0xFFE8ECF5),
                  child: Icon(
                    Icons.person_rounded,
                    size: 60,
                    color: Color(0xFFCDD3E0),
                  ),
                ),
              ),

              const SizedBox(height: 20),

              // Name
              _isLoading
                  ? const SizedBox(
                      width: 20,
                      height: 20,
                      child: CircularProgressIndicator(strokeWidth: 2),
                    )
                  : Text(
                      nama,
                      style: AppTextStyles.appTitle.copyWith(
                        fontSize: 24,
                        fontWeight: FontWeight.w800,
                      ),
                    ),

              const SizedBox(height: 4),

              // Email
              Text(
                email,
                style: AppTextStyles.bodyText.copyWith(
                  fontSize: 14,
                  color: const Color(0xFF7A7A7A),
                ),
              ),

              const SizedBox(height: 16),

              // Verification Badge
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                decoration: BoxDecoration(
                  color: role == 'officer'
                      ? const Color(0xFFEAEEFF)
                      : const Color(0xFFFFF7EC),
                  borderRadius: BorderRadius.circular(20),
                  border: Border.all(
                    color: role == 'officer'
                        ? const Color(0xFFB8C7FF)
                        : const Color(0xFFFDE1B9),
                    width: 1.5,
                  ),
                ),
                child: Row(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Icon(
                      role == 'officer'
                          ? Icons.engineering_rounded
                          : Icons.workspace_premium_rounded,
                      color: role == 'officer'
                          ? AppColors.primaryBlue
                          : const Color(0xFFB76E00),
                      size: 18,
                    ),
                    const SizedBox(width: 8),
                    Text(
                      role == 'officer' ? 'PETUGAS' : 'TERVERIFIKASI',
                      style: AppTextStyles.label.copyWith(
                        fontSize: 12,
                        fontWeight: FontWeight.w800,
                        color: role == 'officer'
                            ? AppColors.primaryBlue
                            : const Color(0xFFB76E00),
                        letterSpacing: 0.5,
                      ),
                    ),
                  ],
                ),
              ),

              const SizedBox(height: 40),

              // Menu Items
              _buildMenuItem(
                icon: Icons.person_outline_rounded,
                title: 'Ubah Profil',
                onTap: () async {
                  final result = await Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) =>
                          EditProfilScreen(userData: _userData),
                    ),
                  );
                  if (result == true) {
                    _loadUserData(); // Refresh data after edit
                  }
                },
              ),
              _buildMenuItem(
                icon: Icons.shield_outlined,
                title: 'Keamanan',
                onTap: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) => const KeamananScreen(),
                    ),
                  );
                },
              ),
              _buildMenuItem(
                icon: Icons.help_outline_rounded,
                title: 'Pusat Bantuan',
                onTap: _handlePusatBantuan,
              ),

              // Logout Button
              _buildLogoutButton(context),

              const SizedBox(height: 100), // Bottom padding
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildMenuItem({
    required IconData icon,
    required String title,
    required VoidCallback onTap,
  }) {
    return Container(
      margin: const EdgeInsets.only(bottom: 16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.03),
            blurRadius: 10,
            offset: const Offset(0, 4),
          ),
        ],
        border: Border.all(color: const Color(0xFFF0F0F0)),
      ),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(16),
          onTap: onTap,
          child: Padding(
            padding: const EdgeInsets.all(16.0),
            child: Row(
              children: [
                // Icon Box
                Container(
                  width: 44,
                  height: 44,
                  decoration: BoxDecoration(
                    color: const Color(0xFFE8ECF5),
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: Icon(
                    icon,
                    color: const Color(0xFF0F3E9F),
                    size: 24,
                  ),
                ),
                const SizedBox(width: 16),
                
                // Title
                Expanded(
                  child: Text(
                    title,
                    style: AppTextStyles.label.copyWith(
                      fontSize: 16,
                      color: Colors.black87,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                ),
                
                // Chevron Right
                const Icon(
                  Icons.chevron_right_rounded,
                  color: Color(0xFF7A7A7A),
                  size: 24,
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildLogoutButton(BuildContext context) {
    return Container(
      margin: const EdgeInsets.only(bottom: 16),
      decoration: BoxDecoration(
        color: const Color(0xFFFFF0F0),
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: const Color(0xFFC62828).withValues(alpha: 0.05),
            blurRadius: 10,
            offset: const Offset(0, 4),
          ),
        ],
        border: Border.all(color: const Color(0xFFFFEAEA)),
      ),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(16),
          onTap: () => _handleLogout(context),
          child: Padding(
            padding: const EdgeInsets.all(16.0),
            child: Row(
              children: [
                // Icon (No box for logout)
                Container(
                  width: 44,
                  height: 44,
                  alignment: Alignment.center,
                  child: const Icon(
                    Icons.logout_rounded,
                    color: Color(0xFFC62828),
                    size: 26,
                  ),
                ),
                const SizedBox(width: 16),
                
                // Title
                Expanded(
                  child: Text(
                    'Keluar',
                    style: AppTextStyles.label.copyWith(
                      fontSize: 16,
                      color: const Color(0xFFC62828),
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                ),
                
                // Chevron Right
                const Icon(
                  Icons.chevron_right_rounded,
                  color: Color(0xFFC62828),
                  size: 24,
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
