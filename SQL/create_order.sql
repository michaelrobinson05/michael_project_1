create table IF NOT EXISTS orderDetails
(
    orderDetails_id INTEGER    not null

    primary key autoincrement,
    user_id INTEGER,
    date TEXT,
    price INTEGER,
    paymentDetails TEXT,
    product_id INTEGER,
    orderCode TEXT,
    quantity INTEGER



);
