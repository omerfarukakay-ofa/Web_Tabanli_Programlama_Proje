<?php

class GameMatch {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($userId, $playerId, $champion, $result, $kills, $deaths, $assists, $date) {
        $stmt = $this->conn->prepare("INSERT INTO matches (user_id, player_id, champion, result, kills, deaths, assists, match_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissiiis", $userId, $playerId, $champion, $result, $kills, $deaths, $assists, $date);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function getAll($userId) {
        $stmt = $this->conn->prepare("SELECT m.id, m.champion, m.result, m.kills, m.deaths, m.assists, m.match_date, p.summoner_name FROM matches m JOIN players p ON m.player_id = p.id WHERE m.user_id = ? ORDER BY m.match_date DESC, m.id DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function getById($id, $userId) {
        $stmt = $this->conn->prepare("SELECT id, player_id, champion, result, kills, deaths, assists, match_date FROM matches WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }

    public function update($id, $userId, $playerId, $champion, $result, $kills, $deaths, $assists, $date) {
        $stmt = $this->conn->prepare("UPDATE matches SET player_id = ?, champion = ?, result = ?, kills = ?, deaths = ?, assists = ?, match_date = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("issiiisii", $playerId, $champion, $result, $kills, $deaths, $assists, $date, $id, $userId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function delete($id, $userId) {
        $stmt = $this->conn->prepare("DELETE FROM matches WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $userId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
