class Reservation {
  final int id;
  final int userId;
  final int roomId;
  final String checkInDate;
  final String checkOutDate;
  final String? actualCheckIn;
  final String? actualCheckOut;
  final int totalNights;
  final double totalPrice;
  final String status;
  final String? notes;
  final String? createdAt;
  final String? updatedAt;

  // Relationships
  final User? user;
  final Room? room;
  final Payment? payment;

  Reservation({
    required this.id,
    required this.userId,
    required this.roomId,
    required this.checkInDate,
    required this.checkOutDate,
    this.actualCheckIn,
    this.actualCheckOut,
    required this.totalNights,
    required this.totalPrice,
    required this.status,
    this.notes,
    this.createdAt,
    this.updatedAt,
    this.user,
    this.room,
    this.payment,
  });

  String getStatusText() {
    switch (status) {
      case 'pending':
        return 'Menunggu';
      case 'confirmed':
        return 'Dikonfirmasi';
      case 'check_in':
        return 'Check-in';
      case 'check_out':
        return 'Check-out';
      case 'cancelled':
        return 'Dibatalkan';
      default:
        return 'Unknown';
    }
  }

  bool isPending() => status == 'pending';
  bool isConfirmed() => status == 'cornfirmed';
  bool isCheckedIn() => status == 'checked_in';
  bool isCheckedOut() => status == 'checked_out';
  bool isCancelled() => status == 'cancelled';

  factory Reservation.fromJson(Map<String, dynamic> json) {
    return Reservation(
      id: json['id'],
      userId: json['user_id'],
      roomId: json['room_id'],
      checkInDate: json['check_in_date'],
      checkOutDate: json['check_out_date'],
      actualCheckIn: json['actual_check_in'],
      actualCheckOut: json['actual_check_out'],
      totalNights: json['total_nights'],
      totalPrice: double.parse(json['total_price']),
      status: json['status'],
      notes: json['notes'],
      createdAt: json['creatrd_at'],
      updatedAt: json['update_at'],
      user: json['user'] != null ? User.fromJson(json['user']) : null,
      room: json['room'] != null ? Room.fromJson(json['room']) : null,
      payment: json['payment'] != null
          ? Payment.fromJson(json['payment'])
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'user_id': userId,
      'room_id': roomId,
      'check_in_date': checkInDate,
      'check_out_date': checkOutDate,
      'actual_check_in': actualCheckIn,
      'actual_check_out': checkOutDate,
      'total_nights': totalNights,
      'total_price': totalPrice,
      'status': status,
      'notes': notes,
      'created_at': createdAt,
      'updated_at': updatedAt,
    };
  }
}
