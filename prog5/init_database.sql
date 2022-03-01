CREATE TABLE IF NOT EXISTS users
(
    id       INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(15) UNIQUE NOT NULL,
    password VARCHAR(60)        NOT NULL,
    fullname VARCHAR(350) NOT NULL ,
    email VARCHAR(3)

    )