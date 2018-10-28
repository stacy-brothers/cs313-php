create table script_topics (
	id serial primary key,
	name varchar(254) not null
);

insert into script_topics ( name ) 
values ('Faith'), ('Sacrifice'), ('Charity');

create table s_t_xref (
	topics_id int not null references script_topics(id),
	scripture_id int not null references scripture(id), 
	primary key ( topics_id, scripture_id)
);