DROP DATABASE IF EXISTS prog6;
CREATE DATABASE IF NOT EXISTS prog6;
USE prog6;


INSERT INTO users (username, fullname, password, role)
VALUES ('vcth4nh', 'Vũ Chí Thành', '$2y$10$W1mChCZbUriGSSIoGURZS.buYOOkERyuNyUmwLxYzch2b2Z20iHRi', 'teacher'),
       ('student1', 'Học sinh 1', '$2y$10$W1mChCZbUriGSSIoGURZS.buYOOkERyuNyUmwLxYzch2b2Z20iHRi', 'student'),
       ('student2', 'Học sinh 2', '$2y$10$W1mChCZbUriGSSIoGURZS.buYOOkERyuNyUmwLxYzch2b2Z20iHRi', 'student'),
       ('teacher1', 'Giáo viên 1', '$2y$10$W1mChCZbUriGSSIoGURZS.buYOOkERyuNyUmwLxYzch2b2Z20iHRi', 'teacher'),
       ('teacher2', 'Giáo viên 2', '$2y$10$W1mChCZbUriGSSIoGURZS.buYOOkERyuNyUmwLxYzch2b2Z20iHRi', 'teacher');

INSERT INTO messages (send_uid, recv_uid, text)
VALUES (1, 2, 'msg1'),
       (1, 2, 'msg2'),
       (1, 2, 'msg3');

DROP TABLE IF EXISTS exercises;
CREATE TABLE IF NOT EXISTS exercises
(
    exer_id       INT AUTO_INCREMENT PRIMARY KEY,
    post_time     TIMESTAMP DEFAULT CURRENT_TIMESTAMP()                           NOT NULL,
    location      VARCHAR(256) UNIQUE                                             NOT NULL,
    original_name VARCHAR(256) CHARACTER SET utf8mb4 COLLATE 'utf8mb4_general_ci' NOT NULL
);

DROP TABLE IF EXISTS submitted;
CREATE TABLE IF NOT EXISTS submitted
(
    sub_id        INT AUTO_INCREMENT PRIMARY KEY,
    exer_id       INT                                                             NOT NULL,
    uid           INT                                                             NOT NULL,
    post_time     TIMESTAMP DEFAULT CURRENT_TIMESTAMP()                           NOT NULL,
    location      VARCHAR(256) UNIQUE                                             NOT NULL,
    original_name VARCHAR(256) CHARACTER SET utf8mb4 COLLATE 'utf8mb4_general_ci' NOT NULL
);

DROP TABLE IF EXISTS challs;
CREATE TABLE IF NOT EXISTS challs
(
    chall_id  INT AUTO_INCREMENT PRIMARY KEY,
    post_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP()                           NOT NULL,
    hint      VARCHAR(256) CHARACTER SET utf8mb4 COLLATE 'utf8mb4_general_ci' NOT NULL
);
