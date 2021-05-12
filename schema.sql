CREATE DATABASE readme
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE readme;

CREATE TABLE types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_name VARCHAR(128) NOT NULL UNIQUE,
    icon_class VARCHAR(128) NOT NULL UNIQUE
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    us_name VARCHAR(128) NOT NULL UNIQUE, 
    us_password VARCHAR(64) NOT NULL UNIQUE,
    email VARCHAR(128) NOT NULL UNIQUE,
    avatar_img VARCHAR(128),
    dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX us_name_index ON users (us_name) USING BTREE;
CREATE INDEX email_index ON users (email) USING BTREE;

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(128),
    content TEXT,
    autor VARCHAR(128),
    img VARCHAR(128),
    video VARCHAR(128),
    link VARCHAR(128),
    show_count INT,
    dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    post_type INT,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (post_type) REFERENCES types (id)
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    comment TEXT,
    dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    post_id INT,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE
);

CREATE TABLE likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    post_id INT,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE
);

CREATE TABLE subscribes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    to_user_id INT,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (to_user_id) REFERENCES users (id) ON DELETE CASCADE
);

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content TEXT,
    from_user_id INT,
    to_user_id INT,
    FOREIGN KEY (from_user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (to_user_id) REFERENCES users (id) ON DELETE CASCADE
);

CREATE TABLE hashtags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hash_name VARCHAR(128) NOT NULL UNIQUE
);

CREATE TABLE link_hashtags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT,
    hash_id INT,
    FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE,
    FOREIGN KEY (hash_id) REFERENCES hashtags (id) ON DELETE CASCADE
);