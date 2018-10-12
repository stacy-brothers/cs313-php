
-- create database research;
-- no permissions to create table in heroku postgres.... :(

-- users of the database
create table researcher (
    id serial primary key,
    username varchar(30),
    fullname varchar(50),
    email varchar(50),
    pass_hash varchar(254)
)

-- this is the main table 
create table topic (
    id serial primary key,
    topic varchar(254) not null,
    researcher_id references researcher(id),
    private boolean default false,
    notes text
)

-- this is a reference url for a particular topic.  
-- one topic may have many reference urls
create table ref_url (
    id serial primary key,
    url varchar(254) not null, 
    descr varchar(254),
    topic_id integer references topic(id)
)

-- this is a lookup table that contains keywords that can be assigned to topics
create table keyword ( 
    id serial primary key,
    keyword varchar(30) not null unique
)

-- this is the xref table for the many-to-many keyword to topic relationship
create table topic_keyword (
    topic_id integer references topic(id),
    keyword_id integer references keyword(id)
)




