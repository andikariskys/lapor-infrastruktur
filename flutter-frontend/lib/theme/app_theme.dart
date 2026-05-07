import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class AppColors {
  static const Color primaryBlue = Color(0xFF1A3C9A);
  static const Color accentOrange = Color(0xFFE8720C);
  static const Color background = Color(0xFFF0F2F8);
  static const Color cardBackground = Colors.white;
  static const Color textDark = Color(0xFF1A1A2E);
  static const Color textGrey = Color(0xFF8A94A6);
  static const Color inputBorder = Color(0xFFDDE2EE);
  static const Color inputActiveBorder = Color(0xFF1A3C9A);
  static const Color hintText = Color(0xFFB0BAC9);
}

class AppTextStyles {
  static TextStyle get appTitle => GoogleFonts.inter(
        fontSize: 30,
        fontWeight: FontWeight.w800,
        color: AppColors.primaryBlue,
        height: 1.2,
      );

  static TextStyle get appSubtitle => GoogleFonts.inter(
        fontSize: 14,
        fontWeight: FontWeight.w400,
        color: AppColors.textGrey,
      );

  static TextStyle get label => GoogleFonts.inter(
        fontSize: 13,
        fontWeight: FontWeight.w600,
        color: AppColors.textDark,
      );

  static TextStyle get inputText => GoogleFonts.inter(
        fontSize: 14,
        fontWeight: FontWeight.w400,
        color: AppColors.textDark,
      );

  static TextStyle get buttonText => GoogleFonts.inter(
        fontSize: 16,
        fontWeight: FontWeight.w700,
        color: Colors.white,
        letterSpacing: 0.3,
      );

  static TextStyle get linkText => GoogleFonts.inter(
        fontSize: 14,
        fontWeight: FontWeight.w700,
        color: AppColors.primaryBlue,
      );

  static TextStyle get forgotPassword => GoogleFonts.inter(
        fontSize: 13,
        fontWeight: FontWeight.w700,
        color: AppColors.primaryBlue,
      );

  static TextStyle get bodyText => GoogleFonts.inter(
        fontSize: 14,
        fontWeight: FontWeight.w400,
        color: AppColors.textGrey,
      );

  static TextStyle get adminText => GoogleFonts.inter(
        fontSize: 14,
        fontWeight: FontWeight.w700,
        color: AppColors.accentOrange,
      );
}
