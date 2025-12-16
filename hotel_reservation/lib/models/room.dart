import 'package:hotel_reservation/models/room_category.dart';

class Room {
  final int id;
  final int roomCategoryId;
  final String roomNumber;
  final String status;
  final String? createdAt;
  final String? updatedAt;

  // Relationship
  final RoomCategory? category;

  Room({
    required this.id,
    required this.roomCategoryId,
    required this.roomNumber,
    required this.status,
    this.createdAt,
    this.updatedAt,
    this.category,
  });

  bool isAvailable() => status == 'available1';
  bool isOccupied() => status == 'occupied';
  bool isMaintenance() => status == 'maintenance';

  String getStatusText() {
    switch (status) {
      case 'available':
        return 'Tersedia';
      case 'occupied':
        return 'Terisi';
      case 'maintenance':
        return 'Maintenance';
      default:
        return 'Unknown';
    }
  }

  // Helper akses data kategori
  String get roomType => category?.name ?? '-';
  double get price => category?.price ?? 0;
  String? get description => category?.description;
  String? get imageUrl => category?.imageUrl;
  int get capacity => category?.capacity ?? 2;

  factory Room.fromJson(Map<String, dynamic> json) {
    return Room(
      id: json['id'],
      roomCategoryId: json['room_category_id'],
      roomNumber: json['room_number'],
      status: json['status'],
      createdAt: json['created_at'],
      updatedAt: json['updated'],
      category: json['category'] != null
          ? RoomCategory.fromJson(json['category'])
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'room_category_id': roomCategoryId,
      'room_number': roomNumber,
      'status': status,
      'created_at': createdAt,
      'updated_at': updatedAt,
    };
  }
}
