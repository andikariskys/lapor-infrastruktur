import 'dart:convert';
import 'dart:typed_data';
import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';

/// Servis API utama buat ngatur semua komunikasi ke backend FastAPI.
/// Pake Dio plus interceptor biar token otomatis keurus dan gampang nanganin error.
class ApiService {
  // Ganti ini sama URL backend
  static const String baseUrl = 'http://localhost:8001/api';

  // Singleton Dio instance yang udah dipasangin interceptor
  static Dio? _dio;

  /// Ambil instance singleton Dio yang udah disetting interceptornya.
  static Dio get dio {
    _dio ??= _createDio();
    return _dio!;
  }

  /// Bikin instance Dio baru sekalian sama opsi default dan interceptor.
  static Dio _createDio() {
    final dioInstance = Dio(
      BaseOptions(
        baseUrl: baseUrl,
        connectTimeout: const Duration(seconds: 15),
        receiveTimeout: const Duration(seconds: 15),
        sendTimeout: const Duration(seconds: 30),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ),
    );

    // Masukin auth interceptor biar token otomatis nyelip & error kehandle
    dioInstance.interceptors.add(_AuthInterceptor());

    // Tambahin logging interceptor (cuma buat mode debug aja)
    assert(() {
      dioInstance.interceptors.add(
        LogInterceptor(
          requestBody: true,
          responseBody: true,
          logPrint: (obj) => print('🌐 DIO: $obj'),
        ),
      );
      return true;
    }());

    return dioInstance;
  }

  /// Reset instance Dio (buat ngebersihin state interceptor).
  static void resetDio() {
    _dio?.close();
    _dio = null;
  }

  // Ngurusin Token

  static Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('access_token');
  }

  static Future<void> saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('access_token', token);
  }

  static Future<void> clearToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('access_token');
    await prefs.remove('user_data');
  }

  static Future<void> saveUserData(Map<String, dynamic> userData) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('user_data', json.encode(userData));
  }

  static Future<Map<String, dynamic>?> getUserData() async {
    final prefs = await SharedPreferences.getInstance();
    final data = prefs.getString('user_data');
    if (data != null) {
      return json.decode(data) as Map<String, dynamic>;
    }
    return null;
  }

  // Helper: Ambil pesan error dari exceptionnya Dio

  static String _extractError(DioException e, String fallback) {
    if (e.response?.data != null) {
      try {
        final data = e.response!.data;
        if (data is Map<String, dynamic>) {
          return data['detail']?.toString() ?? fallback;
        }
      } catch (_) {}
    }
    if (e.type == DioExceptionType.connectionTimeout ||
        e.type == DioExceptionType.receiveTimeout) {
      return 'Koneksi timeout. Periksa jaringan Anda.';
    }
    if (e.type == DioExceptionType.connectionError) {
      return 'Tidak dapat terhubung ke server.';
    }
    return fallback;
  }

  // Autentikasi

  /// Daftarin user baru (defaultnya jadi warga/pelapor)
  static Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String phone,
    required String password,
  }) async {
    try {
      final response = await dio.post(
        '/auth/register',
        data: {
          'name': name,
          'email': email,
          'phone': phone,
          'password': password,
          'role': 'citizen',
        },
      );
      return response.data as Map<String, dynamic>;
    } on DioException catch (e) {
      throw Exception(_extractError(e, 'Registrasi gagal'));
    }
  }

  /// Buat login dan dapetin token JWT
  static Future<Map<String, dynamic>> login({
    required String email,
    required String password,
  }) async {
    try {
      // Endpoint login ini pakenya format form-urlencoded (standar OAuth2)
      final response = await dio.post(
        '/auth/login',
        data: {'username': email, 'password': password},
        options: Options(contentType: Headers.formUrlEncodedContentType),
      );

      final data = response.data as Map<String, dynamic>;
      await saveToken(data['access_token']);

      // Langsung tarik aja data profil usernya
      final profile = await getMyProfile();
      await saveUserData(profile);
      return profile;
    } on DioException catch (e) {
      throw Exception(_extractError(e, 'Login gagal'));
    }
  }

  /// Ambil data profil user yang lagi login
  static Future<Map<String, dynamic>> getMyProfile() async {
    try {
      final response = await dio.get('/auth/me');
      return response.data as Map<String, dynamic>;
    } on DioException catch (e) {
      throw Exception(_extractError(e, 'Gagal memuat profil'));
    }
  }

  // Laporan (Buat Pelapor)

  /// Bikin laporan baru sekalian upload gambarnya
  static Future<Map<String, dynamic>> createReport({
    required String description,
    required double latitude,
    required double longitude,
    required int categoryId,
    required Uint8List imageBytes,
    required String fileName,
  }) async {
    try {
      final formData = FormData.fromMap({
        'description': description,
        'latitude': latitude.toString(),
        'longitude': longitude.toString(),
        'category_id': categoryId.toString(),
        'image': MultipartFile.fromBytes(imageBytes, filename: fileName),
      });

      final response = await dio.post(
        '/reports',
        data: formData,
        options: Options(
          // ngatur content type multipartnya biar pas
          contentType: Headers.multipartFormDataContentType,
        ),
      );
      return response.data as Map<String, dynamic>;
    } on DioException catch (e) {
      throw Exception(_extractError(e, 'Gagal mengirim laporan'));
    }
  }

  /// Ambil list laporan (warga/pelapor)
  static Future<List<dynamic>> getMyReports() async {
    try {
      final response = await dio.get('/reports/my');
      return response.data as List<dynamic>;
    } on DioException catch (e) {
      throw Exception(_extractError(e, 'Gagal memuat laporan'));
    }
  }

  /// Ambil list tugas/laporan buat petugas
  static Future<List<dynamic>> getAssignedReports() async {
    try {
      final response = await dio.get('/reports/assigned');
      return response.data as List<dynamic>;
    } on DioException catch (e) {
      throw Exception(_extractError(e, 'Gagal memuat tugas'));
    }
  }

  /// Ambil detail spesifik laporannya
  static Future<Map<String, dynamic>> getReportDetail(int reportId) async {
    try {
      final response = await dio.get('/reports/$reportId');
      return response.data as Map<String, dynamic>;
    } on DioException catch (e) {
      throw Exception(_extractError(e, 'Gagal memuat detail laporan'));
    }
  }

  /// Update status laporan (khusus petugas/admin)
  static Future<Map<String, dynamic>> updateReportStatus({
    required int reportId,
    required String status,
  }) async {
    try {
      final response = await dio.patch(
        '/reports/$reportId/verify',
        data: {'status': status},
      );
      return response.data as Map<String, dynamic>;
    } on DioException catch (e) {
      throw Exception(_extractError(e, 'Gagal mengupdate status'));
    }
  }

  /// Update data profil user
  static Future<Map<String, dynamic>> updateMyProfile({
    String? name,
    String? phone,
  }) async {
    try {
      final body = <String, dynamic>{};
      if (name != null) body['name'] = name;
      if (phone != null) body['phone'] = phone;

      final response = await dio.patch('/users/me', data: body);
      final data = response.data as Map<String, dynamic>;
      await saveUserData(data);
      return data;
    } on DioException catch (e) {
      throw Exception(_extractError(e, 'Gagal memperbarui profil'));
    }
  }

  /// Ganti password (ngakalin pake endpoint login soalnya dari backend belum ada endpoint khususnya)
  static Future<void> changePassword({
    required String oldPassword,
    required String newPassword,
  }) async {
    // Backend emang ga punya endpoint khusus ganti password buat warga
    // Ngakalin verifikasi password lama dengan cara nyoba login, baru deh diupdate
    final userData = await getUserData();
    if (userData == null) throw Exception('Data user tidak ditemukan');

    // Verifikasi password lama pake cara nembak endpoint login
    try {
      await dio.post(
        '/auth/login',
        data: {'username': userData['email'], 'password': oldPassword},
        options: Options(contentType: Headers.formUrlEncodedContentType),
      );
    } catch (e) {
      throw Exception('Kata sandi lama salah');
    }

    // Catatan: Backend belum nyediain endpoint buat ganti password user.
    // TODO: Bikin endpoint di backend buat fitur ganti password
  }

  /// Ngasih feedback/ulasan buat suatu laporan
  static Future<Map<String, dynamic>> createFeedback({
    required int reportId,
    required String content,
    required int rating,
  }) async {
    try {
      final response = await dio.post(
        '/reports/$reportId/feedback',
        data: {'content': content, 'rating': rating},
      );
      return response.data as Map<String, dynamic>;
    } on DioException catch (e) {
      throw Exception(_extractError(e, 'Gagal mengirim ulasan'));
    }
  }

  /// Nambahin update progres kerjaan (buat petugas)
  static Future<Map<String, dynamic>> addWorkProgress({
    required int reportId,
    required String note,
  }) async {
    try {
      final formData = FormData.fromMap({'note': note});

      final response = await dio.post(
        '/reports/$reportId/progress',
        data: formData,
        options: Options(contentType: Headers.multipartFormDataContentType),
      );
      return response.data as Map<String, dynamic>;
    } on DioException catch (e) {
      throw Exception(_extractError(e, 'Gagal mengirim progres'));
    }
  }

  /// Ambil list kategori laporan
  static Future<List<dynamic>> getCategories() async {
    try {
      final response = await dio.get('/categories');
      return response.data as List<dynamic>;
    } on DioException catch (e) {
      throw Exception(_extractError(e, 'Gagal memuat kategori'));
    }
  }
}

// Auth Interceptor

/// Interceptor otomatis nyelipin token JWT tiap request
/// Ngurusi respon belum login / unauthorized (401).
class _AuthInterceptor extends Interceptor {
  @override
  void onRequest(
    RequestOptions options,
    RequestInterceptorHandler handler,
  ) async {
    // Gapake token kalau ke endpoint login atau register
    final path = options.path;
    if (path.contains('/auth/login') || path.contains('/auth/register')) {
      return handler.next(options);
    }

    // Selipin token
    final token = await ApiService.getToken();
    if (token != null) {
      options.headers['Authorization'] = 'Bearer $token';
    }

    return handler.next(options);
  }

  @override
  void onError(DioException err, ErrorInterceptorHandler handler) async {
    // Kalau dapet 401 Unauthorized — hapus aja tokennya
    if (err.response?.statusCode == 401) {
      await ApiService.clearToken();
      ApiService.resetDio();
      // Opsional: lempar user balik ke halaman login lewat global navigator key
    }

    return handler.next(err);
  }
}
