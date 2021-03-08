CREATE DATABASE IF NOT EXISTS challenge_api;

CREATE USER 'db_challenge_user'@'%' IDENTIFIED BY '5FoPbVK_eX4nL91eG';
GRANT ALL PRIVILEGES ON *.* TO 'db_challenge_user'@'%';
GRANT ALL PRIVILEGES ON challenge_api.* TO 'username'@'%';
FLUSH PRIVILEGES;