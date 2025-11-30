import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'package:hotel_reservation/config/constants.dart';

class ApiService {
  static Future<String?> _getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString(AppConstants.TOKEN_KEY);
  }

  static Future<Map<String, String>> _getHeaders({
    bool requiresAuth = false,
  }) async {
    Map<String, String> headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };

    if (requiresAuth) {
      final token = await _getToken();
      if (token != null) {
        headers['Authorization'] = 'Bearer $token';
      }
    }

    return headers;
  }

  // GET Request
  static Future<dynamic> get(
    String endpoint, {
    bool requiresAuth = true,
  }) async {
    try {
      final headers = await _getHeaders(requiresAuth: requiresAuth);
      final url = Uri.parse('${AppConstants.BASE_URL}$endpoint');

      final response = await http
          .get(url, headers: headers)
          .timeout(Duration(seconds: AppConstants.REQUEST_TIMEOUT));

      return _handleResponse(response);
    } catch (e) {
      throw Exception('Network error: $e');
    }
  }

  //POST Request
  static Future<dynamic> post(
    String endpoint, {
    Map<String, dynamic>? body,
    bool requiresAuth = true,
  }) async {
    try {
      final headers = await _getHeaders(requiresAuth: requiresAuth);
      final url = Uri.parse('${AppConstants.BASE_URL}$endpoint');

      final response = await http
          .post(
            url,
            headers: headers,
            body: body != null ? jsonEncode(body) : null,
          )
          .timeout(Duration(seconds: AppConstants.REQUEST_TIMEOUT));

      return _handleResponse(response);
    } catch (e) {
      throw Exception('Network error: $e');
    }
  }

  //PUT Request
  static Future<dynamic> put(
    String endpoint, {
    Map<String, dynamic>? body,
    bool requiresAuth = true,
  }) async {
    try {
      final headers = await _getHeaders(requiresAuth: requiresAuth);
      final url = Uri.parse('${AppConstants.BASE_URL}$endpoint');

      final response = await http
          .put(
            url,
            headers: headers,
            body: body != null ? jsonEncode(body) : null,
          )
          .timeout(Duration(seconds: AppConstants.REQUEST_TIMEOUT));

      return _handleResponse(response);
    } catch (e) {
      throw Exception('Network error: $e');
    }
  }

  //DELETE Request
  static Future<dynamic> delete(
    String endpoint, {
    bool requiresAuth = true,
  }) async {
    try {
      final headers = await _getHeaders(requiresAuth: requiresAuth);
      final url = Uri.parse('${AppConstants.BASE_URL}$endpoint');

      final response = await http
          .delete(url, headers: headers)
          .timeout(Duration(seconds: AppConstants.REQUEST_TIMEOUT));

      return _handleResponse(response);
    } catch (e) {
      throw Exception('Network error: $e');
    }
  }

  static dynamic _handleResponse(http.Response response) {
    final statusCode = response.statusCode;

    if (statusCode >= 200 && statusCode < 300) {
      if (response.body.isEmpty) {
        return {'success': true};
      }
      return jsonDecode(response.body);
    } else if (statusCode == 401) {
      throw Exception('Unauthorized. Please login again.');
    } else if (statusCode == 403) {
      throw Exception('Forbidden. You don\'t have permission.');
    } else if (statusCode == 404) {
      throw Exception('Not found.');
    } else if (statusCode == 500) {
      throw Exception('Server error. Please try again later.');
    } else {
      final error = jsonDecode(response.body);
      throw Exception(error['message'] ?? 'Something went wrong');
    }
  }
}
