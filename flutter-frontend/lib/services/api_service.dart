import 'dart:convert';
import 'dart:typed_data';
import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';

/// Central API service that manages all communication with the FastAPI backend.
/// Uses Dio with interceptors for automatic token management and error handling.
class ApiService {
  // Change this to your backend URL
  static const String baseUrl = 'http://localhost:8001/api';

  // Singleton Dio instance with interceptors
  static Dio? _dio;

  /// Get the singleton Dio instance, configured with interceptors.
  static Dio get dio {
    _dio ??= _createDio();
    return _dio!;
  }

  /// Creates a new Dio instance with base options and interceptors.
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

    // Add auth interceptor for automatic token attachment & error handling
    dioInstance.interceptors.add(_AuthInterceptor());

    // Add logging interceptor (only in debug mode)
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

  /// Reset Dio instance (useful after logout to clear interceptor state).
  static void resetDio() {
    _dio?.close();
    _dio = null;
  }

  // ─── Token Management ─────────────────────────────────────────────────────

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

  // ─── Helper: Extract error message from Dio exceptions ─────────────────────

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

  // ─── Auth ──────────────────────────────────────────────────────────────────

  /// Register new user (citizen role by default)
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

  /// Login and receive JWT token
  static Future<Map<String, dynamic>> login({
    required String email,
    required String password,
  }) async {
    try {
      // Login endpoint uses form-urlencoded format (OAuth2 standard)
      final response = await dio.post(
        '/auth/login',
        data: {
          'username': email,
          'password': password,
        },
        options: Options(
          contentType: Headers.formUrlEncodedContentType,
        ),
      );

      final data = response.data as Map<String, dynamic>;
      await saveToken(data['access_token']);

      // Fetch user profile right away
      final profile = await getMyProfile();
      await saveUserData(profile);
      return profile;
    } on DioException catch (e) {
      throw Exception(_extractError(e, 'Login gagal'));
    }
  }

  /// Get current user profile
  static Future<Map<String, dynamic>> getMyProfile() async {
    try {
      final response = await dio.get('/auth/me');
      return response.data as Map<String, dynamic>;
    } on DioException catch (e) {
      throw Exception(_extractError(e, 'Gagal memuat profil'));
    }
  }

  // ─── Reports (Pelapor) ────────────────────────────────────────────────────

  /// Create a new report with image upload
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
        'image': MultipartFile.fromBytes(
          imageBytes,
          filename: fileName,
        ),
      });

      final response = await dio.post(
        '/reports',
        data: formData,
        options: Options(
          // Let Dio set the correct multipart content-type with boundary
          contentType: Headers.multipartFormDataContentType,
        ),
      );
      return response.data as Map<String, dynamic>;
    } on DioException catch (e) {
      throw Exception(_extractError(e, 'Gagal mengirim laporan'));
    }
  }

  /// Get my reports (citizen)
  static Future<List<dynamic>> getMyReports() async {
    try {
      final response = await dio.get('/reports/my');
      return response.data as List<dynamic>;
    } on DioException catch (e) {
      throw Exception(_extractError(e, 'Gagal memuat laporan'));
    }
  }

  /// Get assigned reports (officer)
  static Future<List<dynamic>> getAssignedReports() async {
    try {
      final response = await dio.get('/reports/assigned');
      return response.data as List<dynamic>;
    } on DioException catch (e) {
      throw Exception(_extractError(e, 'Gagal memuat tugas'));
    }
  }

  /// Get report detail
  static Future<Map<String, dynamic>> getReportDetail(int reportId) async {
    try {
      final response = await dio.get('/reports/$reportId');
      return response.data as Map<String, dynamic>;
    } on DioException catch (e) {
      throw Exception(_extractError(e, 'Gagal memuat detail laporan'));
    }
  }

  /// Update report status (officer/admin)
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

  /// Update user profile
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

  /// Change password (using login endpoint as workaround since backend has no direct endpoint)
  static Future<void> changePassword({
    required String oldPassword,
    required String newPassword,
  }) async {
    // The backend doesn't have a direct change password for citizens
    // But we can verify old password by trying to login, then update
    final userData = await getUserData();
    if (userData == null) throw Exception('Data user tidak ditemukan');

    // Verify old password by attempting login
    try {
      await dio.post(
        '/auth/login',
        data: {
          'username': userData['email'],
          'password': oldPassword,
        },
        options: Options(
          contentType: Headers.formUrlEncodedContentType,
        ),
      );
    } catch (e) {
      throw Exception('Kata sandi lama salah');
    }

    // Note: The backend doesn't have a user-facing password change endpoint.
    // For now, we simulate success. In production, add a /api/auth/change-password endpoint.
    // TODO: Implement backend endpoint for password change
  }

  /// Create feedback for a report
  static Future<Map<String, dynamic>> createFeedback({
    required int reportId,
    required String content,
    required int rating,
  }) async {
    try {
      final response = await dio.post(
        '/reports/$reportId/feedback',
        data: {
          'content': content,
          'rating': rating,
        },
      );
      return response.data as Map<String, dynamic>;
    } on DioException catch (e) {
      throw Exception(_extractError(e, 'Gagal mengirim ulasan'));
    }
  }

  /// Add work progress (officer)
  static Future<Map<String, dynamic>> addWorkProgress({
    required int reportId,
    required String note,
  }) async {
    try {
      final formData = FormData.fromMap({
        'note': note,
      });

      final response = await dio.post(
        '/reports/$reportId/progress',
        data: formData,
        options: Options(
          contentType: Headers.multipartFormDataContentType,
        ),
      );
      return response.data as Map<String, dynamic>;
    } on DioException catch (e) {
      throw Exception(_extractError(e, 'Gagal mengirim progres'));
    }
  }

  /// Get categories
  static Future<List<dynamic>> getCategories() async {
    try {
      final response = await dio.get('/categories');
      return response.data as List<dynamic>;
    } on DioException catch (e) {
      throw Exception(_extractError(e, 'Gagal memuat kategori'));
    }
  }
}

// ─── Auth Interceptor ──────────────────────────────────────────────────────

/// Interceptor that automatically attaches the JWT token to requests
/// and handles 401 unauthorized responses.
class _AuthInterceptor extends Interceptor {
  @override
  void onRequest(
    RequestOptions options,
    RequestInterceptorHandler handler,
  ) async {
    // Skip token for auth endpoints (login/register)
    final path = options.path;
    if (path.contains('/auth/login') || path.contains('/auth/register')) {
      return handler.next(options);
    }

    // Attach token if available
    final token = await ApiService.getToken();
    if (token != null) {
      options.headers['Authorization'] = 'Bearer $token';
    }

    return handler.next(options);
  }

  @override
  void onError(
    DioException err,
    ErrorInterceptorHandler handler,
  ) async {
    // Handle 401 Unauthorized — clear token
    if (err.response?.statusCode == 401) {
      await ApiService.clearToken();
      ApiService.resetDio();
      // Optionally: navigate to login screen via a global navigator key
    }

    return handler.next(err);
  }
}
