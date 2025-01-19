CREATE DATABASE IF NOT EXISTS Youdemy;
USE Youdemy;

-- Table des Utilisateurs (Users)
CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL COMMENT 'Mot de passe haché avec bcrypt.',
    role ENUM('Student', 'Instructor', 'Admin') NOT NULL,
    status ENUM('Active', 'Pending', 'Suspended') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des Catégories (Categories)
CREATE TABLE Categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des Cours (Courses)
CREATE TABLE Courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    instructor_id INT,
    category_id INT,
    image_uri VARCHAR(255) COMMENT 'Chemin relatif de image du cours',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (instructor_id) REFERENCES Users(id) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES Categories(id) ON DELETE SET NULL
);

-- Table des Tags
CREATE TABLE Tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table de Jointure Cours-Tags (CourseTags)
CREATE TABLE CourseTags (
    course_id INT,
    tag_id INT,
    PRIMARY KEY (course_id, tag_id),
    FOREIGN KEY (course_id) REFERENCES Courses(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES Tags(id) ON DELETE CASCADE
);

-- Table des Inscriptions (Enrollments)
CREATE TABLE Enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES Courses(id) ON DELETE CASCADE
);

-- Table des Vidéos (Videos)
CREATE TABLE Videos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    video_url VARCHAR(255) NOT NULL,
    duration INT COMMENT 'Durée en secondes',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES Courses(id) ON DELETE CASCADE
);

-- Table des Contenus Markdown (MarkdownContents)
CREATE TABLE MarkdownContents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL COMMENT 'Contenu Markdown',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES Courses(id) ON DELETE CASCADE
);

-- Table des Quiz (Quizzes)
CREATE TABLE Quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES Courses(id) ON DELETE CASCADE
);

-- Table des Questions (Questions)
CREATE TABLE Questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT,
    question TEXT NOT NULL,
    option1 VARCHAR(255),
    option2 VARCHAR(255),
    option3 VARCHAR(255),
    option4 VARCHAR(255),
    correct_answer INT COMMENT 'Index de la bonne réponse (1 à 4)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (quiz_id) REFERENCES Quizzes(id) ON DELETE CASCADE
);

-- Table des Statistiques des Cours (CourseStatistics)
CREATE TABLE CourseStatistics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    enrolled_students INT DEFAULT 0,
    engagement_rate FLOAT DEFAULT 0.0,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES Courses(id) ON DELETE CASCADE
);

-- Table des Statistiques des Étudiants (StudentStatistics)
CREATE TABLE StudentStatistics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    progress FLOAT DEFAULT 0.0,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES Courses(id) ON DELETE CASCADE
);

-- Table des Statistiques Globales (GlobalStatistics)
CREATE TABLE GlobalStatistics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    total_courses INT DEFAULT 0,
    category_distribution JSON COMMENT 'Répartition des cours par catégorie',
    top_courses JSON COMMENT 'Cours les plus populaires',
    top_instructors JSON COMMENT 'Enseignants les plus populaires',
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Ajout d'index pour améliorer les performances
CREATE INDEX idx_course_id ON Courses(id);
CREATE INDEX idx_student_id ON Enrollments(student_id);
CREATE INDEX idx_category_id ON Courses(category_id);
CREATE INDEX idx_instructor_id ON Courses(instructor_id);
CREATE INDEX idx_quiz_id ON Questions(quiz_id);
CREATE INDEX idx_course_id_videos ON Videos(course_id);
CREATE INDEX idx_course_id_markdown ON MarkdownContents(course_id);
CREATE INDEX idx_course_id_quizzes ON Quizzes(course_id);
CREATE INDEX idx_course_id_statistics ON CourseStatistics(course_id);
CREATE INDEX idx_student_id_statistics ON StudentStatistics(student_id);