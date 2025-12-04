import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:hotel_reservation/config/constants.dart';
import 'package:hotel_reservation/models/user.dart';
import 'api_service.dart';

class AuthService {
  //Register
  static Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
    String? phone,
  }) async {
    final response = await ApiService.post(
      AppConstants.REGISTER,
      body: {
        'name': name,
        'email': email,
        'password': password,
        'password_confirmation': passwordConfirmation,
        'phone': phone,
      },
      requiresAuth: false,
    );

    if (response['success']) {
      final token = response['data']['token'];
      final user = User.fromJson(response['data']['user']);

      await _saveAuthData(token, user);

      return {'success': true, 'user': user, 'token': token};
    }

    throw Exception(response['message'] ?? 'Registration failed');
  }

  //Login
  static Future<Map<String, dynamic>> login({
    required String email,
    required String password,
  }) async {
    final response = await ApiService.post(
      AppConstants.LOGIN,
      body: {'email': email, 'password': password},
      requiresAuth: false,
    );

    if (response['success']) {
      final token = response['data']['token'];
      final user = User.fromJson(response['data']['user']);

      await _saveAuthData(token, user);

      return {'success': true, 'user': user, 'token': token};
    }

    throw Exception(response['message'] ?? 'Login failed');
  }

  //Logout
  static Future<void> Logout() async {
    try {
      await ApiService.post(AppConstants.LOGOUT);
    } catch (e) {
      // clear local data
    } finally {
      await _clearAuthData();
    }
  }

  // Get Current User
  static Future<User?> getCurrentUser() async {
    try {
      final response = await ApiService.get(AppConstants.ME);

      if (response['success']) {
        return User.fromJson(response['data']);
      }
    } catch (e) {
      return null;
    }
    return null;
  }

  // Check if logged in
  static Future<bool> isLoggedIn() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString(AppConstants.TOKEN_KEY);
    return token != null && token.isNotEmpty;
  }

  // Get saved user
  static Future<User?> getSavedUser() async {
    final prefs = await SharedPreferences.getInstance();
    final userJson = prefs.getString(AppConstants.USER_KEY);

    if (userJson != null) {
      return User.fromJson(jsonDecode(userJson));
    }
    return null;
  }

  // Get token
  static Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString(AppConstants.TOKEN_KEY);
  }

  // Private: Save auth data
  static Future<void> _saveAuthData(String token, User user) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(AppConstants.TOKEN_KEY, token);
    await prefs.setString(AppConstants.USER_KEY, jsonEncode(user.toJson()));
  }

  // Private: Clear auth data
  static Future<void> _clearAuthData() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(AppConstants.TOKEN_KEY);
    await prefs.remove(AppConstants.USER_KEY);
  }
}
