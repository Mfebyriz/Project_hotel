class User {
  final int id;
  final String name;
  final String email;
  final String? phone;
  final String role;
  final String? emailVerifiedAt;
  final String? createdAt;
  final String? updateAt;

  User({
    required this.id,
    required this.name,
    required this.email,
    this.phone,
    required this.role,
    this.emailVerifiedAt,
    this.createdAt,
    this.updateAt,
  });

  bool isAdmin() => role == 'admin';
  bool isCustomer() => role == 'customer';

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      name: json['name'],
      email: json['email'],
      phone: json['phone'],
      role: json['role'],
      emailVerifiedAt: json['email_verified_at'],
      createdAt: json['created_at'],
      updateAt: json['updated_at'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'phone': phone,
      'role': role,
      'email_verifed_at': emailVerifiedAt,
      'created_at': createdAt,
      'updated_at': updateAt,
    };
  }
}
