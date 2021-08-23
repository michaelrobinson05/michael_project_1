create table IF NOT EXISTS invoice
(
    invoice_id     INTEGER not null
        primary key autoincrement,
    user_id    TEXT,
    product_id    INTEGER,
    date        INTEGER,
    price  INTEGER,
    payment details INTEGER
);