<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // ─────────────────────────────────────────────
    // FIND
    // ─────────────────────────────────────────────

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM users WHERE user_id = :id LIMIT 1'
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM users WHERE email = :email LIMIT 1'
        );
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM users WHERE username = :username LIMIT 1'
        );
        $stmt->execute([':username' => $username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    // ─────────────────────────────────────────────
    // CREATE
    // ─────────────────────────────────────────────

    public function create(array $data): int
    {
        $sql = '
            INSERT INTO users
                (username, full_name, email, phone, password_hash, profile_image)
            VALUES
                (:username, :full_name, :email, :phone, :password_hash, :profile_image)
        ';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':username'      => $data['username'],
            ':full_name'     => $data['full_name'],
            ':email'         => $data['email'],
            ':phone'         => $data['phone']         ?? null,
            ':password_hash' => $data['password_hash'],
            ':profile_image' => $data['profile_image'] ?? null,
        ]);

        return (int) $this->db->lastInsertId();
    }

    // ─────────────────────────────────────────────
    // UPDATE
    // ─────────────────────────────────────────────

    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = [':id' => $id];

        $allowed = ['full_name', 'phone', 'address', 'profile_image', 'language'];
        foreach ($allowed as $col) {
            if (array_key_exists($col, $data)) {
                $fields[]        = "{$col} = :{$col}";
                $params[":{$col}"] = $data[$col];
            }
        }

        if (empty($fields)) return false;

        $sql  = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE user_id = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function updatePassword(int $id, string $hash): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE users SET password_hash = :hash WHERE user_id = :id'
        );
        return $stmt->execute([':hash' => $hash, ':id' => $id]);
    }

    public function updateTrust(int $id, int $trustLevel, string $badgeStatus): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE users SET trust_level = :trust, badge_status = :badge WHERE user_id = :id'
        );
        return $stmt->execute([
            ':trust' => $trustLevel,
            ':badge' => $badgeStatus,
            ':id'    => $id,
        ]);
    }

    // ─────────────────────────────────────────────
    // DELETE
    // ─────────────────────────────────────────────

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM users WHERE user_id = :id');
        return $stmt->execute([':id' => $id]);
    }

    // ─────────────────────────────────────────────
    // PASSWORD RESET TOKENS
    // ─────────────────────────────────────────────

    public function storeResetToken(int $userId, string $token, string $expires): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE users SET reset_token = :token, reset_expires = :expires WHERE user_id = :id'
        );
        return $stmt->execute([
            ':token'   => $token,
            ':expires' => $expires,
            ':id'      => $userId,
        ]);
    }

    public function findByResetToken(string $token): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM users WHERE reset_token = :token LIMIT 1'
        );
        $stmt->execute([':token' => $token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function clearResetToken(int $id): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE users SET reset_token = NULL, reset_expires = NULL WHERE user_id = :id'
        );
        return $stmt->execute([':id' => $id]);
    }

    // ─────────────────────────────────────────────
    // REMEMBER-ME TOKENS
    // ─────────────────────────────────────────────

    public function storeRememberToken(int $userId, string $tokenHash, int $expires): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE users SET remember_token = :token, remember_expires = FROM_UNIXTIME(:expires) WHERE user_id = :id'
        );
        return $stmt->execute([
            ':token'   => $tokenHash,
            ':expires' => $expires,
            ':id'      => $userId,
        ]);
    }

    public function findByRememberToken(string $tokenHash): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM users WHERE remember_token = :token AND remember_expires > NOW() LIMIT 1'
        );
        $stmt->execute([':token' => $tokenHash]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function clearRememberToken(int $id): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE users SET remember_token = NULL, remember_expires = NULL WHERE user_id = :id'
        );
        return $stmt->execute([':id' => $id]);
    }

    // ─────────────────────────────────────────────
    // ADMIN / PAGINATION
    // ─────────────────────────────────────────────

    public function paginate(int $limit, int $offset, string $search = ''): array
    {
        if ($search !== '') {
            $stmt = $this->db->prepare('
                SELECT * FROM users
                WHERE username LIKE :s OR full_name LIKE :s OR email LIKE :s
                ORDER BY date_joined DESC
                LIMIT :limit OFFSET :offset
            ');
            $stmt->bindValue(':s',      '%' . $search . '%', PDO::PARAM_STR);
        } else {
            $stmt = $this->db->prepare('
                SELECT * FROM users
                ORDER BY date_joined DESC
                LIMIT :limit OFFSET :offset
            ');
        }
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count(string $search = ''): int
    {
        if ($search !== '') {
            $stmt = $this->db->prepare('
                SELECT COUNT(*) FROM users
                WHERE username LIKE :s OR full_name LIKE :s OR email LIKE :s
            ');
            $stmt->execute([':s' => '%' . $search . '%']);
        } else {
            $stmt = $this->db->query('SELECT COUNT(*) FROM users');
        }
        return (int) $stmt->fetchColumn();
    }
}