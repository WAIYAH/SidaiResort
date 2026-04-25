<?php declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Booking
{
    private Database $database;

    public function __construct(?Database $database = null)
    {
        $this->database = $database ?? Database::getInstance();
    }

    public function create(array $data): ?int
    {
        try {
            $bookingRef = generate_booking_ref();

            $this->database->query(
                'INSERT INTO bookings (booking_ref, guest_id, booking_type, room_id, hall_id, check_in, check_out, 
                 event_date, event_type, num_guests, subtotal, discount_amount, tax_amount, total_amount, 
                 deposit_amount, status, payment_status)
                 VALUES (:ref, :guest_id, :type, :room_id, :hall_id, :check_in, :check_out, :event_date, :event_type, 
                 :num_guests, :subtotal, :discount, :tax, :total, :deposit, :status, :payment_status)',
                [
                    ':ref' => $bookingRef,
                    ':guest_id' => $data['guest_id'],
                    ':type' => $data['booking_type'],
                    ':room_id' => $data['room_id'] ?? null,
                    ':hall_id' => $data['hall_id'] ?? null,
                    ':check_in' => $data['check_in'] ?? null,
                    ':check_out' => $data['check_out'] ?? null,
                    ':event_date' => $data['event_date'] ?? null,
                    ':event_type' => $data['event_type'] ?? null,
                    ':num_guests' => $data['num_guests'] ?? 1,
                    ':subtotal' => $data['subtotal'] ?? 0,
                    ':discount' => $data['discount_amount'] ?? 0,
                    ':tax' => $data['tax_amount'] ?? 0,
                    ':total' => $data['total_amount'] ?? 0,
                    ':deposit' => $data['deposit_amount'] ?? 0,
                    ':status' => $data['status'] ?? 'pending',
                    ':payment_status' => $data['payment_status'] ?? 'unpaid',
                ]
            );

            return (int)$this->database->connection()->lastInsertId();
        } catch (\Throwable $exception) {
            log_error('Failed to create booking', $exception);
            return null;
        }
    }

    public function getById(int $id): ?array
    {
        return $this->database->queryOne(
            'SELECT b.*, g.full_name, g.email, g.phone FROM bookings b
             JOIN guests g ON b.guest_id = g.id
             WHERE b.id = :id',
            [':id' => $id]
        );
    }

    public function getByRef(string $ref): ?array
    {
        return $this->database->queryOne(
            'SELECT b.*, g.full_name, g.email, g.phone FROM bookings b
             JOIN guests g ON b.guest_id = g.id
             WHERE b.booking_ref = :ref',
            [':ref' => $ref]
        );
    }

    public function getAll(int $limit = 100, int $offset = 0): array
    {
        return $this->database->queryAll(
            'SELECT b.*, g.full_name, g.email FROM bookings b
             JOIN guests g ON b.guest_id = g.id
             WHERE b.deleted_at IS NULL
             ORDER BY b.created_at DESC LIMIT :limit OFFSET :offset',
            [':limit' => $limit, ':offset' => $offset]
        );
    }

    public function update(int $id, array $data): bool
    {
        try {
            $this->database->query(
                'UPDATE bookings SET status = :status, payment_status = :payment_status, 
                 notes = :notes, updated_at = NOW()
                 WHERE id = :id',
                [
                    ':id' => $id,
                    ':status' => $data['status'] ?? null,
                    ':payment_status' => $data['payment_status'] ?? null,
                    ':notes' => $data['notes'] ?? null,
                ]
            );

            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to update booking', $exception);
            return false;
        }
    }

    public function cancel(int $id, string $reason): bool
    {
        try {
            $this->database->query(
                'UPDATE bookings SET status = :status, payment_status = :payment_status, 
                 cancellation_reason = :reason, cancelled_at = NOW()
                 WHERE id = :id',
                [
                    ':id' => $id,
                    ':status' => 'cancelled',
                    ':payment_status' => 'refunded',
                    ':reason' => $reason,
                ]
            );

            return true;
        } catch (\Throwable $exception) {
            log_error('Failed to cancel booking', $exception);
            return false;
        }
    }
}
