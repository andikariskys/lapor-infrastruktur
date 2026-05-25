import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:image_picker/image_picker.dart';
import 'package:lapor_infrastruktur/theme/app_theme.dart';
import 'package:lapor_infrastruktur/services/api_service.dart';

class EditProfilScreen extends StatefulWidget {
  final Map<String, dynamic>? userData;

  const EditProfilScreen({super.key, this.userData});

  @override
  State<EditProfilScreen> createState() => _EditProfilScreenState();
}

class _EditProfilScreenState extends State<EditProfilScreen> {
  final _namaController = TextEditingController();
  final _emailController = TextEditingController();
  final _phoneController = TextEditingController();
  bool _isLoading = false;

  Uint8List? _imageBytes;
  String? _imageName;

  @override
  void initState() {
    super.initState();
    _namaController.text = widget.userData?['name'] ?? '';
    _emailController.text = widget.userData?['email'] ?? '';
    _phoneController.text = widget.userData?['phone'] ?? '';
  }

  @override
  void dispose() {
    _namaController.dispose();
    _emailController.dispose();
    _phoneController.dispose();
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

  void _pickProfileImage() async {
    final ImagePicker picker = ImagePicker();

    // Show source selection bottom sheet
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      builder: (ctx) => Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Text(
              'Ubah Foto Profil',
              style: AppTextStyles.label.copyWith(fontSize: 18),
            ),
            const SizedBox(height: 20),
            ListTile(
              leading: Container(
                width: 44,
                height: 44,
                decoration: BoxDecoration(
                  color: const Color(0xFFE8ECF5),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: const Icon(
                  Icons.camera_alt_rounded,
                  color: AppColors.primaryBlue,
                ),
              ),
              title: Text(
                'Kamera',
                style: AppTextStyles.label.copyWith(fontSize: 16),
              ),
              onTap: () {
                Navigator.pop(ctx);
                _pickFromSource(picker, ImageSource.camera);
              },
            ),
            ListTile(
              leading: Container(
                width: 44,
                height: 44,
                decoration: BoxDecoration(
                  color: const Color(0xFFE8ECF5),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: const Icon(
                  Icons.photo_library_rounded,
                  color: AppColors.primaryBlue,
                ),
              ),
              title: Text(
                'Galeri',
                style: AppTextStyles.label.copyWith(fontSize: 16),
              ),
              onTap: () {
                Navigator.pop(ctx);
                _pickFromSource(picker, ImageSource.gallery);
              },
            ),
            const SizedBox(height: 8),
          ],
        ),
      ),
    );
  }

  void _pickFromSource(ImagePicker picker, ImageSource source) async {
    try {
      final XFile? image = await picker.pickImage(
        source: source,
        maxWidth: 500,
        maxHeight: 500,
        imageQuality: 80,
      );
      if (image != null) {
        final bytes = await image.readAsBytes();
        setState(() {
          _imageBytes = bytes;
          _imageName = image.name;
        });
        _showSnackBar('Foto berhasil dipilih');
      }
    } catch (e) {
      _showSnackBar('Gagal memilih foto: ${e.toString()}', isError: true);
    }
  }

  void _handleSimpan() async {
    final nama = _namaController.text.trim();
    final email = _emailController.text.trim();
    final phone = _phoneController.text.trim();

    if (nama.isEmpty) {
      _showSnackBar('Nama tidak boleh kosong.', isError: true);
      return;
    }
    if (email.isEmpty) {
      _showSnackBar('Email tidak boleh kosong.', isError: true);
      return;
    }

    setState(() => _isLoading = true);

    try {
      await ApiService.updateMyProfile(
        name: nama,
        email: email,
        phone: phone,
        imageBytes: _imageBytes,
        imageFileName: _imageName,
      );
      if (!mounted) return;
      _showSnackBar('Profil berhasil diperbarui.');
      Navigator.pop(context, true); // Return true to signal refresh
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

    // Get current profile image path from userData
    final profilePhotoPath = widget.userData?['profile_photo'];

    Widget avatarImage;
    if (_imageBytes != null) {
      avatarImage = CircleAvatar(
        radius: 46,
        backgroundImage: MemoryImage(_imageBytes!),
      );
    } else if (profilePhotoPath != null && profilePhotoPath.isNotEmpty) {
      avatarImage = CircleAvatar(
        radius: 46,
        backgroundImage: NetworkImage(ApiService.getFullImageUrl(profilePhotoPath)),
      );
    } else {
      avatarImage = const CircleAvatar(
        radius: 46,
        backgroundColor: Color(0xFFE8ECF5),
        child: Icon(
          Icons.person_rounded,
          size: 60,
          color: Color(0xFFCDD3E0),
        ),
      );
    }

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

                // Avatar with Edit Button
                Center(
                  child: Stack(
                    children: [
                      Container(
                        width: 100,
                        height: 100,
                        decoration: BoxDecoration(
                          shape: BoxShape.circle,
                          color: AppColors.background,
                          border: Border.all(color: Colors.white, width: 4),
                          boxShadow: [
                            BoxShadow(
                              color: Colors.black.withValues(alpha: 0.1),
                              blurRadius: 20,
                              offset: const Offset(0, 8),
                            ),
                          ],
                        ),
                        child: avatarImage,
                      ),
                      Positioned(
                        bottom: 0,
                        right: 0,
                        child: GestureDetector(
                          onTap: _pickProfileImage,
                          child: Container(
                            width: 32,
                            height: 32,
                            decoration: BoxDecoration(
                              color: AppColors.primaryBlue,
                              shape: BoxShape.circle,
                              border: Border.all(color: Colors.white, width: 2),
                            ),
                            child: const Icon(
                              Icons.camera_alt_rounded,
                              color: Colors.white,
                              size: 16,
                            ),
                          ),
                        ),
                      ),
                    ],
                  ),
                ),

                const SizedBox(height: 32),

                // Nama
                _buildFieldLabel('Nama Lengkap'),
                const SizedBox(height: 10),
                _buildTextField(_namaController, 'Masukkan nama lengkap'),

                const SizedBox(height: 20),

                // Email
                _buildFieldLabel('Email'),
                const SizedBox(height: 10),
                _buildTextField(_emailController, 'Masukkan email', keyboardType: TextInputType.emailAddress),

                const SizedBox(height: 20),

                // Phone
                _buildFieldLabel('No. Telepon'),
                const SizedBox(height: 10),
                _buildTextField(_phoneController, '0881-2345-6789',
                    keyboardType: TextInputType.phone),

                const SizedBox(height: 40),

                // Simpan Button
                _buildSimpanButton(),

                const SizedBox(height: 40),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildFieldLabel(String label) {
    return Text(
      label,
      style: AppTextStyles.label.copyWith(
        fontSize: 15,
        color: Colors.black87,
        fontWeight: FontWeight.w700,
      ),
    );
  }

  Widget _buildTextField(TextEditingController controller, String hint,
      {TextInputType keyboardType = TextInputType.text}) {
    return Container(
      height: 54,
      decoration: BoxDecoration(
        color: const Color(0xFFF5F5F5),
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: const Color(0xFFE0E0E0)),
      ),
      child: TextField(
        controller: controller,
        keyboardType: keyboardType,
        style: AppTextStyles.inputText,
        decoration: InputDecoration(
          hintText: hint,
          hintStyle:
              AppTextStyles.inputText.copyWith(color: const Color(0xFF9E9E9E)),
          border: InputBorder.none,
          contentPadding:
              const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
        ),
      ),
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
          onPressed: _isLoading ? null : _handleSimpan,
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
        'Ubah Profil',
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
}
