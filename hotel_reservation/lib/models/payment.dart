class Payment {
  final int id;
  final int reservationId;
  final double amount;
  final double lateFee;
  final String? paymentMethod;
  final String paymentStatus;
  final String? paymentDate;
  final String? notes;
  final String? createdAt;
  final String? updatedAt;

  Payment({
    required this.id,
    required this.reservationId,
    required this.amount,
    required this.lateFee,
    this.paymentMethod,
    required this.paymentStatus,
    this.paymentDate,
    this.notes,
    this.createdAt,
    this.updatedAt,
  });

  double getTotalAmount() => amount + lateFee;

  bool isPending() => paymentStatus == 'pending';
  bool isPaid() => paymentStatus == 'paid';
  bool isRefunded() => paymentStatus == 'refunded';

  String getStatusText() {
    switch(paymentStatus) {
      case 'pending':
        return 'Belum Dibayar';
      case 'paid':
        return 'Sudah Dibayar';
      case 'refunded':
        return 'Refund';
      default:
        return 'Unknown';
    }
  }

  String getPaymentMethodText() {
    switch (paymentMethod) {
      case 'cash':
        return 'Tunai';
      case 'transfer':
        return 'Tranfer';
      case 'card':
        return 'Kartu';
      default:
        return '-';
    }
  }

  factory Payment.fromJson(Map<String, dynamic> json) {
    return Payment(
      id: json['id'],
      reservationId: json['reservation_id'],
      amount: double.parse(json['amount'].toString()),
      lateFee: double.parse(json['late_fee'].toString()),
      paymentMethod: json['payment_method'],
      paymentStatus: json['payment_status'],
      paymentDate: json['payment_date'],
      notes: json['notes'],
      createdAt: json['created_at'],
      updatedAt: json['updated_at'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'reservation_id': reservationId,
      'amount': amount,
      'late_fee': lateFee,
      'payment_method': paymentMethod,
      'payment_status': paymentStatus,
      'payment_date': paymentDate,
      'notes': notes,
      'created_at': createdAt,
      'updated_aat': updatedAt,
    };
  }
}