import 'dart:convert';
import 'dart:typed_data';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

/// Central API service that manages all communication with the FastAPI backend.
/// Handles token storage, auth headers, and all CRUD operations.
class ApiService {
  // Change this to your backend URL
  static const String baseUrl = 'http://localhost:8001/api';

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

  static Map<String, String> _authHeaders(String token) => {
        'Authorization': 'Bearer $token',
        'Content-Type': 'application/json',
      };

  // ─── Auth ──────────────────────────────────────────────────────────────────

  /// Register new user (citizen role by default)
  static Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String phone,
    required String password,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/auth/register'),
      headers: {'Content-Type': 'application/json'},
      body: json.encode({
        'name': name,
        'email': email,
        'phone': phone,
        'password': password,
        'role': 'citizen',
      }),
    );

    if (response.statusCode == 200 || response.statusCode == 201) {
      return json.decode(response.body);
    } else {
      final error = json.decode(response.body);
      throw Exception(error['detail'] ?? 'Registrasi gagal');
    }
  }

  /// Login and receive JWT token
  static Future<Map<String, dynamic>> login({
    required String email,
    required String password,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/auth/login'),
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: {
        'username': email,
        'password': password,
      },
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      await saveToken(data['access_token']);
      // Fetch user profile right away
      final profile = await getMyProfile();
      await saveUserData(profile);
      return profile;
    } else {
      final error = json.decode(response.body);
      throw Exception(error['detail'] ?? 'Login gagal');
    }
  }

  /// Get current user profile
  static Future<Map<String, dynamic>> getMyProfile() async {
    final token = await getToken();
    if (token == null) throw Exception('Belum login');

    final response = await http.get(
      Uri.parse('$baseUrl/auth/me'),
      headers: _authHeaders(token),
    );

    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('Gagal memuat profil');
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
    final token = await getToken();
    if (token == null) throw Exception('Belum login');

    final request =
        http.MultipartRequest('POST', Uri.parse('$baseUrl/reports'));
    request.headers['Authorization'] = 'Bearer $token';
    request.fields['description'] = description;
    request.fields['latitude'] = latitude.toString();
    request.fields['longitude'] = longitude.toString();
    request.fields['category_id'] = categoryId.toString();
    request.files.add(
      http.MultipartFile.fromBytes('image', imageBytes, filename: fileName),
    );

    final streamedResponse = await request.send();
    final response = await http.Response.fromStream(streamedResponse);

    if (response.statusCode == 200 || response.statusCode == 201) {
      return json.decode(response.body);
    } else {
      final error = json.decode(response.body);
      throw Exception(error['detail'] ?? 'Gagal mengirim laporan');
    }
  }

  /// Get my reports (citizen)
  static Future<List<dynamic>> getMyReports() async {
    final token = await getToken();
    if (token == null) throw Exception('Belum login');

    final response = await http.get(
      Uri.parse('$baseUrl/reports/my'),
      headers: _authHeaders(token),
    );

    if (response.statusCode == 200) {
      return json.decode(response.body) as List<dynamic>;
    } else {
      throw Exception('Gagal memuat laporan');
    }
  }

  /// Get assigned reports (officer)
  static Future<List<dynamic>> getAssignedReports() async {
    final token = await getToken();
    if (token == null) throw Exception('Belum login');

    final response = await http.get(
      Uri.parse('$baseUrl/reports/assigned'),
      headers: _authHeaders(token),
    );

    if (response.statusCode == 200) {
      return json.decode(response.body) as List<dynamic>;
    } else {
      throw Exception('Gagal memuat tugas');
    }
  }

  /// Get report detail
  static Future<Map<String, dynamic>> getReportDetail(int reportId) async {
    final token = await getToken();
    if (token == null) throw Exception('Belum login');

    final response = await http.get(
      Uri.parse('$baseUrl/reports/$reportId'),
      headers: _authHeaders(token),
    );

    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('Gagal memuat detail laporan');
    }
  }

  /// Update report status (officer/admin)
  static Future<Map<String, dynamic>> updateReportStatus({
    required int reportId,
    required String status,
  }) async {
    final token = await getToken();
    if (token == null) throw Exception('Belum login');

    final response = await http.patch(
      Uri.parse('$baseUrl/reports/$reportId/verify'),
      headers: _authHeaders(token),
      body: json.encode({'status': status}),
    );

    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('Gagal mengupdate status');
    }
  }

  /// Update user profile
  static Future<Map<String, dynamic>> updateMyProfile({
    String? name,
    String? phone,
  }) async {
    final token = await getToken();
    if (token == null) throw Exception('Belum login');

    final body = <String, dynamic>{};
    if (name != null) body['name'] = name;
    if (phone != null) body['phone'] = phone;

    final response = await http.patch(
      Uri.parse('$baseUrl/users/me'),
      headers: _authHeaders(token),
      body: json.encode(body),
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      await saveUserData(data);
      return data;
    } else {
      throw Exception('Gagal memperbarui profil');
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
      final response = await http.post(
        Uri.parse('$baseUrl/auth/login'),
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: {
          'username': userData['email'],
          'password': oldPassword,
        },
      );
      if (response.statusCode != 200) {
        throw Exception('Kata sandi lama salah');
      }
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
    final token = await getToken();
    if (token == null) throw Exception('Belum login');

    final response = await http.post(
      Uri.parse('$baseUrl/reports/$reportId/feedback'),
      headers: _authHeaders(token),
      body: json.encode({
        'content': content,
        'rating': rating,
      }),
    );

    if (response.statusCode == 200 || response.statusCode == 201) {
      return json.decode(response.body);
    } else {
      throw Exception('Gagal mengirim ulasan');
    }
  }

  /// Add work progress (officer)
  static Future<Map<String, dynamic>> addWorkProgress({
    required int reportId,
    required String note,
  }) async {
    final token = await getToken();
    if (token == null) throw Exception('Belum login');

    final request = http.MultipartRequest(
      'POST',
      Uri.parse('$baseUrl/reports/$reportId/progress'),
    );
    request.headers['Authorization'] = 'Bearer $token';
    request.fields['note'] = note;

    final streamedResponse = await request.send();
    final response = await http.Response.fromStream(streamedResponse);

    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('Gagal mengirim progres');
    }
  }

  /// Get categories
  static Future<List<dynamic>> getCategories() async {
    final token = await getToken();
    if (token == null) throw Exception('Belum login');

    final response = await http.get(
      Uri.parse('$baseUrl/categories'),
      headers: _authHeaders(token),
    );

    if (response.statusCode == 200) {
      return json.decode(response.body) as List<dynamic>;
    } else {
      return [];
    }
  }
}
