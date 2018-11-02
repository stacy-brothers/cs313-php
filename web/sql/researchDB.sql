
-- create database research;
-- no permissions to create table in heroku postgres.... :(

-- this is the main table 
create table topic (
    id serial primary key,
    topic varchar(254) not null,
    notes text
);

-- this is a lookup table that contains keywords that can be assigned to topics
create table keyword ( 
    id serial primary key,
    keyword varchar(30) not null unique
);

-- this is the xref table for the many-to-many keyword to topic relationship
create table topic_keyword (
    topic_id integer references topic(id),
    keyword_id integer references keyword(id)
);




