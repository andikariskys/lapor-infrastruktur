import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';

// Conditional import for web platform
import 'location_service_stub.dart'
    if (dart.library.html) 'location_service_web.dart' as platform_location;

class LocationData {
  final double latitude;
  final double longitude;
  final String address;

  LocationData({
    required this.latitude,
    required this.longitude,
    required this.address,
  });

  String get koordinat => '${latitude.toStringAsFixed(6)}, ${longitude.toStringAsFixed(6)}';
}

class LocationService {
  // Separate Dio instance for geocoding (no auth needed)
  static final Dio _geoDio = Dio(
    BaseOptions(
      connectTimeout: const Duration(seconds: 10),
      receiveTimeout: const Duration(seconds: 10),
      headers: {
        'User-Agent': 'LaporInfrastruktur/1.0',
        'Accept-Language': 'id',
      },
    ),
  );

  /// Get current location with address (reverse geocoding)
  static Future<LocationData> getCurrentLocation() async {
    final coords = await platform_location.getCurrentPosition();

    // Reverse geocode to get address
    final address = await reverseGeocode(coords['latitude']!, coords['longitude']!);

    return LocationData(
      latitude: coords['latitude']!,
      longitude: coords['longitude']!,
      address: address,
    );
  }

  /// Reverse geocode coordinates to human-readable address using OpenStreetMap Nominatim
  static Future<String> reverseGeocode(double lat, double lon) async {
    try {
      final response = await _geoDio.get(
        'https://nominatim.openstreetmap.org/reverse',
        queryParameters: {
          'format': 'json',
          'lat': lat,
          'lon': lon,
          'zoom': 18,
          'addressdetails': 1,
        },
      );

      if (response.statusCode == 200) {
        final data = response.data as Map<String, dynamic>;
        final address = data['address'] as Map<String, dynamic>?;

        if (address != null) {
          // Build a readable address from components
          final parts = <String>[];

          // Road/street name
          final road = address['road'] ?? address['pedestrian'] ?? address['footway'];
          if (road != null) parts.add(road);

          // Village/suburb
          final village = address['village'] ?? address['suburb'] ?? address['neighbourhood'];
          if (village != null) parts.add(village);

          // City/county
          final city = address['city'] ?? address['town'] ?? address['county'];
          if (city != null) parts.add(city);

          if (parts.isNotEmpty) {
            return parts.join(', ');
          }
        }

        // Fallback to display_name
        final displayName = data['display_name'] as String?;
        if (displayName != null) {
          // Shorten the display name (take first 3 parts)
          final shortened = displayName.split(',').take(3).join(',').trim();
          return shortened;
        }
      }
    } catch (e) {
      debugPrint('Reverse geocoding error: $e');
    }

    return 'Lokasi tidak diketahui';
  }
}
