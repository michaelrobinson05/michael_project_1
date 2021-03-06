create table IF NOT EXISTS messaging
(
    user_id     INTEGER not null
        primary key autoincrement,
    username   TEXT,
    firstname TEXT,
    email   TEXT,
    sender TEXT,
    recipient     int,
    message       TEXT,
    dateSubmitted DATETIME

);
