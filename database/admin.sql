create table admin(
    email VARCHAR(255) PRIMARY key,
    password VARCHAR(255) NOT NULL
);
alter table admin add column name VARCHAR(255) not null ;