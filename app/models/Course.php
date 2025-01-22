<?php
require_once __DIR__ . '/../core/Database.php';

class Course{
    private $pdo;

    public function __construct() {
        $database = Database::getInstance();
        $this->pdo = $database->getPdo();
    }

    /**
     * Get all courses 
     * @return array 
     */
    public function getAllCourses(): array {
        $query = "
            SELECT 
                Courses.id, 
                Courses.title, 
                Courses.description, 
                Courses.image_uri, 
                Categories.name AS category 
            FROM Courses
            LEFT JOIN Categories ON Courses.category_id = Categories.id
        ";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get courses by category
     * @param string $category
     * @return array
     */
    public function getCoursesByCategory(string $category): array {
        $query = "
            SELECT 
                Courses.id, 
                Courses.title, 
                Courses.description, 
                Courses.image_uri, 
                Categories.name AS category 
            FROM Courses
            LEFT JOIN Categories ON Courses.category_id = Categories.id
            WHERE Categories.name = :category
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':category' => $category]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * search courses
     * @param string $searchTerm
     * @return array
     */
    public function searchCourses(string $searchTerm): array {
        $query = "
            SELECT 
                Courses.id, 
                Courses.title, 
                Courses.description, 
                Courses.image_uri, 
                Categories.name AS category 
            FROM Courses
            LEFT JOIN Categories ON Courses.category_id = Categories.id
            WHERE Courses.title LIKE :searchTerm OR Courses.description LIKE :searchTerm
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':searchTerm' => "%$searchTerm%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get Categories
     * @return array
     */
    public function getCategories(): array {
        $query = "SELECT name FROM Categories";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

            /**
         * Enroll a student in a course.
         * @param int $courseId
         * @param int $studentId
         * @return bool
         */
        public function enrollStudent(int $courseId, int $studentId): bool {
            // Vérifier si l'étudiant est déjà inscrit au cours
            $query = "SELECT id FROM Enrollments WHERE student_id = :student_id AND course_id = :course_id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':student_id' => $studentId, ':course_id' => $courseId]);

            if ($stmt->fetch()) {
                throw new Exception("You are already enrolled in this course.");
            }

            // Insérer l'inscription
            $query = "INSERT INTO Enrollments (student_id, course_id, enrollment_date) VALUES (:student_id, :course_id, NOW())";
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute([':student_id' => $studentId, ':course_id' => $courseId]);
        }

                                /**
 * Get course statistics for an instructor.
 * @param int $instructorId
 * @return array
 */
public function getCourseStatistics(int $instructorId): array {
    // Total number of courses
    $query = "SELECT COUNT(*) AS total_courses FROM Courses WHERE instructor_id = :instructor_id";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([':instructor_id' => $instructorId]);
    $totalCourses = $stmt->fetchColumn();

    // Total number of enrolled students
    $query = "
        SELECT COUNT(DISTINCT Enrollments.student_id) AS total_students
        FROM Enrollments
        JOIN Courses ON Enrollments.course_id = Courses.id
        WHERE Courses.instructor_id = :instructor_id
    ";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([':instructor_id' => $instructorId]);
    $totalStudents = $stmt->fetchColumn();

    // Engagement rate (simplified example: average progress of students)
    $query = "
        SELECT AVG(StudentStatistics.progress) AS engagement_rate
        FROM StudentStatistics
        JOIN Courses ON StudentStatistics.course_id = Courses.id
        WHERE Courses.instructor_id = :instructor_id
    ";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([':instructor_id' => $instructorId]);
    $engagementRate = (float) $stmt->fetchColumn();

    return [
        'total_courses' => $totalCourses,
        'total_students' => $totalStudents,
        'engagement_rate' => $engagementRate,
    ];
}


/**
 * Get recent courses created by an instructor.
 * @param int $instructorId
 * @return array
 */
public function getRecentCourses(int $instructorId): array {
    $query = "
        SELECT 
            Courses.id, 
            Courses.title, 
            Categories.name AS category, 
            COUNT(Enrollments.student_id) AS students
        FROM Courses
        LEFT JOIN Categories ON Courses.category_id = Categories.id
        LEFT JOIN Enrollments ON Courses.id = Enrollments.course_id
        WHERE Courses.instructor_id = :instructor_id
        GROUP BY Courses.id
        ORDER BY Courses.created_at DESC
        LIMIT 5
    ";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([':instructor_id' => $instructorId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get recent students enrolled in courses created by an instructor.
 * @param int $instructorId
 * @return array
 */
public function getRecentStudents(int $instructorId): array {
    $query = "
        SELECT 
            Users.id, 
            Users.name, 
            Users.email, 
            AVG(StudentStatistics.progress) AS progress,
            MAX(Enrollments.enrollment_date) AS last_enrollment_date
        FROM Enrollments
        JOIN Users ON Enrollments.student_id = Users.id
        JOIN Courses ON Enrollments.course_id = Courses.id
        LEFT JOIN StudentStatistics ON Enrollments.student_id = StudentStatistics.student_id
        WHERE Courses.instructor_id = :instructor_id
        GROUP BY Users.id
        ORDER BY last_enrollment_date DESC
        LIMIT 5
    ";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([':instructor_id' => $instructorId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
?>