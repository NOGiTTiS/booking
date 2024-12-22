<?php

class Equipment
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    public function createEquipment($name, $description)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO equipments (name, description) VALUES (?, ?)");
            $stmt->execute([$name, $description]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error creating equipment: " . $e->getMessage());
            return false;
        }
    }
    public function getEquipmentById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM equipments WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateEquipment($id, $name, $description)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE equipments SET name = ?, description = ? WHERE id = ?");
            $stmt->execute([$name, $description, $id]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error updating equipment: " . $e->getMessage());
            return false;
        }
    }

    public function deleteEquipment($id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM equipments WHERE id = ?");
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error deleting equipment: " . $e->getMessage());
            return false;
        }
    }

    public function getAllEquipments()
    {
        $stmt = $this->pdo->query("SELECT * FROM equipments");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
