<?php declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Event
{
    private Database $database;

    public function __construct(?Database $database = null)
    {
        $this->database = $database ?? Database::getInstance();
    }

    /**
     * Get upcoming event bookings.
     */
    public function getUpcoming(int $limit = 20): array
    {
        return $this->database->queryAll(
            "SELECT b.*, g.full_name, g.email, g.phone, h.name as hall_name
             FROM bookings b
             JOIN guests g ON b.guest_id = g.id
             LEFT JOIN halls h ON b.hall_id = h.id
             WHERE b.booking_type IN ('hall', 'event', 'conference')
               AND b.event_date >= CURDATE()
               AND b.status NOT IN ('cancelled')
               AND b.deleted_at IS NULL
             ORDER BY b.event_date ASC
             LIMIT :limit",
            [':limit' => $limit]
        );
    }

    /**
     * Get all event/hall bookings for admin.
     */
    public function getAll(int $limit = 100): array
    {
        return $this->database->queryAll(
            "SELECT b.*, g.full_name, g.email, h.name as hall_name
             FROM bookings b
             JOIN guests g ON b.guest_id = g.id
             LEFT JOIN halls h ON b.hall_id = h.id
             WHERE b.booking_type IN ('hall', 'event', 'conference')
               AND b.deleted_at IS NULL
             ORDER BY b.created_at DESC
             LIMIT :limit",
            [':limit' => $limit]
        );
    }

    /**
     * Get event bookings by date range.
     */
    public function getByDateRange(string $from, string $to): array
    {
        return $this->database->queryAll(
            "SELECT b.*, g.full_name, h.name as hall_name
             FROM bookings b
             JOIN guests g ON b.guest_id = g.id
             LEFT JOIN halls h ON b.hall_id = h.id
             WHERE b.booking_type IN ('hall', 'event', 'conference')
               AND b.event_date BETWEEN :from AND :to
               AND b.deleted_at IS NULL
             ORDER BY b.event_date ASC",
            [':from' => $from, ':to' => $to]
        );
    }
}
