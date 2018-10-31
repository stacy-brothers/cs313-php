
create table users (
    id serial primary key,
    name varchar(50) not null,
    pass varchar(256) not null
)