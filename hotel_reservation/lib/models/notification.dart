class AppNotification {
  final int id;
  final int userId;
  final String title;
  final String message;
  final String type;
  final bool isRead;
  final String? createdAt;
  final String? updatedAt;

  AppNotification({
    required this.id,
    required this.userId,
    required this.title,
    required this.message,
    required this.type,
    required this.isRead,
    this.createdAt,
    this.updatedAt,
  });

  bool isReminder() => type == 'reminder';
  bool isWarning() => type == 'warning';
  bool isInfo() => type == 'info';

  factory AppNotification.fromJson(Map<String, dynamic> json) {
    return AppNotification(
      id: json[['id']],
      userId: json['user_id'],
      title: json['title'],
      message: json['message'],
      type: json['type'],
      isRead: json['is_read'] == 1 || json['is_read'] == true,
      createdAt: json['createdd_at'],
      updatedAt: json['updated_at'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'user_id': userId,
      'title': title,
      'message': message,
      'type': type,
      'is_read': isRead,
      'created_at': createdAt,
      'update_at': updatedAt,
    };
  }
}
