create table scripture (
    id serial primary key,
    book varchar(50) not null,
    chapter integer not null,
    verse integer not null,
    content text not null
)