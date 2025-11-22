<?php
header('Content-Type: application/json');
require_once '/../api/config.php';

// This endpoint is public and does not require login.

try {
    $technicianId = filter_input(INPUT_GET, 'technician_id', FILTER_VALIDATE_INT);
    $month = filter_input(INPUT_GET, 'month', FILTER_VALIDATE_INT);
    $year = filter_input(INPUT_GET, 'year', FILTER_VALIDATE_INT);

    if (!$technicianId || !$month || !$year) {
        throw new Exception("Missing required parameters: technician, month, or year.");
    }

    // Define all possible time slots (8 AM to 7 PM)
    $allTimeSlots = [];
    for ($hour = 8; $hour <= 19; $hour++) {
        $allTimeSlots[] = sprintf('%02d:00:00', $hour);
    }

    // Fetch all appointments for the technician in the given month/year
    $sql = "SELECT appointment_date, appointment_time FROM appointments 
            WHERE technician_id = :technician_id 
            AND YEAR(appointment_date) = :year 
            AND MONTH(appointment_date) = :month
            AND status IN ('confirmed', 'pending')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['technician_id' => $technicianId, 'year' => $year, 'month' => $month]);
    $appointments = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_COLUMN);

    $availability = [];
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    for ($day = 1; $day <= $daysInMonth; $day++) {
        $dateStr = sprintf('%d-%02d-%02d', $year, $month, $day);
        $bookedSlots = $appointments[$dateStr] ?? [];
        $availableSlots = array_diff($allTimeSlots, $bookedSlots);
        
        $availability[$dateStr] = [
            'is_fully_booked' => empty($availableSlots),
            'available_slots' => array_values($availableSlots) // re-index array
        ];
    }

    echo json_encode($availability);

} catch (PDOException $e) {
    http_response_code(500);
    error_log("Availability Fetch Error: " . $e->getMessage());
    echo json_encode(['error' => 'A database error occurred.']);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
?>