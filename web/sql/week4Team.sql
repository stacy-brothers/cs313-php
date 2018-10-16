
CREATE TABLE Speakers(
    Id     serial PRIMARY KEY,
    Name   varchar(30) NOT NULL
);

CREATE TABLE Conferences(
    Id     serial PRIMARY KEY,
    Month   integer NOT NULL,
    Year  integer NOT NULL
);

CREATE TABLE Users(
    Id     serial PRIMARY KEY,
    Name   varchar(30) NOT NULL
);


CREATE TABLE Talks(
    Id     serial PRIMARY KEY,
    Title   varchar(254) NOT NULL,
    Speaker integer REFERENCES Speakers(Id),
    Conference integer REFERENCES Conferences(Id)
);

CREATE TABLE Notes(
    Id     serial PRIMARY KEY,
    Notes  text NOT NULL,
    UserId  integer REFERENCES Users(Id),
    Talk integer REFERENCES Talks(Id)
);

insert into conferences (Month, Year) values (10,2018);
insert into speakers(name) values ('Quentin L. Cook');
insert into users (name) values ('Stacy');
insert into talks (title, speaker, conference) values ('Deep and Lasting Conversion to Heavenly Father and the Lord Jesus Christ',
    (select id from speakers s where s.name = 'Quentin L. Cook'), (select id from conferences c where c.month = 10 and c.year = 2018));
insert into notes ( userid, talk, notes ) values ((select id from users u where u.name = 'Stacy'),
    (select id from talks t where t.title = 'Deep and Lasting Conversion to Heavenly Father and the Lord Jesus Christ'), 
    'This was a really great talk.  Make sure to read through it and take better notes...' ); 
insert into speakers(name) values ('M. Joseph Brough');
insert into talks (title, speaker, conference) values ('Lift Up Your Head and Rejoice',
    (select id from speakers s where s.name = 'M. Joseph Brough'), (select id from conferences c where c.month = 10 and c.year = 2018));
insert into notes ( userid, talk, notes ) values ((select id from users u where u.name = 'Stacy'),
    (select id from talks t where t.title = 'Lift Up Your Head and Rejoice'), 
    'This was a also a really great talk.  Looks like the whole morning is going to be great.  Make sure to read through it and take better notes...' ); 
