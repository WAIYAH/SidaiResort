<?php declare(strict_types=1);

namespace App\Core;

use TCPDF;

final class Receipt
{
    private Database $database;
    private Logger $logger;

    public function __construct(?Database $database = null, ?Logger $logger = null)
    {
        $this->database = $database ?? Database::getInstance();
        $this->logger = $logger ?? new Logger();
    }

    public function generateBookingReceipt(int $bookingId): ?string
    {
        try {
            $booking = $this->database->queryOne(
                'SELECT b.*, g.full_name, g.email, g.phone, r.name as room_name
                 FROM bookings b
                 JOIN guests g ON b.guest_id = g.id
                 LEFT JOIN rooms r ON b.room_id = r.id
                 WHERE b.id = :id',
                [':id' => $bookingId]
            );

            if ($booking === null) {
                return null;
            }

            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_PAGE_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->SetCreator('Sidai Resort');
            $pdf->SetAuthor('Sidai Resort');
            $pdf->SetTitle('Booking Receipt - ' . $booking['booking_ref']);
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetMargins(15, 15, 15);
            $pdf->AddPage();

            // Header
            $pdf->SetFont('helvetica', 'B', 20);
            $pdf->Cell(0, 10, 'Sidai Resort', 0, 1, 'C');
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(0, 5, 'Booking Receipt', 0, 1, 'C');
            $pdf->Ln(10);

            // Guest Information
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 5, 'Guest Information', 0, 1, 'L');
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(50, 5, 'Name:', 0, 0);
            $pdf->Cell(0, 5, $booking['full_name'], 0, 1);
            $pdf->Cell(50, 5, 'Email:', 0, 0);
            $pdf->Cell(0, 5, $booking['email'], 0, 1);
            $pdf->Cell(50, 5, 'Phone:', 0, 0);
            $pdf->Cell(0, 5, $booking['phone'], 0, 1);
            $pdf->Cell(50, 5, 'Booking Ref:', 0, 0);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 5, $booking['booking_ref'], 0, 1);
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Ln(5);

            // Booking Details
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 5, 'Booking Details', 0, 1, 'L');
            $pdf->SetFont('helvetica', '', 10);

            if ($booking['room_id']) {
                $pdf->Cell(50, 5, 'Room:', 0, 0);
                $pdf->Cell(0, 5, $booking['room_name'], 0, 1);
                $pdf->Cell(50, 5, 'Check-in:', 0, 0);
                $pdf->Cell(0, 5, format_eat_date($booking['check_in']), 0, 1);
                $pdf->Cell(50, 5, 'Check-out:', 0, 0);
                $pdf->Cell(0, 5, $booking['check_out'] ? format_eat_date($booking['check_out']) : 'N/A', 0, 1);
                $pdf->Cell(50, 5, 'Nights:', 0, 0);
                $pdf->Cell(0, 5, (string)$booking['num_nights'], 0, 1);
            }

            $pdf->Cell(50, 5, 'Guests:', 0, 0);
            $pdf->Cell(0, 5, (string)$booking['num_guests'], 0, 1);
            $pdf->Cell(50, 5, 'Type:', 0, 0);
            $pdf->Cell(0, 5, ucfirst(str_replace('_', ' ', $booking['booking_type'])), 0, 1);
            $pdf->Ln(5);

            // Amount Details
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 5, 'Amount Details', 0, 1, 'L');
            $pdf->SetFont('helvetica', '', 10);

            $pdf->Cell(50, 5, 'Subtotal:', 0, 0);
            $pdf->Cell(0, 5, 'KES ' . format_kes($booking['subtotal']), 0, 1, 'R');

            if ($booking['discount_amount'] > 0) {
                $pdf->Cell(50, 5, 'Discount:', 0, 0);
                $pdf->Cell(0, 5, 'KES -' . format_kes($booking['discount_amount']), 0, 1, 'R');
            }

            if ($booking['tax_amount'] > 0) {
                $pdf->Cell(50, 5, 'Tax:', 0, 0);
                $pdf->Cell(0, 5, 'KES ' . format_kes($booking['tax_amount']), 0, 1, 'R');
            }

            $pdf->SetFont('helvetica', 'B', 11);
            $pdf->Cell(50, 5, 'Total Amount:', 0, 0);
            $pdf->Cell(0, 5, 'KES ' . format_kes($booking['total_amount']), 0, 1, 'R');

            if ($booking['deposit_amount'] > 0) {
                $pdf->SetFont('helvetica', '', 10);
                $pdf->Cell(50, 5, 'Deposit Paid:', 0, 0);
                $pdf->Cell(0, 5, 'KES ' . format_kes($booking['deposit_amount']), 0, 1, 'R');
            }

            if ($booking['balance_due'] > 0) {
                $pdf->SetFont('helvetica', 'B', 10);
                $pdf->Cell(50, 5, 'Balance Due:', 0, 0);
                $pdf->Cell(0, 5, 'KES ' . format_kes($booking['balance_due']), 0, 1, 'R');
            }

            $pdf->Ln(10);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Cell(0, 5, 'Generated: ' . format_eat_date(date('Y-m-d H:i:s')), 0, 1, 'R');

            $filename = "receipt-{$booking['booking_ref']}-" . time() . '.pdf';
            $filepath = STORAGE_PATH . "/uploads/receipts/{$filename}";

            $pdf->Output($filepath, 'F');

            return $filepath;
        } catch (\Throwable $exception) {
            $this->logger->logError('Failed to generate receipt', $exception);
            return null;
        }
    }

    public function downloadReceipt(int $bookingId): void
    {
        $filepath = $this->generateBookingReceipt($bookingId);

        if ($filepath === null || !file_exists($filepath)) {
            http_response_code(404);
            return;
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename=' . basename($filepath));
        header('Content-Length: ' . filesize($filepath));

        readfile($filepath);
    }
}

