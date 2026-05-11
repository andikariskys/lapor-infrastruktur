import 'package:flutter/material.dart';
import 'package:lapor_infrastruktur/theme/app_theme.dart';
import 'package:lapor_infrastruktur/screens/petugas/detail_penugasan_screen.dart';

class TugasScreen extends StatefulWidget {
  const TugasScreen({super.key});

  @override
  State<TugasScreen> createState() => _TugasScreenState();
}

class _TugasScreenState extends State<TugasScreen> {
  final TextEditingController _searchController = TextEditingController();
  String _selectedFilter = 'Semua';

  final List<String> _filterOptions = [
    'Semua',
    'DIAJUKAN',
    'DIPROSES',
    'DIKERJAKAN',
    'SELESAI',
  ];

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final List<Map<String, dynamic>> tugasList = [
      {
        'kategori': 'Jalan',
        'tanggal': '26 Apr 2026 • 08:45 WIB',
        'lokasi': 'Kec. Matesih, Kab. Karanganyar\n-7.534587, 110.838543',
        'lokasi_nama': 'Jl. Karanganyar-Matesih, Kab. Karanganyar',
        'koordinat': '-7.534587, 110.838543',
        'status': 'DIPROSES',
        'deskripsi': 'Kerusakan jalan berlubang di persimpangan utama yang cukup berbahaya bagi pengendara pada malam hari.',
        'catatan_admin': 'Harap segera ditangani sebelum akhir bulan.',
        'icon': Icons.add_road_rounded,
        'iconBg': const Color(0xFFE4EFFF),
        'iconColor': const Color(0xFF0F3E9F),
      },
      {
        'kategori': 'Jalan',
        'tanggal': '23 Apr 2026 • 12:35 WIB',
        'lokasi': 'Kec. Jebres, Surakarta\n-6.134357, 112.138223',
        'lokasi_nama': 'Jl. Mojosongo Raya, Kec. Jebres, Surakarta',
        'koordinat': '-6.134357, 112.138223',
        'status': 'DIKERJAKAN',
        'deskripsi': 'Terdapat lubang besar di tengah persimpangan. Berbahaya bagi pengendara pada malam hari.',
        'catatan_admin': 'Tolong segera diperbaiki karena tiga hari lagi akan ditinjau oleh pejabat daerah.',
        'icon': Icons.add_road_rounded,
        'iconBg': const Color(0xFFE4EFFF),
        'iconColor': const Color(0xFF0F3E9F),
      },
      {
        'kategori': 'Jalan',
        'tanggal': '20 Apr 2026 • 15:45 WIB',
        'lokasi': 'Kec. Menteng, Jakarta\n-7.534587, 110.838543',
        'lokasi_nama': 'Jl. Sudirman, Kec. Menteng, Jakarta',
        'koordinat': '-7.534587, 110.838543',
        'status': 'SELESAI',
        'deskripsi': 'Kerusakan aspal di bahu jalan sudah parah dan berisiko menyebabkan kecelakaan.',
        'catatan_admin': '',
        'respon': 'Perbaikan telah selesai dilakukan oleh tim teknis. Aspal sudah diganti dan kondisi jalan kembali normal.',
        'petugas_nama': 'Tim Teknis Bina Marga',
        'petugas_waktu': '21 Apr 2026 • 10:00 WIB',
        'rating': 4,
        'ulasan': 'Pengerjaan bagus, namun jalur hasil perbaikan tidak merata, jika memungkinkan tolong perbaiki lagi.',
        'icon': Icons.add_road_rounded,
        'iconBg': const Color(0xFFE4EFFF),
        'iconColor': const Color(0xFF0F3E9F),
      },
    ];

    final filteredList = _selectedFilter == 'Semua'
        ? tugasList
        : tugasList.where((item) => item['status'] == _selectedFilter).toList();

    return SafeArea(
      child: SingleChildScrollView(
        physics: const BouncingScrollPhysics(),
        child: Padding(
          padding: const EdgeInsets.symmetric(horizontal: 24.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const SizedBox(height: 24),

              Text(
                'Riwayat Penugasan',
                style: AppTextStyles.appTitle.copyWith(fontSize: 22),
              ),

              const SizedBox(height: 24),

              // Search Bar
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
                          prefixIcon: const Icon(Icons.search_rounded,
                              color: Color(0xFF7A7A7A), size: 22),
                          border: InputBorder.none,
                          contentPadding:
                              const EdgeInsets.symmetric(vertical: 16),
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(width: 12),
                  Container(
                    height: 52,
                    width: 52,
                    decoration: BoxDecoration(
                      color: const Color(0xFF003CBF),
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: const Icon(Icons.search_rounded,
                        color: Colors.white, size: 24),
                  ),
                ],
              ),

              const SizedBox(height: 12),

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
                        const Icon(Icons.filter_list_rounded,
                            color: Colors.black87, size: 22),
                        const SizedBox(width: 10),
                        Text(
                          _selectedFilter == 'Semua'
                              ? 'Filter'
                              : 'Filter: $_selectedFilter',
                          style: AppTextStyles.label.copyWith(
                              fontSize: 15, color: Colors.black87),
                        ),
                      ],
                    ),
                  ),
                ),
              ),

              const SizedBox(height: 28),

              Text(
                'Laporan Terbaru',
                style: AppTextStyles.label.copyWith(
                    fontSize: 18, color: const Color(0xFF333333)),
              ),

              const SizedBox(height: 14),

              filteredList.isEmpty
                  ? Center(
                      child: Padding(
                        padding: const EdgeInsets.symmetric(vertical: 40),
                        child: Text(
                          'Tidak ada tugas untuk status ini.',
                          style: AppTextStyles.label
                              .copyWith(color: const Color(0xFF7A7A7A)),
                        ),
                      ),
                    )
                  : ListView.builder(
                      shrinkWrap: true,
                      physics: const NeverScrollableScrollPhysics(),
                      itemCount: filteredList.length,
                      itemBuilder: (context, index) {
                        return _buildTugasCard(filteredList[index]);
                      },
                    ),

              const SizedBox(height: 100),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildTugasCard(Map<String, dynamic> item) {
    final String status = item['status'];
    Color badgeBg;
    Color badgeText;

    switch (status) {
      case 'DIKERJAKAN':
        badgeBg = const Color(0xFFD6E4FF);
        badgeText = const Color(0xFF0044C4);
        break;
      case 'DIPROSES':
        badgeBg = const Color(0xFFFCE4CA);
        badgeText = const Color(0xFF8D4F00);
        break;
      case 'SELESAI':
        badgeBg = const Color(0xFFA6FA96);
        badgeText = const Color(0xFF1B5E20);
        break;
      default:
        badgeBg = const Color(0xFFEBEBEB);
        badgeText = const Color(0xFF333333);
    }

    return GestureDetector(
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => DetailPenugasanScreen(penugasan: item),
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
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(
                  width: 52,
                  height: 52,
                  decoration: BoxDecoration(
                    color: item['iconBg'],
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: Icon(item['icon'],
                      color: item['iconColor'], size: 26),
                ),
                const SizedBox(width: 14),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Expanded(
                            child: Text(
                              item['kategori'],
                              style: AppTextStyles.label.copyWith(
                                  fontSize: 16, color: Colors.black87),
                            ),
                          ),
                          const SizedBox(width: 8),
                          Row(
                            children: [
                              Container(
                                padding: const EdgeInsets.symmetric(
                                    horizontal: 10, vertical: 4),
                                decoration: BoxDecoration(
                                  color: badgeBg,
                                  borderRadius: BorderRadius.circular(12),
                                ),
                                child: Text(
                                  status,
                                  style: AppTextStyles.label.copyWith(
                                    fontSize: 10,
                                    fontWeight: FontWeight.w800,
                                    color: badgeText,
                                  ),
                                ),
                              ),
                              const SizedBox(width: 4),
                              const Icon(Icons.chevron_right_rounded,
                                  color: Color(0xFF7A7A7A), size: 20),
                            ],
                          ),
                        ],
                      ),
                      const SizedBox(height: 6),
                      Row(
                        children: [
                          const Icon(Icons.calendar_today_outlined,
                              color: Color(0xFF7A7A7A), size: 13),
                          const SizedBox(width: 5),
                          Text(
                            item['tanggal'],
                            style: AppTextStyles.bodyText.copyWith(
                                fontSize: 12,
                                color: const Color(0xFF7A7A7A)),
                          ),
                        ],
                      ),
                      const SizedBox(height: 6),
                      Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Padding(
                            padding: EdgeInsets.only(top: 2),
                            child: Icon(Icons.location_on_outlined,
                                color: Color(0xFF7A7A7A), size: 13),
                          ),
                          const SizedBox(width: 5),
                          Expanded(
                            child: Text(
                              item['lokasi'],
                              style: AppTextStyles.bodyText.copyWith(
                                  fontSize: 12,
                                  color: const Color(0xFF7A7A7A),
                                  height: 1.4),
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              ],
            ),

            // Tampilkan ulasan/rating jika SELESAI
            if (status == 'SELESAI' && item['ulasan'] != null) ...[
              const SizedBox(height: 12),
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: const Color(0xFFF8F8F8),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: List.generate(5, (i) {
                        final rating = item['rating'] ?? 0;
                        return Icon(
                          i < rating ? Icons.star_rounded : Icons.star_border_rounded,
                          size: 20,
                          color: i < rating
                              ? const Color(0xFFFFC107)
                              : const Color(0xFFCCCCCC),
                        );
                      }),
                    ),
                    const SizedBox(height: 6),
                    Text(
                      '"${item['ulasan']}"',
                      style: AppTextStyles.bodyText.copyWith(
                        fontSize: 12,
                        fontStyle: FontStyle.italic,
                        color: const Color(0xFF555555),
                        height: 1.4,
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ],
        ),
      ),
    );
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
                'Filter Status Tugas',
                style: AppTextStyles.appTitle.copyWith(fontSize: 18),
              ),
              const SizedBox(height: 16),
              ..._filterOptions.map((filter) {
                final isSelected = _selectedFilter == filter;
                return InkWell(
                  onTap: () {
                    setState(() => _selectedFilter = filter);
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
                            color: isSelected
                                ? const Color(0xFF003CBF)
                                : Colors.black87,
                            fontWeight: isSelected
                                ? FontWeight.w700
                                : FontWeight.w500,
                          ),
                        ),
                        if (isSelected)
                          const Icon(Icons.check_circle_rounded,
                              color: Color(0xFF003CBF)),
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
