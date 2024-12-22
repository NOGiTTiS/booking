<?php
session_start();
require_once '../config/database.php';
require_once '../models/Equipment.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

$equipmentModel = new Equipment($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        try {
            if ($equipmentModel->createEquipment($name, $description)) {
                $_SESSION['success_message'] = "สร้างอุปกรณ์สำเร็จ";
                header('Location: ../views/equipments/list.php');
                exit();
            } else {
                $_SESSION['error_message'] = "ไม่สามารถสร้างอุปกรณ์ได้ กรุณาลองใหม่อีกครั้ง";
                header('Location: ../views/equipments/create.php');
                exit();
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: ../views/equipments/create.php');
            exit();
        }

    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        try {
            if ($equipmentModel->updateEquipment($id, $name, $description)) {
                $_SESSION['success_message'] = "แก้ไขอุปกรณ์สำเร็จ";
                header('Location: ../views/equipments/list.php');
                exit();
            } else {
                $_SESSION['error_message'] = "ไม่สามารถแก้ไขอุปกรณ์ได้ กรุณาลองใหม่อีกครั้ง";
                header("Location: ../views/equipments/edit.php?id=$id");
                exit();
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header("Location: ../views/equipments/edit.php?id=$id");
            exit();
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        try {
            if ($equipmentModel->deleteEquipment($id)) {
                $_SESSION['success_message'] = "ลบอุปกรณ์สำเร็จ";
                header('Location: ../views/equipments/list.php');
                exit();
            } else {
                $_SESSION['error_message'] = "ไม่สามารถลบอุปกรณ์ได้ กรุณาลองใหม่อีกครั้ง";
                header('Location: ../views/equipments/list.php');
                exit();
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: ../views/equipments/list.php');
            exit();
        }
    }
}
