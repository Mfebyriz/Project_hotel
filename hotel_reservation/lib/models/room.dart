class Room {
  final int id;
  final String roomNumber;
  final String roomType;
  final double price;
  final String? description;
  final String? imageUrl;
  final String status;
  final int capacity;
  final String? createdAt;
  final String? updatedAt;

  Room({
    required this.id,
    required this.roomNumber,
    required this.roomType,
    required this.price,
    this.description,
    this.imageUrl,
    required this.status,
    required this.capacity,
    this.createdAt,
    this.updatedAt,
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

  factory Room.fromJson(Map<String, dynamic> json) {
    return Room(
      id: json['id'],
      roomNumber: json['room_number'],
      roomType: json['room_type'],
      price: double.parse(json['price'].toString()),
      description: json['description'],
      imageUrl: json['image_url'],
      status: json['status'],
      capacity: json['capacity'] ?? 2,
      createdAt: json['created_at'],
      updatedAt: json['updated'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'room_number': roomNumber,
      'room_type': roomType,
      'price': price,
      'description': description,
      'image_url': imageUrl,
      'status': status,
      'capacity': capacity,
      'created_at': createdAt,
      'updated_at': updatedAt,
    };
  }
}
