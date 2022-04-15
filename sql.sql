CREATE TABLE Articles (
    article_id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    article_title VARCHAR(255) NOT NULL,
    article_author VARCHAR(255),
    article_tags VARCHAR(255),
    article_preview VARCHAR(255),
    article_content TEXT,
    article_timestamp INT(11)
);

CREATE TABLE Images (
    image_id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    filename VARCHAR(255)
);

CREATE TABLE Tags (
    tag_id INT(10) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    tag_name VARCHAR(255) NOT NULL
);

CREATE TABLE Users (
    user_id INT(10) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    user_name VARCHAR(255),
    user_password VARCHAR(255),
    edit_users_list INT(3)
);

CREATE TABLE Articles_Tags (
    article INT(10),
    tag INT(10),
    FOREIGN KEY (article) REFERENCES Articles(article_id),
    FOREIGN KEY (tag) REFERENCES Tags(tag_id)
);

INSERT INTO Tags (tag_name) VALUES ('kancelaria');
INSERT INTO Tags (tag_name) VALUES ('novaciky');
INSERT INTO Tags (tag_name) VALUES ('baby');
INSERT INTO Tags (tag_name) VALUES ('vzacne');

INSERT INTO Users (user_name, user_password, edit_users_list) VALUES ('admin', '80a19f669b02edfbc208a5386ab5036b', 0);




