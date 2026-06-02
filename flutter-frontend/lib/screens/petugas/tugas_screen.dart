import 'package:flutter/material.dart';
import 'package:lapor_infrastruktur/theme/app_theme.dart';
import 'package:lapor_infrastruktur/screens/petugas/detail_penugasan_screen.dart';
import 'package:lapor_infrastruktur/services/api_service.dart';
import 'package:lapor_infrastruktur/services/location_service.dart';

class TugasScreen extends StatefulWidget {
  const TugasScreen({super.key});

  @override
  State<TugasScreen> createState() => _TugasScreenState();
}

class _TugasScreenState extends State<TugasScreen> {
  final TextEditingController _searchController = TextEditingController();
  String _selectedFilter = 'Semua';
  String _searchQuery = '';
  bool _isLoading = true;

  final List<String> _filterOptions = [
    'Semua',
    'DIAJUKAN',
    'DIPROSES',
    'DIKERJAKAN',
    'SELESAI',
  ];

  List<Map<String, dynamic>> _tugasList = [];

  @override
  void initState() {
    super.initState();
    _loadAssignedReports();
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  Future<void> _loadAssignedReports() async {
    setState(() => _isLoading = true);
    try {
      final reports = await ApiService.getAssignedReports();
      if (!mounted) return;
      setState(() {
        _tugasList = reports.map((r) {
          final report = r as Map<String, dynamic>;
          final kategoriName = report['category']?['name'] ?? 'Lainnya';
          final status = _mapStatus(report['status'] ?? 'pending');
          final lat = report['latitude'] ?? 0.0;
          final lon = report['longitude'] ?? 0.0;

          return {
            'id': report['id'],
            'kategori': kategoriName,
            'tanggal': _formatDate(report['created_at']),
            'lokasi': 'Memuat lokasi...',
            'lokasi_nama': 'Memuat...',
            'koordinat': '${(lat as num).toStringAsFixed(6)}, ${(lon as num).toStringAsFixed(6)}',
            'latitude': lat,
            'longitude': lon,
            'deskripsi': report['description'] ?? '-',
            'status': status,
            'foto_url': report['photo_url'],
            'catatan_admin': report['assignments'] != null && (report['assignments'] as List).isNotEmpty
                ? (report['assignments'] as List).first['note'] ?? ''
                : '',
            'feedbacks': report['feedbacks'] ?? [],
            'completion_photo': report['completion_photo'],
            'officer_reply': report['officer_reply'],
            'icon': _getIconForCategory(kategoriName),
            'iconBg': _getIconBgForCategory(kategoriName),
            'iconColor': _getIconColorForCategory(kategoriName),
          };
        }).toList();
        _isLoading = false;
      });
      _reverseGeocodeAll();
    } catch (e) {
      if (!mounted) return;
      setState(() {
        _tugasList = [];
        _isLoading = false;
      });
    }
  }

  Future<void> _reverseGeocodeAll() async {
    for (int i = 0; i < _tugasList.length; i++) {
      final lat = _tugasList[i]['latitude'];
      final lon = _tugasList[i]['longitude'];
      if (lat != null && lon != null && lat != 0.0 && lon != 0.0) {
        try {
          final address = await LocationService.reverseGeocode(
            (lat as num).toDouble(),
            (lon as num).toDouble(),
          );
          if (!mounted) return;
          setState(() {
            _tugasList[i]['lokasi'] = '$address\n${_tugasList[i]['koordinat']}';
            _tugasList[i]['lokasi_nama'] = address;
          });
        } catch (_) {
          if (!mounted) return;
          setState(() {
            _tugasList[i]['lokasi'] = _tugasList[i]['koordinat'];
            _tugasList[i]['lokasi_nama'] = _tugasList[i]['koordinat'];
          });
        }
      }
    }
  }

  String _mapStatus(String apiStatus) {
    switch (apiStatus) {
      case 'pending':
        return 'DIAJUKAN';
      case 'verified':
        return 'DIPROSES';
      case 'in_progress':
        return 'DIKERJAKAN';
      case 'resolved':
        return 'SELESAI';
      case 'spam':
        return 'DITOLAK';
      default:
        return 'DIAJUKAN';
    }
  }

  String _formatDate(String? dateStr) {
    if (dateStr == null) return '-';
    try {
      final date = DateTime.parse(dateStr);
      final months = [
        '', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
        'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
      ];
      return '${date.day} ${months[date.month]} ${date.year} • ${date.hour.toString().padLeft(2, '0')}:${date.minute.toString().padLeft(2, '0')} WIB';
    } catch (_) {
      return dateStr;
    }
  }

  IconData _getIconForCategory(String kategori) {
    final lower = kategori.toLowerCase();
    if (lower.contains('jalan') && (lower.contains('rusak') || lower.contains('kerusakan'))) {
      return Icons.add_road_rounded;
    } else if (lower.contains('lampu') || lower.contains('penerangan')) {
      return Icons.power_off_rounded;
    } else if (lower.contains('drainase') || lower.contains('selokan')) {
      return Icons.water_drop_outlined;
    } else if (lower.contains('rambu') || lower.contains('marka')) {
      return Icons.signpost_rounded;
    } else {
      return Icons.report_problem_rounded;
    }
  }

  Color _getIconBgForCategory(String kategori) {
    final lower = kategori.toLowerCase();
    if (lower.contains('jalan') && (lower.contains('rusak') || lower.contains('kerusakan'))) {
      return const Color(0xFFE4EFFF);
    } else if (lower.contains('lampu') || lower.contains('penerangan')) {
      return const Color(0xFFFFDFDF);
    } else if (lower.contains('drainase') || lower.contains('selokan')) {
      return const Color(0xFFE4EFFF);
    } else if (lower.contains('rambu') || lower.contains('marka')) {
      return const Color(0xFFFFF0E6);
    } else {
      return const Color(0xFFFFF0E6);
    }
  }

  Color _getIconColorForCategory(String kategori) {
    final lower = kategori.toLowerCase();
    if (lower.contains('jalan') && (lower.contains('rusak') || lower.contains('kerusakan'))) {
      return const Color(0xFF0F3E9F);
    } else if (lower.contains('lampu') || lower.contains('penerangan')) {
      return const Color(0xFF9F0F0F);
    } else if (lower.contains('drainase') || lower.contains('selokan')) {
      return const Color(0xFF0F3E9F);
    } else if (lower.contains('rambu') || lower.contains('marka')) {
      return const Color(0xFFE8720C);
    } else {
      return const Color(0xFFE8720C);
    }
  }

  @override
  Widget build(BuildContext context) {
    final filteredList = _tugasList.where((item) {
      final matchesFilter = _selectedFilter == 'Semua' || item['status'] == _selectedFilter;
      final matchesSearch = _searchQuery.isEmpty ||
          item['kategori'].toString().toLowerCase().contains(_searchQuery) ||
          item['lokasi'].toString().toLowerCase().contains(_searchQuery) ||
          item['deskripsi'].toString().toLowerCase().contains(_searchQuery);
      return matchesFilter && matchesSearch;
    }).toList();

    return SafeArea(
      child: RefreshIndicator(
        onRefresh: _loadAssignedReports,
        color: AppColors.primaryBlue,
        child: SingleChildScrollView(
          physics: const AlwaysScrollableScrollPhysics(
            parent: BouncingScrollPhysics(),
          ),
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
                          onSubmitted: (_) {
                            setState(() {
                              _searchQuery = _searchController.text.trim().toLowerCase();
                            });
                          },
                          decoration: InputDecoration(
                            hintText: 'Cari lokasi atau kerusakan',
                            hintStyle: AppTextStyles.inputText.copyWith(
                              color: const Color(0xFF7A7A7A),
                            ),
                            prefixIcon: const Icon(Icons.search_rounded,
                                color: Color(0xFF7A7A7A), size: 22),
                            suffixIcon: _searchQuery.isNotEmpty
                                ? GestureDetector(
                                    onTap: () {
                                      _searchController.clear();
                                      setState(() => _searchQuery = '');
                                    },
                                    child: const Icon(Icons.close_rounded,
                                        color: Color(0xFF7A7A7A), size: 20),
                                  )
                                : null,
                            border: InputBorder.none,
                            contentPadding:
                                const EdgeInsets.symmetric(vertical: 16),
                          ),
                        ),
                      ),
                    ),
                    const SizedBox(width: 12),
                    GestureDetector(
                      onTap: () {
                        setState(() {
                          _searchQuery = _searchController.text.trim().toLowerCase();
                        });
                      },
                      child: Container(
                        height: 52,
                        width: 52,
                        decoration: BoxDecoration(
                          color: const Color(0xFF003CBF),
                          borderRadius: BorderRadius.circular(12),
                        ),
                        child: const Icon(Icons.search_rounded,
                            color: Colors.white, size: 24),
                      ),
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

                if (_isLoading)
                  const Center(
                    child: Padding(
                      padding: EdgeInsets.symmetric(vertical: 40),
                      child: CircularProgressIndicator(color: AppColors.primaryBlue),
                    ),
                  )
                else if (filteredList.isEmpty)
                  Center(
                    child: Padding(
                      padding: const EdgeInsets.symmetric(vertical: 40),
                      child: Column(
                        children: [
                          Icon(Icons.assignment_outlined,
                              size: 48, color: Colors.grey.withValues(alpha: 0.5)),
                          const SizedBox(height: 12),
                          Text(
                            _searchQuery.isNotEmpty
                                ? 'Tidak ditemukan hasil untuk "$_searchQuery"'
                                : 'Tidak ada tugas untuk status ini.',
                            style: AppTextStyles.label
                                .copyWith(color: const Color(0xFF7A7A7A)),
                            textAlign: TextAlign.center,
                          ),
                        ],
                      ),
                    ),
                  )
                else
                  ListView.builder(
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
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              width: 56,
              height: 56,
              decoration: BoxDecoration(
                color: item['iconBg'] ?? const Color(0xFFE4EFFF),
                borderRadius: BorderRadius.circular(12),
              ),
              child: item['foto_url'] != null && item['foto_url'].toString().isNotEmpty
                  ? ClipRRect(
                      borderRadius: BorderRadius.circular(12),
                      child: Image.network(
                        ApiService.getFullImageUrl(item['foto_url']),
                        fit: BoxFit.cover,
                        errorBuilder: (context, error, stackTrace) {
                          return Center(
                            child: Icon(
                              item['icon'] ?? Icons.report_problem_rounded,
                              color: item['iconColor'] ?? const Color(0xFF0F3E9F),
                              size: 28,
                            ),
                          );
                        },
                        loadingBuilder: (context, child, loadingProgress) {
                          if (loadingProgress == null) return child;
                          return const Center(
                            child: SizedBox(
                              width: 20,
                              height: 20,
                              child: CircularProgressIndicator(
                                strokeWidth: 2,
                                valueColor: AlwaysStoppedAnimation<Color>(Color(0xFF0F3E9F)),
                              ),
                            ),
                          );
                        },
                      ),
                    )
                  : Icon(
                      item['icon'] ?? Icons.report_problem_rounded,
                      color: item['iconColor'] ?? const Color(0xFF0F3E9F),
                      size: 28,
                    ),
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
