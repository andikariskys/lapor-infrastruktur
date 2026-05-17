/// Stub implementation for non-web platforms
Future<Map<String, double>> getCurrentPosition() async {
  throw UnsupportedError('Geolocation hanya didukung di platform web.');
}
