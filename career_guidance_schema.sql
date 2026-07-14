-- ============================================================
-- CareerGuide Schema — XAMPP Compatible (No information_schema)
-- HOW TO RUN:
--   1. Open phpMyAdmin → http://localhost/phpmyadmin
--   2. Click "career_guidance" in the left sidebar
--   3. Click the SQL tab
--   4. Paste THIS ENTIRE FILE → click Go
--   5. Ignore any "Duplicate key" warnings (orange) — only red = problem
-- ============================================================

-- Make sure we are in the right database
USE career_guidance;

-- ── USERS ────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    email      VARCHAR(150) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    phone      VARCHAR(20)  DEFAULT NULL,
    location   VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- ── TEST RESULTS ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS test_results (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT          NOT NULL,
    category    VARCHAR(100) NOT NULL,
    subcategory VARCHAR(100) NOT NULL,
    suitability ENUM('highly','moderately','not') NOT NULL DEFAULT 'not',
    score       INT          NOT NULL DEFAULT 0,
    top_career  VARCHAR(150) DEFAULT NULL,
    result_json LONGTEXT     DEFAULT NULL,
    taken_at    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ── SAVED EXAMS ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS saved_exams (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT          NOT NULL,
    exam_name   VARCHAR(150) NOT NULL,
    career_name VARCHAR(150) NOT NULL,
    exam_level  VARCHAR(50)  DEFAULT NULL,
    eligibility VARCHAR(200) DEFAULT NULL,
    saved_at    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ── CAREER INTERESTS ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS career_interests (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT          NOT NULL,
    career_name VARCHAR(150) NOT NULL,
    saved_at    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_interest (user_id, career_name),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ── ADD phone & location to existing users table ─────────────
-- Safe: ALTER IGNORE silently skips if column already exists
ALTER IGNORE TABLE users ADD COLUMN phone     VARCHAR(20)  DEFAULT NULL;
ALTER IGNORE TABLE users ADD COLUMN location  VARCHAR(100) DEFAULT NULL;

-- ── INDEXES (ALTER IGNORE skips if index already exists) ─────
ALTER IGNORE TABLE test_results    ADD INDEX idx_results_user   (user_id);
ALTER IGNORE TABLE saved_exams     ADD INDEX idx_exams_user     (user_id);
ALTER IGNORE TABLE career_interests ADD INDEX idx_interests_user (user_id);

-- ── DONE — this query shows all your tables ──────────────────
SHOW TABLES;