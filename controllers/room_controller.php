<?php
session_start();
require_once '../config/database.php';
require_once '../models/Room.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit();
}

$roomModel = new Room($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $name = $_POST['name'];
        $capacity = $_POST['capacity'];
        $description = $_POST['description'];
        $color = $_POST['color'];
        try {
            if ($roomModel->createRoom($name, $capacity, $description, $color)) {
                $_SESSION['success_message'] = "สร้างห้องประชุมสำเร็จ";
                header('Location: ../views/rooms/list.php');
                exit();
            } else {
                $_SESSION['error_message'] = "ไม่สามารถสร้างห้องประชุมได้ กรุณาลองใหม่อีกครั้ง";
                header('Location: ../views/rooms/create.php');
                exit();
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: ../views/rooms/create.php');
            exit();
        }

    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $capacity = $_POST['capacity'];
        $description = $_POST['description'];
        $color = $_POST['color'];
        try {
            if ($roomModel->updateRoom($id, $name, $capacity, $description, $color)) {
                $_SESSION['success_message'] = "แก้ไขห้องประชุมสำเร็จ";
                header('Location: ../views/rooms/list.php');
                exit();
            } else {
                $_SESSION['error_message'] = "ไม่สามารถแก้ไขห้องประชุมได้ กรุณาลองใหม่อีกครั้ง";
                header("Location: ../views/rooms/edit.php?id=$id");
                exit();
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header("Location: ../views/rooms/edit.php?id=$id");
            exit();
        }

    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        try {
            if ($roomModel->deleteRoom($id)) {
                $_SESSION['success_message'] = "ลบห้องประชุมสำเร็จ";
                header('Location: ../views/rooms/list.php');
                exit();
            } else {
                $_SESSION['error_message'] = "ไม่สามารถลบห้องประชุมได้ กรุณาลองใหม่อีกครั้ง";
                header('Location: ../views/rooms/list.php');
                exit();
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: ../views/rooms/list.php');
            exit();
        }
    }
}
