import 'package:flutter/material.dart';
import 'package:lapor_infrastruktur/theme/app_theme.dart';
import 'package:lapor_infrastruktur/screens/pelapor/detail_laporan_screen.dart';

class RiwayatScreen extends StatefulWidget {
  const RiwayatScreen({super.key});

  @override
  State<RiwayatScreen> createState() => _RiwayatScreenState();
}

class _RiwayatScreenState extends State<RiwayatScreen> {
  final TextEditingController _searchController = TextEditingController();
  String _selectedFilter = 'Semua';
  final List<String> _filterOptions = [
    'Semua',
    'Kerusakan jalan',
    'Rambu lalu lintas dan marka jalan',
    'Lampu penerangan jalan',
    'Drainase'
  ];

  // Data dipindahkan ke dalam build agar Hot Reload bisa me-refresh data ini

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    // Data sesuai mockup (dipindah ke sini agar state tidak tersangkut saat Hot Reload)
    final List<Map<String, dynamic>> laporanList = [
      {
        'kategori': 'Kerusakan jalan',
        'tanggal': '26 Apr 2026 • 08:45 WIB',
        'lokasi': 'Kec. Menteng, Jakarta\n-7.534587, 110.838543',
        'lokasi_nama': 'Jl. Karanganyar-Matesih, Kec. Menteng',
        'koordinat': '-7.534587, 110.838543',
        'deskripsi': 'Terdapat lubang yang cukup besar di tengah persimpangan. Sangat berbahaya bagi pengendara pada malam hari karena minim penerangan dan sering tergenang air saat hujan. Mohon untuk segera diperbaiki.',
        'status': 'DIAJUKAN',
        'icon': Icons.add_road_rounded,
        'iconBg': const Color(0xFFE4EFFF),
        'iconColor': const Color(0xFF0F3E9F),
      },
      {
        'kategori': 'Lampu penerangan jalan',
        'tanggal': '15 Mar 2026 • 08:15 WIB',
        'lokasi': 'Jl. Sudirman No.12\n-7.534587, 110.838543',
        'lokasi_nama': 'Jl. Sudirman No.12, Jakarta Pusat',
        'koordinat': '-7.534587, 110.838543',
        'deskripsi': 'Lampu penerangan jalan di sepanjang ruas jalan ini sudah mati selama lebih dari 2 minggu. Kondisi ini sangat berbahaya bagi pejalan kaki dan pengendara pada malam hari.',
        'status': 'SELESAI',
        'respon': 'Tim teknis dari Dinas Bina Marga telah menyelesaikan penggantian lampu penerangan jalan tersebut. Lampu sudah menyala normal. Terima kasih atas laporan Anda.',
        'petugas_nama': 'Admin Dinas PU',
        'petugas_waktu': '16 Mar 2026 • 09:30 WIB',
        'icon': Icons.power_off_rounded,
        'iconBg': const Color(0xFFFFDFDF),
        'iconColor': const Color(0xFF9F0F0F),
      },
      {
        'kategori': 'Drainase',
        'tanggal': '21 Jan 2026 • 12:45 WIB',
        'lokasi': 'Kec. Jebres, Mojosongo,\nSurakarta',
        'lokasi_nama': 'Jl. Mojosongo Raya, Kec. Jebres, Surakarta',
        'koordinat': '-7.551234, 110.857432',
        'deskripsi': 'Saluran drainase di depan rumah warga tersumbat dan meluap saat hujan deras. Genangan air mencapai ketinggian 30 cm dan mengalir ke jalan raya.',
        'status': 'DITOLAK',
        'alasan_tolak': 'Laporan tidak dapat diproses karena lokasi yang dilaporkan berada di luar wilayah kewenangan dinas. Silakan hubungi pemerintah kelurahan setempat untuk penanganan lebih lanjut.',
        'icon': Icons.water_drop_outlined,
        'iconBg': const Color(0xFFE4EFFF),
        'iconColor': const Color(0xFF0F3E9F),
      },
    ];

    // Menerapkan filter yang dipilih
    final filteredList = _selectedFilter == 'Semua' 
        ? laporanList 
        : laporanList.where((item) => item['kategori'].toString().toLowerCase() == _selectedFilter.toLowerCase()).toList();

    return SafeArea(
      child: SingleChildScrollView(
        physics: const BouncingScrollPhysics(),
        child: Padding(
          padding: const EdgeInsets.symmetric(horizontal: 24.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const SizedBox(height: 24),

              // Title
              Text(
                'Riwayat Laporan',
                style: AppTextStyles.appTitle.copyWith(fontSize: 22),
              ),

              const SizedBox(height: 24),

              // Search Bar & Search Button
              Row(
                children: [
                  Expanded(
                    child: Container(
                      height: 52,
                      decoration: BoxDecoration(
                        color: const Color(0xFFEBEBEB),
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: TextField(
                        controller: _searchController,
                        style: AppTextStyles.inputText,
                        decoration: InputDecoration(
                          hintText: 'Cari lokasi atau kerusakan',
                          hintStyle: AppTextStyles.inputText.copyWith(
                            color: const Color(0xFF7A7A7A),
                          ),
                          prefixIcon: const Icon(
                            Icons.search_rounded,
                            color: Color(0xFF7A7A7A),
                            size: 22,
                          ),
                          border: InputBorder.none,
                          contentPadding: const EdgeInsets.symmetric(vertical: 16),
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(width: 12),
                  Container(
                    height: 52,
                    width: 52,
                    decoration: BoxDecoration(
                      color: const Color(0xFF003CBF), // Dark blue button
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: const Icon(
                      Icons.search_rounded,
                      color: Colors.white,
                      size: 24,
                    ),
                  ),
                ],
              ),

              const SizedBox(height: 16),

              // Filter Button
              Container(
                height: 52,
                width: double.infinity,
                decoration: BoxDecoration(
                  color: const Color(0xFFEBEBEB),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Material(
                  color: Colors.transparent,
                  child: InkWell(
                    borderRadius: BorderRadius.circular(12),
                    onTap: () => _showFilterModal(context),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        const Icon(
                          Icons.filter_list_rounded,
                          color: Colors.black87,
                          size: 22,
                        ),
                        const SizedBox(width: 12),
                        Text(
                          'Filter',
                          style: AppTextStyles.label.copyWith(
                            fontSize: 16,
                            color: Colors.black87,
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ),

              const SizedBox(height: 32),

              // Laporan Terbaru Title
              Text(
                'Laporan Terbaru',
                style: AppTextStyles.label.copyWith(
                  fontSize: 18,
                  color: const Color(0xFF333333),
                ),
              ),

              const SizedBox(height: 16),

              // List of Laporan
              filteredList.isEmpty 
                ? Center(
                    child: Padding(
                      padding: const EdgeInsets.symmetric(vertical: 40.0),
                      child: Text(
                        'Tidak ada laporan untuk kategori ini.',
                        style: AppTextStyles.label.copyWith(color: const Color(0xFF7A7A7A)),
                      ),
                    ),
                  )
                : ListView.builder(
                    shrinkWrap: true,
                    physics: const NeverScrollableScrollPhysics(),
                    itemCount: filteredList.length,
                    itemBuilder: (context, index) {
                      return _buildLaporanCard(filteredList[index]);
                    },
                  ),

              const SizedBox(height: 100), // Bottom padding for nav bar
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildLaporanCard(Map<String, dynamic> item) {
    // Determine badge colors based on status
    Color badgeBgColor;
    Color badgeTextColor;

    switch (item['status']) {
      case 'DIAJUKAN':
        badgeBgColor = const Color(0xFFFCE4CA); // Light orange
        badgeTextColor = const Color(0xFF8D4F00); // Dark orange/brown
        break;
      case 'SELESAI':
        badgeBgColor = const Color(0xFFA6FA96); // Bright light green
        badgeTextColor = const Color(0xFF000000); // Black or very dark green
        break;
      case 'DITOLAK':
        badgeBgColor = const Color(0xFFFFD5D5); // Light red
        badgeTextColor = const Color(0xFF9F0F0F); // Dark red
        break;
      default:
        badgeBgColor = const Color(0xFFEBEBEB);
        badgeTextColor = const Color(0xFF333333);
    }

    return GestureDetector(
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => DetailLaporanScreen(laporan: item),
          ),
        );
      },
      child: Container(
        margin: const EdgeInsets.only(bottom: 16),
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withValues(alpha: 0.04),
              blurRadius: 12,
              offset: const Offset(0, 6),
            ),
          ],
          border: Border.all(color: const Color(0xFFF0F0F0)),
        ),
        child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Icon Thumbnail
          Container(
            width: 56,
            height: 56,
            decoration: BoxDecoration(
              color: item['iconBg'],
              borderRadius: BorderRadius.circular(12),
            ),
            child: Icon(
              item['icon'],
              color: item['iconColor'],
              size: 28,
            ),
          ),
          const SizedBox(width: 16),

          // Content
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Top Row: Category and Badge
                Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Expanded(
                      child: Text(
                        item['kategori'],
                        style: AppTextStyles.label.copyWith(
                          fontSize: 16,
                          color: Colors.black87,
                        ),
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                      ),
                    ),
                    const SizedBox(width: 8),
                    Row(
                      children: [
                        Container(
                          padding: const EdgeInsets.symmetric(
                            horizontal: 10,
                            vertical: 4,
                          ),
                          decoration: BoxDecoration(
                            color: badgeBgColor,
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: Text(
                            item['status'],
                            style: AppTextStyles.label.copyWith(
                              fontSize: 10,
                              fontWeight: FontWeight.w800,
                              color: badgeTextColor,
                            ),
                          ),
                        ),
                        const SizedBox(width: 4),
                        const Icon(
                          Icons.chevron_right_rounded,
                          color: Color(0xFF7A7A7A),
                          size: 20,
                        ),
                      ],
                    ),
                  ],
                ),
                
                const SizedBox(height: 6),
                
                // Date & Time
                Row(
                  children: [
                    const Icon(
                      Icons.calendar_today_outlined,
                      color: Color(0xFF7A7A7A),
                      size: 14,
                    ),
                    const SizedBox(width: 6),
                    Text(
                      item['tanggal'],
                      style: AppTextStyles.bodyText.copyWith(
                        fontSize: 12,
                        color: const Color(0xFF7A7A7A),
                      ),
                    ),
                  ],
                ),

                const SizedBox(height: 12),

                // Location
                Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Padding(
                      padding: EdgeInsets.only(top: 2),
                      child: Icon(
                        Icons.location_on_outlined,
                        color: Color(0xFF7A7A7A),
                        size: 14,
                      ),
                    ),
                    const SizedBox(width: 6),
                    Expanded(
                      child: Text(
                        item['lokasi'],
                        style: AppTextStyles.bodyText.copyWith(
                          fontSize: 12,
                          color: const Color(0xFF7A7A7A),
                          height: 1.4,
                        ),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    ));
  }

  void _showFilterModal(BuildContext context) {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      builder: (context) {
        return Padding(
          padding: const EdgeInsets.symmetric(vertical: 24.0, horizontal: 20.0),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'Pilih Kategori Laporan',
                style: AppTextStyles.appTitle.copyWith(fontSize: 18),
              ),
              const SizedBox(height: 16),
              ..._filterOptions.map((filter) {
                final isSelected = _selectedFilter == filter;
                return InkWell(
                  onTap: () {
                    setState(() {
                      _selectedFilter = filter;
                    });
                    Navigator.pop(context);
                  },
                  child: Container(
                    padding: const EdgeInsets.symmetric(vertical: 14),
                    decoration: BoxDecoration(
                      border: Border(
                        bottom: BorderSide(
                          color: Colors.grey.withValues(alpha: 0.2),
                        ),
                      ),
                    ),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text(
                          filter,
                          style: AppTextStyles.label.copyWith(
                            fontSize: 16,
                            color: isSelected ? const Color(0xFF003CBF) : Colors.black87,
                            fontWeight: isSelected ? FontWeight.w700 : FontWeight.w500,
                          ),
                        ),
                        if (isSelected)
                          const Icon(Icons.check_circle_rounded, color: Color(0xFF003CBF)),
                      ],
                    ),
                  ),
                );
              }),
            ],
          ),
        );
      },
    );
  }
}
