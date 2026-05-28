import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:lapor_infrastruktur/theme/app_theme.dart';
import 'package:lapor_infrastruktur/screens/pelapor/buat_laporan_screen.dart';
import 'package:lapor_infrastruktur/screens/pelapor/riwayat_screen.dart';
import 'package:lapor_infrastruktur/screens/pelapor/profil_screen.dart';
import 'package:lapor_infrastruktur/services/api_service.dart';
import 'package:lapor_infrastruktur/services/location_service.dart';

class HomeScreen extends StatefulWidget {
  final String namaUser;
  const HomeScreen({super.key, this.namaUser = 'Pengguna'});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen>
    with SingleTickerProviderStateMixin {
  int _selectedNavIndex = 0;
  late PageController _pageController;

  late AnimationController _animController;
  late Animation<double> _fadeAnim;
  late Animation<Offset> _slideAnim;

  // Data — loaded dynamically
  Map<String, dynamic>? _userData;
  String _lokasi = 'Memuat lokasi...';
  String _koordinat = '...';
  bool _isLoadingLocation = true;
  int _limitTerpakai = 0;
  final int _limitTotal = 3;
  int _totalLaporan = 0;
  int _diajukan = 0;
  int _ditolak = 0;
  int _diproses = 0;
  int _selesai = 0;

  @override
  void initState() {
    super.initState();
    _pageController = PageController(initialPage: _selectedNavIndex);
    _animController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 700),
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
    _loadReportStats();
    _loadLocation();
    _loadUserData();
  }

  Future<void> _loadUserData() async {
    // 1. Load instantly from cache for instant response
    try {
      final data = await ApiService.getUserData();
      if (data != null && mounted) {
        setState(() {
          _userData = data;
        });
      }
    } catch (_) {}

    // 2. Fetch fresh profile data from server in background to sync
    try {
      final profile = await ApiService.getMyProfile();
      await ApiService.saveUserData(profile);
      if (mounted) {
        setState(() {
          _userData = profile;
        });
      }
    } catch (_) {}
  }

  Future<void> _loadLocation() async {
    try {
      final location = await LocationService.getCurrentLocation();
      if (!mounted) return;
      setState(() {
        _lokasi = location.address;
        _koordinat = location.koordinat;
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

  Future<void> _loadReportStats() async {
    try {
      final reports = await ApiService.getMyReports();
      if (!mounted) return;
      int diajukan = 0, ditolak = 0, diproses = 0, selesai = 0;
      for (final r in reports) {
        final status = (r as Map<String, dynamic>)['status'] ?? 'pending';
        switch (status) {
          case 'pending':
            diajukan++;
            break;
          case 'spam':
            ditolak++;
            break;
          case 'verified':
          case 'in_progress':
            diproses++;
            break;
          case 'resolved':
            selesai++;
            break;
        }
      }
      // Count today's reports for limit
      final now = DateTime.now();
      int todayCount = 0;
      for (final r in reports) {
        final createdAt = (r as Map<String, dynamic>)['created_at'];
        if (createdAt != null) {
          try {
            final date = DateTime.parse(createdAt);
            if (date.year == now.year && date.month == now.month && date.day == now.day) {
              todayCount++;
            }
          } catch (_) {}
        }
      }
      setState(() {
        _totalLaporan = reports.length;
        _diajukan = diajukan;
        _ditolak = ditolak;
        _diproses = diproses;
        _selesai = selesai;
        _limitTerpakai = todayCount;
      });
    } catch (_) {
      // Keep defaults if API fails
    }
  }

  @override
  void dispose() {
    _pageController.dispose();
    _animController.dispose();
    super.dispose();
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
      body: FadeTransition(
        opacity: _fadeAnim,
        child: SlideTransition(
          position: _slideAnim,
          child: SafeArea(
            child: PageView(
              controller: _pageController,
              onPageChanged: (index) {
                setState(() => _selectedNavIndex = index);
                if (index == 0) {
                  _loadUserData();
                  _loadReportStats();
                }
              },
              children: [
                _buildBeranda(),
                const RiwayatScreen(),
                const ProfilScreen(),
              ],
            ),
          ),
        ),
      ),

      // FAB (hanya tampil di tab Beranda)
      floatingActionButton: _selectedNavIndex == 0 ? _buildFAB() : null,

      // Bottom Nav
      bottomNavigationBar: _buildBottomNav(),
    );
  }

  // ─── BERANDA ───────────────────────────────────────────────────────────────
  Widget _buildBeranda() {
    return SingleChildScrollView(
      physics: const BouncingScrollPhysics(),
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 20.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const SizedBox(height: 20),
            _buildHeader(),
            const SizedBox(height: 20),
            _buildLokasiCard(),
            const SizedBox(height: 14),
            _buildLimitCard(),
            const SizedBox(height: 20),
            _buildTotalLaporanCard(),
            const SizedBox(height: 16),
            _buildStatusGrid(),
            const SizedBox(height: 100),
          ],
        ),
      ),
    );
  }

  // ─── HEADER ────────────────────────────────────────────────────────────────
  Widget _buildHeader() {
    final String nama = _userData?['name'] ?? widget.namaUser;
    final String? profilePhotoPath = _userData?['profile_photo'];

    Widget avatarImage;
    if (profilePhotoPath != null && profilePhotoPath.isNotEmpty) {
      avatarImage = CircleAvatar(
        radius: 24,
        backgroundImage: NetworkImage(ApiService.getFullImageUrl(profilePhotoPath)),
      );
    } else {
      avatarImage = const CircleAvatar(
        radius: 24,
        backgroundColor: AppColors.primaryBlue,
        child: Icon(
          Icons.person_rounded,
          color: Colors.white,
          size: 26,
        ),
      );
    }

    return Row(
      children: [
        // Avatar
        Container(
          width: 48,
          height: 48,
          decoration: BoxDecoration(
            shape: BoxShape.circle,
            boxShadow: [
              BoxShadow(
                color: AppColors.primaryBlue.withValues(alpha: 0.25),
                blurRadius: 12,
                offset: const Offset(0, 4),
              ),
            ],
          ),
          child: avatarImage,
        ),
        const SizedBox(width: 12),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'HALO, SELAMAT DATANG',
                style: AppTextStyles.appSubtitle.copyWith(
                  fontSize: 11,
                  letterSpacing: 0.5,
                  fontWeight: FontWeight.w600,
                ),
              ),
              const SizedBox(height: 2),
              Text(
                nama,
                style: AppTextStyles.label.copyWith(
                  fontSize: 15,
                  fontWeight: FontWeight.w700,
                  color: AppColors.textDark,
                ),
                maxLines: 1,
                overflow: TextOverflow.ellipsis,
              ),
            ],
          ),
        ),
      ],
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
          // Icon lokasi
          Container(
            width: 40,
            height: 40,
            decoration: BoxDecoration(
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
                          valueColor: AlwaysStoppedAnimation<Color>(AppColors.primaryBlue),
                        ),
                      ),
                    ],
                  ],
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

  // ─── LIMIT LAPORAN CARD ───────────────────────────────────────────────────
  Widget _buildLimitCard() {
    final double progress = _limitTerpakai / _limitTotal;
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
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              const Icon(
                Icons.timelapse_rounded,
                color: AppColors.accentOrange,
                size: 20,
              ),
              const SizedBox(width: 8),
              Text(
                'Limit Laporan Harian',
                style: AppTextStyles.label.copyWith(fontSize: 13),
              ),
              const Spacer(),
              RichText(
                text: TextSpan(
                  children: [
                    TextSpan(
                      text: '$_limitTerpakai/$_limitTotal ',
                      style: AppTextStyles.label.copyWith(
                        color: AppColors.primaryBlue,
                        fontSize: 13,
                      ),
                    ),
                    TextSpan(
                      text: 'Terpakai',
                      style: AppTextStyles.appSubtitle.copyWith(fontSize: 12),
                    ),
                  ],
                ),
              ),
            ],
          ),
          const SizedBox(height: 10),
          ClipRRect(
            borderRadius: BorderRadius.circular(8),
            child: LinearProgressIndicator(
              value: progress,
              minHeight: 7,
              backgroundColor: AppColors.inputBorder,
              valueColor: const AlwaysStoppedAnimation<Color>(
                AppColors.primaryBlue,
              ),
            ),
          ),
        ],
      ),
    );
  }

  // ─── TOTAL LAPORAN CARD ───────────────────────────────────────────────────
  Widget _buildTotalLaporanCard() {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(22),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [Color(0xFF1A3C9A), Color(0xFF2856C8)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: AppColors.primaryBlue.withValues(alpha: 0.35),
            blurRadius: 20,
            offset: const Offset(0, 8),
          ),
        ],
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'TOTAL LAPORAN',
                style: AppTextStyles.appSubtitle.copyWith(
                  color: Colors.white.withValues(alpha: 0.8),
                  fontSize: 11,
                  letterSpacing: 1.0,
                  fontWeight: FontWeight.w600,
                ),
              ),
              const SizedBox(height: 8),
              Text(
                '$_totalLaporan',
                style: AppTextStyles.appTitle.copyWith(
                  color: Colors.white,
                  fontSize: 52,
                  fontWeight: FontWeight.w800,
                  height: 1.0,
                ),
              ),
            ],
          ),
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(
              color: Colors.white.withValues(alpha: 0.15),
              borderRadius: BorderRadius.circular(12),
            ),
            child: const Icon(
              Icons.bar_chart_rounded,
              color: Colors.white,
              size: 26,
            ),
          ),
        ],
      ),
    );
  }

  // ─── STATUS GRID ──────────────────────────────────────────────────────────
  Widget _buildStatusGrid() {
    return GridView.count(
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      crossAxisCount: 2,
      mainAxisSpacing: 12,
      crossAxisSpacing: 12,
      childAspectRatio: 1.3,
      children: [
        _buildStatusCard(
          label: 'DIAJUKAN',
          count: _diajukan,
          bgColor: const Color(0xFFFFF0E6),
          borderColor: const Color(0xFFE8720C),
          iconColor: const Color(0xFFE8720C),
          icon: Icons.more_horiz_rounded,
        ),
        _buildStatusCard(
          label: 'DITOLAK',
          count: _ditolak,
          bgColor: const Color(0xFFFFECEC),
          borderColor: const Color(0xFFE53935),
          iconColor: const Color(0xFFE53935),
          icon: Icons.cancel_outlined,
        ),
        _buildStatusCard(
          label: 'DIPROSES',
          count: _diproses,
          bgColor: const Color(0xFFEAEEFF),
          borderColor: const Color(0xFF4361EE),
          iconColor: const Color(0xFF4361EE),
          icon: Icons.settings_outlined,
        ),
        _buildStatusCard(
          label: 'SELESAI',
          count: _selesai,
          bgColor: const Color(0xFFDFF5E3),
          borderColor: const Color(0xFF2E7D32),
          iconColor: const Color(0xFF2E7D32),
          icon: Icons.check_circle_outline_rounded,
        ),
      ],
    );
  }

  Widget _buildStatusCard({
    required String label,
    required int count,
    required Color bgColor,
    required Color borderColor,
    required Color iconColor,
    required IconData icon,
  }) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: bgColor,
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: borderColor, width: 1.5),
        boxShadow: [
          // Memberikan bayangan berwarna sesuai dengan warna tema ikonnya
          BoxShadow(
            color: iconColor.withValues(alpha: 0.15),
            blurRadius: 14,
            offset: const Offset(0, 6),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                label,
                style: AppTextStyles.appSubtitle.copyWith(
                  fontSize: 11,
                  letterSpacing: 0.6,
                  fontWeight: FontWeight.w700,
                  color: AppColors.textDark.withValues(alpha: 0.7),
                ),
              ),
              Icon(icon, color: iconColor, size: 22),
            ],
          ),
          Text(
            '$count',
            style: AppTextStyles.appTitle.copyWith(
              fontSize: 34,
              color: AppColors.textDark,
              fontWeight: FontWeight.w800,
              height: 1.0,
            ),
          ),
        ],
      ),
    );
  }

  // ─── FAB ──────────────────────────────────────────────────────────────────
  Widget _buildFAB() {
    return Container(
      width: 56,
      height: 56,
      decoration: BoxDecoration(
        shape: BoxShape.circle,
        gradient: const LinearGradient(
          colors: [Color(0xFF1A3C9A), Color(0xFF2856C8)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        boxShadow: [
          BoxShadow(
            color: AppColors.primaryBlue.withValues(alpha: 0.4),
            blurRadius: 14,
            offset: const Offset(0, 6),
          ),
        ],
      ),
      child: FloatingActionButton(
        onPressed: () {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (context) => const BuatLaporanScreen(),
            ),
          );
        },
        backgroundColor: Colors.transparent,
        elevation: 0,
        child: const Icon(Icons.add, color: Colors.white, size: 28),
      ),
    );
  }

  // ─── BOTTOM NAV ───────────────────────────────────────────────────────────
  Widget _buildBottomNav() {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.07),
            blurRadius: 20,
            offset: const Offset(0, -4),
          ),
        ],
        borderRadius: const BorderRadius.vertical(top: Radius.circular(20)),
      ),
      child: ClipRRect(
        borderRadius: const BorderRadius.vertical(top: Radius.circular(20)),
        child: BottomNavigationBar(
          currentIndex: _selectedNavIndex,
          onTap: (index) {
            setState(() => _selectedNavIndex = index);
            _pageController.animateToPage(
              index,
              duration: const Duration(milliseconds: 300),
              curve: Curves.easeInOut,
            );
          },
          backgroundColor: Colors.white,
          selectedItemColor: AppColors.primaryBlue,
          unselectedItemColor: AppColors.textGrey,
          selectedLabelStyle: AppTextStyles.appSubtitle.copyWith(
            fontSize: 11,
            fontWeight: FontWeight.w700,
            color: AppColors.primaryBlue,
          ),
          unselectedLabelStyle: AppTextStyles.appSubtitle.copyWith(
            fontSize: 11,
          ),
          type: BottomNavigationBarType.fixed,
          elevation: 0,
          items: const [
            BottomNavigationBarItem(
              icon: Icon(Icons.home_outlined),
              activeIcon: Icon(Icons.home_rounded),
              label: 'BERANDA',
            ),
            BottomNavigationBarItem(
              icon: Icon(Icons.history_rounded),
              activeIcon: Icon(Icons.history_rounded),
              label: 'RIWAYAT',
            ),
            BottomNavigationBarItem(
              icon: Icon(Icons.person_outline_rounded),
              activeIcon: Icon(Icons.person_rounded),
              label: 'PROFIL',
            ),
          ],
        ),
      ),
    );
  }
}
