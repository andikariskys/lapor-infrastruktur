// ignore: avoid_web_libraries_in_flutter
import 'dart:html' as html;
import 'dart:async';

/// Get current position using browser's Geolocation API
Future<Map<String, double>> getCurrentPosition() async {
  final completer = Completer<Map<String, double>>();

  html.window.navigator.geolocation.getCurrentPosition().then((position) {
    completer.complete({
      'latitude': position.coords!.latitude! as double,
      'longitude': position.coords!.longitude! as double,
    });
  }).catchError((error) {
    completer.completeError(
      Exception('Gagal mendapatkan lokasi: Pastikan GPS/Location diizinkan di browser.'),
    );
  });

  return completer.future;
}
