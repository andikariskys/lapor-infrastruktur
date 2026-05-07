import 'package:flutter/material.dart';
import 'package:lapor_infrastruktur/screens/auth/login_screen.dart';

void main() {
  runApp(const MainApp());
}

class MainApp extends StatelessWidget {
  const MainApp({super.key});

  @override

  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Lapor Infrastruktur',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(
          seedColor: const Color(0xFF1A3C9A),
        ),
        useMaterial3: true,
      ),
      home: const LoginScreen(),
    );
  }
}
