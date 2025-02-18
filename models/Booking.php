<?php

class Booking
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function createBooking($userId, $roomId, $subject, $department, $phone, $attendees, $startTime, $endTime, $equipmentIds = [], $note = null, $roomLayoutImage = null, $status = 'pending')
    {
        try {
            // Begin transaction to ensure atomicity
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("INSERT INTO bookings (user_id, room_id, subject, department, phone, attendees, start_time, end_time, note, room_layout_image, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$userId, $roomId, $subject, $department, $phone, $attendees, $startTime, $endTime, $note, $roomLayoutImage, $status]);

            $bookingId = $this->pdo->lastInsertId();

            // Insert selected equipments
            foreach ($equipmentIds as $equipmentId) {
                $stmt = $this->pdo->prepare("INSERT INTO booking_equipments (booking_id, equipment_id) VALUES (?, ?)");
                $stmt->execute([$bookingId, $equipmentId]);
            }

            // Commit transaction
            $this->pdo->commit();

            return $bookingId;
        } catch (PDOException $e) {
            // Rollback transaction if any error occurs
            $this->pdo->rollBack();
            throw new Exception("Error creating booking: " . $e->getMessage());
            return false;
        }
    }

    public function getBookingById($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT
                b.*,
                u.first_name AS user_first_name,
                u.last_name AS user_last_name,
                r.name AS room_name,
                GROUP_CONCAT(e.name) AS equipment_names
            FROM bookings b
            INNER JOIN users u ON b.user_id = u.id
            INNER JOIN rooms r ON b.room_id = r.id
            LEFT JOIN booking_equipments be ON b.id = be.booking_id
            LEFT JOIN equipments e ON be.equipment_id = e.id
           WHERE b.id = ?
           GROUP BY b.id
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateBooking($id, $userId, $roomId, $subject, $department, $phone, $attendees, $startTime, $endTime, $equipmentIds = [], $note = null, $roomLayoutImage = null)
    {
        try {
            // Begin transaction to ensure atomicity
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare("UPDATE bookings SET user_id = ?, room_id = ?, subject = ?, department = ?, phone = ?, attendees = ?, start_time = ?, end_time = ?, note = ?, room_layout_image = ? WHERE id = ?");
            $stmt->execute([$userId, $roomId, $subject, $department, $phone, $attendees, $startTime, $endTime, $note, $roomLayoutImage, $id]);

            // Delete existing booking equipments
            $stmt = $this->pdo->prepare("DELETE FROM booking_equipments WHERE booking_id = ?");
            $stmt->execute([$id]);

            // Insert selected equipments
            foreach ($equipmentIds as $equipmentId) {
                $stmt = $this->pdo->prepare("INSERT INTO booking_equipments (booking_id, equipment_id) VALUES (?, ?)");
                $stmt->execute([$id, $equipmentId]);
            }

            // Commit transaction
            $this->pdo->commit();

            return true;

        } catch (PDOException $e) {
            // Rollback transaction if any error occurs
            $this->pdo->rollBack();
            throw new Exception("Error updating booking: " . $e->getMessage());
            return false;
        }
    }

    public function deleteBooking($id)
    {
        try {
            // Begin transaction to ensure atomicity
            $this->pdo->beginTransaction();
            // Delete booking equipments
            $stmt = $this->pdo->prepare("DELETE FROM booking_equipments WHERE booking_id = ?");
            $stmt->execute([$id]);

            // Delete booking
            $stmt = $this->pdo->prepare("DELETE FROM bookings WHERE id = ?");
            $stmt->execute([$id]);
            // Commit transaction
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            // Rollback transaction if any error occurs
            $this->pdo->rollBack();
            throw new Exception("Error deleting booking: " . $e->getMessage());
            return false;
        }
    }

    public function getAllBookings($where = [])
    {
        $sql = "
            SELECT
                b.*,
                u.first_name AS user_first_name,
                u.last_name AS user_last_name,
                r.name AS room_name,
                GROUP_CONCAT(e.name) AS equipment_names,
                 a.first_name AS admin_first_name,
                a.last_name AS admin_last_name
            FROM bookings b
            INNER JOIN users u ON b.user_id = u.id
            INNER JOIN rooms r ON b.room_id = r.id
           LEFT JOIN users a ON b.admin_id = a.id
            LEFT JOIN booking_equipments be ON b.id = be.booking_id
            LEFT JOIN equipments e ON be.equipment_id = e.id ";
        $conditions = [];
        $params     = [];
        foreach ($where as $key => $value) {
            if (strpos($key, '>=') !== false) {
                $conditions[] = str_replace('>=', '', $key) . " >= ?";
                $params[]     = $value;
            } else if (strpos($key, '<=') !== false) {
                $conditions[] = str_replace('<=', '', $key) . " <= ?";
                $params[]     = $value;
            } else {
                $conditions[] = "$key = ?";
                $params[]     = $value;
            }
        }

        if (! empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        $sql .= " GROUP BY b.id ORDER BY b.id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBookingSummaryByRoom($where = [])
    {
        $sql = "
                SELECT
                    r.name AS room_name,
                    COUNT(b.room_id) AS booking_count,
                    r.color AS room_color
               FROM bookings b
                 INNER JOIN rooms r ON b.room_id = r.id
                 WHERE b.status = 'approved'
           ";
        $conditions = [];
        $params     = [];
        foreach ($where as $key => $value) {
            if (strpos($key, '>=') !== false) {
                $conditions[] = str_replace('>=', '', $key) . " >= ?";
                $params[]     = $value;
            } else if (strpos($key, '<=') !== false) {
                $conditions[] = str_replace('<=', '', $key) . " <= ?";
                $params[]     = $value;
            } else {
                $conditions[] = "$key = ?";
                $params[]     = $value;
            }
        }
        if (! empty($conditions)) {
            $sql .= " AND " . implode(" AND ", $conditions);
        }
        $sql .= " GROUP BY r.id ORDER BY booking_count DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    public function getBookingSummaryByUser($where = [])
    {
        $sql = "
            SELECT
                u.first_name AS user_first_name,
                u.last_name AS user_last_name,
                COUNT(b.user_id) AS booking_count
             FROM bookings b
                INNER JOIN users u ON b.user_id = u.id
                WHERE b.status = 'approved'
        ";

        $conditions = [];
        $params     = [];
        foreach ($where as $key => $value) {
            if (strpos($key, '>=') !== false) {
                $conditions[] = str_replace('>=', '', $key) . " >= ?";
                $params[]     = $value;
            } else if (strpos($key, '<=') !== false) {
                $conditions[] = str_replace('<=', '', $key) . " <= ?";
                $params[]     = $value;
            } else {
                $conditions[] = "$key = ?";
                $params[]     = $value;
            }
        }
        if (! empty($conditions)) {
            $sql .= " AND " . implode(" AND ", $conditions);
        }
        $sql .= " GROUP BY u.id ORDER BY booking_count DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    public function approveBooking($id, $adminId)
    {
        try {
            $booking = $this->getBookingById($id);
            if (! $booking) {
                throw new Exception("Booking not found");
            }
            if ($this->checkBookingAvailability($booking['room_id'], $booking['start_time'], $booking['end_time'], $id)) {
                throw new Exception("ไม่สามารถอนุมัติได้ เนื่องจากช่วงเวลาดังกล่าวไม่ว่าง กรุณาตรวจสอบ");
            }
            $stmt = $this->pdo->prepare("UPDATE bookings SET status = 'approved', admin_id = ? WHERE id = ?");
            $stmt->execute([$adminId, $id]);
            return true;

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
            return false;
        }
    }

    public function rejectBooking($id, $adminId)
    {
        $stmt = $this->pdo->prepare("UPDATE bookings SET status = 'rejected', admin_id = ? WHERE id = ?");
        return $stmt->execute([$adminId, $id]);
    }
    public function getAvailableRooms($startTime, $endTime)
    {
        $sql = "
            SELECT id, name
            FROM rooms
              WHERE id NOT IN (
                SELECT room_id
                   FROM bookings
                  WHERE (start_time < :endTime AND end_time > :startTime) AND status = 'approved'
              )
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':startTime' => $startTime, ':endTime' => $endTime]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function checkBookingAvailability($roomId, $startTime, $endTime, $excludeBookingId = null)
    {

        $sql = "
             SELECT COUNT(*) FROM bookings
               WHERE room_id = :room_id
               AND status = 'approved'
               AND id != :exclude_booking_id
             AND ((start_time < :end_time AND end_time > :start_time))
         ";

        if ($excludeBookingId === null) {
            $sql = str_replace("AND id != :exclude_booking_id", "", $sql);
        }

        $stmt   = $this->pdo->prepare($sql);
        $params = [
            ':room_id'    => $roomId,
            ':start_time' => $startTime,
            ':end_time'   => $endTime,
        ];

        if ($excludeBookingId !== null) {
            $params[':exclude_booking_id'] = $excludeBookingId;
        }

        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
}
