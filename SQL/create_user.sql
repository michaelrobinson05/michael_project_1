create table IF NOT EXISTS user
(
    user_id     INTEGER not null
    primary key autoincrement,
    username    TEXT,
    password    TEXT,
    name        TEXT,
    profilePic  TEXT,
    accessLevel TEXT
);