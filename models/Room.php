<?php

class Room
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    public function createRoom($name, $capacity, $description, $color = null)
    {
        try {
            if ($color === null) {
                $color = $this->getRandomColor();
            }
            $stmt = $this->pdo->prepare("INSERT INTO rooms (name, capacity, description, color) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $capacity, $description, $color]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error creating room: " . $e->getMessage());
            return false;
        }
    }
    public function getRoomById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM rooms WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateRoom($id, $name, $capacity, $description, $color = null)
    {
        try {
            if ($color === null) {
                $color = $this->getRandomColor();
            }
            $stmt = $this->pdo->prepare("UPDATE rooms SET name = ?, capacity = ?, description = ?, color = ? WHERE id = ?");
            $stmt->execute([$name, $capacity, $description, $color, $id]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error updating room: " . $e->getMessage());
            return false;
        }
    }

    public function deleteRoom($id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM rooms WHERE id = ?");
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error deleting room: " . $e->getMessage());
            return false;
        }
    }
    public function getAllRooms()
    {
        $stmt = $this->pdo->query("SELECT * FROM rooms");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    private function getRandomColor()
    {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }
}
