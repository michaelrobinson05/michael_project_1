create table IF NOT EXISTS product
(
    product_id     INTEGER not null
        primary key autoincrement,
    productName TEXT,
    category    TEXT,
    quantity      INTEGER,
    price  INTEGER,
    image TEXT,
    code Text
);