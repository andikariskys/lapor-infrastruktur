import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:lapor_infrastruktur/theme/app_theme.dart';
import 'package:lapor_infrastruktur/screens/petugas/home_petugas_screen.dart';
import 'package:lapor_infrastruktur/screens/petugas/tugas_screen.dart';
import 'package:lapor_infrastruktur/screens/pelapor/profil_screen.dart';

class MainPetugasScreen extends StatefulWidget {
  final String namaUser;
  final String jabatan;
  final String instansi;

  const MainPetugasScreen({
    super.key,
    this.namaUser = 'Andika Risky Septiawan',
    this.jabatan = 'Petugas Bina Marga',
    this.instansi = 'DPUPR Kabupaten Karanganyar',
  });

  @override
  State<MainPetugasScreen> createState() => _MainPetugasScreenState();
}

class _MainPetugasScreenState extends State<MainPetugasScreen>
    with SingleTickerProviderStateMixin {
  int _selectedNavIndex = 0;
  late PageController _pageController;

  late AnimationController _animController;
  late Animation<double> _fadeAnim;
  late Animation<Offset> _slideAnim;

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
              },
              children: [
                HomePetugasScreen(
                  namaUser: widget.namaUser,
                  jabatan: widget.jabatan,
                  instansi: widget.instansi,
                  onViewAllTugas: () {
                    setState(() => _selectedNavIndex = 1);
                    _pageController.animateToPage(
                      1,
                      duration: const Duration(milliseconds: 300),
                      curve: Curves.easeInOut,
                    );
                  },
                ),
                const TugasScreen(),
                const ProfilScreen(),
              ],
            ),
          ),
        ),
      ),
      bottomNavigationBar: _buildBottomNav(),
    );
  }

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
          unselectedLabelStyle: AppTextStyles.appSubtitle.copyWith(fontSize: 11),
          type: BottomNavigationBarType.fixed,
          elevation: 0,
          items: const [
            BottomNavigationBarItem(
              icon: Icon(Icons.home_outlined),
              activeIcon: Icon(Icons.home_rounded),
              label: 'BERANDA',
            ),
            BottomNavigationBarItem(
              icon: Icon(Icons.assignment_outlined),
              activeIcon: Icon(Icons.assignment_rounded),
              label: 'TUGAS',
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
