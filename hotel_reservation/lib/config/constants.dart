class AppConstants {
  static const String BASE_URL = 'http://127.0.0.1:8000/api';

  // API endpoints
  static const String LOGIN = '/login';
  static const String REGISTER = '/register';
  static const String LOGOUT = '/logout';
  static const String ME = '/me';
  static const String ROOMS = '/rooms';
  static const String RESERVATIONS = '/reservations';
  static const String CUSTOMERS = '/customers';
  static const String PAYMENTS = '/payments';
  static const String NOTIFICATIONS = '/notifications';
  static const String REPORTS = '/reports';

  // Shared Preferences keys
  static const String TOKEN_KEY = 'auth_token';
  static const String USER_KEY = 'user_data';

  // API Settings
  static const int REQUEST_TIMEOUT = 30;
  static const int PRE_PAGE = 10;
}
