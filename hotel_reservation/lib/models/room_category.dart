class RoomCategory {
  final int id;
  final String name;
  final double price;
  final String? description;
  final String? imageUrl;
  final int capacity;
  final int? roomsCount;
  final String? createdAt;
  final String? updatedAt;

  RoomCategory({
    required this.id,
    required this.name,
    required this.price,
    this.description,
    this.imageUrl,
    required this.capacity,
    this.roomsCount,
    this.createdAt,
    this.updatedAt,
  });

  factory RoomCategory.fromJson(Map<String, dynamic> json) {
    return RoomCategory(
      id: json['id'],
      name: json['name'],
      price: double.parse(json['price'].toString()),
      description: json['description'],
      imageUrl: json['image_url'],
      capacity: json['capacity'] ?? 2,
      roomsCount: json['rooms_count'],
      createdAt: json['created_at'],
      updatedAt: json['updated_at'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'price': price,
      'description': description,
      'image_url': imageUrl,
      'capacity': capacity,
      'created_at': createdAt,
      'update_at': updatedAt,
    };
  }
}
