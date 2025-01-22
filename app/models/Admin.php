<?php
require_once 'User.php';

class Admin extends User {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo ;
    }
    /**
     * get   globals statistics.
     * @return array
     */
    public function getGlobalStatistics(): array {
        try {
            $statistics = [];

            // total users
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM Users");
            $statistics['total_users'] = $stmt->fetchColumn();

            // total courses
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM Courses");
            $statistics['total_courses'] = $stmt->fetchColumn();

            //  actifs Students
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM Users WHERE role = 'Student' AND status = 'Active'");
            $statistics['active_students'] = $stmt->fetchColumn();

            //  total categories
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM Categories");
            $statistics['total_categories'] = $stmt->fetchColumn();

            return $statistics;
        } catch (PDOException $e) {
            error_log("Error fetching global statistics: " . $e->getMessage());
            throw new RuntimeException("Error while fetching global statistics.", 0, $e);
        }
    }

    /**
     * get recent users
     * @param int $limit (nombre d'utilisateurs à récupérer)
     * @return array
     */
    public function getRecentUsers(int $limit = 5): array {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM Users ORDER BY created_at DESC LIMIT :limit");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching recent users: " . $e->getMessage());
            throw new RuntimeException("Error while fetching recent users.", 0, $e);
        }
    }

    /**
     * Get recent courses
     * @param int 
     * @return array
     */
    public function getRecentCourses(int $limit = 5): array {
        try {
            $stmt = $this->pdo->prepare("
                SELECT Courses.title, Categories.name AS category, Users.name AS instructor, COUNT(Enrollments.id) AS students
                FROM Courses
                LEFT JOIN Categories ON Courses.category_id = Categories.id
                LEFT JOIN Users ON Courses.instructor_id = Users.id
                LEFT JOIN Enrollments ON Courses.id = Enrollments.course_id
                GROUP BY Courses.id
                ORDER BY Courses.created_at DESC
                LIMIT :limit
            ");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching recent courses: " . $e->getMessage());
            throw new RuntimeException("Error while fetching recent courses.", 0, $e);
        }
    }

    /**
     * Met à jour le rôle d'un utilisateur.
     *
     * @param int $userId
     * @param string $newRole
     * @return bool
     */
    public function updateUserRole(int $userId, string $newRole): bool {
        try {
            $sql = "UPDATE Users SET role = :role WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['role' => $newRole, 'id' => $userId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error updating user role: " . $e->getMessage());
            throw new RuntimeException("Error while updating user role.", 0, $e);
        }
    }

    /**
     * Met à jour le statut d'un utilisateur.
     *
     * @param int $userId
     * @param string $newStatus
     * @return bool
     */
    public function updateUserStatus(int $userId, string $newStatus): bool {
        try {
            $sql = "UPDATE Users SET status = :status WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['status' => $newStatus, 'id' => $userId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error updating user status: " . $e->getMessage());
            throw new RuntimeException("Error while updating user status.", 0, $e);
        }
    }

    public function getAllUsers(): array {
        try {
            $stmt = $this->pdo->query("SELECT * FROM Users ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching all users: " . $e->getMessage());
            throw new RuntimeException("Error while fetching all users.", 0, $e);
        }
    }

    public function getAllCourses(): array {
        try {
            $stmt = $this->pdo->query("
                SELECT Courses.title, Categories.name AS category, Users.name AS instructor, COUNT(Enrollments.id) AS students
                FROM Courses
                LEFT JOIN Categories ON Courses.category_id = Categories.id
                LEFT JOIN Users ON Courses.instructor_id = Users.id
                LEFT JOIN Enrollments ON Courses.id = Enrollments.course_id
                GROUP BY Courses.id
                ORDER BY Courses.created_at DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching all courses: " . $e->getMessage());
            throw new RuntimeException("Error while fetching all courses.", 0, $e);
        }
    }
}
?>