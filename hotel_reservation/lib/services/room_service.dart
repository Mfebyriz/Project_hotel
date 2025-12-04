import 'package:hotel_reservation/config/constants.dart';
import 'package:hotel_reservation/models/room.dart';
import 'api_service.dart';

class RoomService {
  // Get all rooms
  static Future<List<Room>> getRooms({
    String? search,
    String? status,
    String? roomType,
    double? minPrice,
    double? maxPrice,
  }) async {
    String endpoint = AppConstants.ROOMS;
    List<String> params = [];

    if (search != null && search.isNotEmpty) {
      params.add('search=$search');
    }
    if (status != null) {
      params.add('status=$status');
    }
    if (roomType != null) {
      params.add('room_type=$roomType');
    }
    if (minPrice != null) {
      params.add('min_price=$minPrice');
    }
    if (maxPrice != null) {
      params.add('max_price=$maxPrice');
    }

    if (params.isNotEmpty) {
      endpoint += '?${params.join('&')}';
    }

    final response = await ApiService.get(endpoint, requiresAuth: false);

    if (response['success']) {
      final List<dynamic> data = response['data']['data'];
      return data.map((json) => Room.fromJson(json)).toList();
    }

    return [];
  }

  // Get room by ID
  static Future<Room?> getRoomById(int id) async {
    final response = await ApiService.get(
      '${AppConstants.ROOMS}/$id',
      requiresAuth: false,
    );

    if (response['success']) {
      return Room.fromJson(response['data']);
    }

    return null;
  }

  // Create room (Admin)
  static Future<Room> createRoom(Map<String, dynamic> roomData) async {
    final response = await ApiService.post(AppConstants.ROOMS, body: roomData);

    if (response['success']) {
      return Room.fromJson(response['data']);
    }

    throw Exception(response['message'] ?? 'Failed to create room');
  }

  // Update room (Admin)
  static Future<Room> updateRoom(int id, Map<String, dynamic> roomData) async {
    final response = await ApiService.put(
      '${AppConstants.ROOMS}/$id',
      body: roomData,
    );

    if (response['success']) {
      return Room.fromJson(response['data']);
    }

    throw Exception(response['message'] ?? 'Failed to update room');
  }

  // Delete room (Admin)
  static Future<void> deleteRoom(int id) async {
    final response = await ApiService.delete('${AppConstants.ROOMS}/$id');

    if (!response['success']) {
      throw Exception(response['message'] ?? 'Failed to delete room');
    }
  }
}
