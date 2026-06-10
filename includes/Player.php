<?php

class Player {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($userId, $name, $role, $rank) {
        $stmt = $this->conn->prepare("INSERT INTO players (user_id, summoner_name, role, rank_tier) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $userId, $name, $role, $rank);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getAll($userId) {
        $stmt = $this->conn->prepare("SELECT id, summoner_name, role, rank_tier FROM players WHERE user_id = ? ORDER BY id DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function getById($id, $userId) {
        $stmt = $this->conn->prepare("SELECT id, summoner_name, role, rank_tier FROM players WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }

    public function update($id, $userId, $name, $role, $rank) {
        $stmt = $this->conn->prepare("UPDATE players SET summoner_name = ?, role = ?, rank_tier = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("sssii", $name, $role, $rank, $id, $userId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id, $userId) {
        $stmt = $this->conn->prepare("DELETE FROM players WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $userId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
