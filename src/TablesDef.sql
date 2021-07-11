create database links;
use links;

drop table Users;
create table Users (
    id int unsigned not null primary key auto_increment,
    name varchar(20) not null,
    pword varchar(20) not null,
    isadmin tinyint(1) default 0,
    usersha1 char(40) not null
);

drop table Tags;
create table Tags (
    id int unsigned not null primary key auto_increment,
    tag varchar(255) not null
);

drop table Links;
create table Links (
    id int unsigned not null primary key auto_increment,
    link varchar(255) not null,
    added timestamp not null,
    addedby int unsigned not null
);

drop table LinkTag;
create table LinkTag (
    linkid int unsigned not null,
    tagid int unsigned not null
);

drop table LinkUser;
create table LinkUser (
    linkid int unsigned not null,
    userid int unsigned not null,
    indexval int unsigned not null,
    connected datetime not null,
    flags int unsigned default 0
);

update linked_list set position = position + 1 where position >= 3 and list_id = 1;


drop table UserFollows;
create table UserFollows (
    userid int unsigned not null,
    followid int unsigned not null,
    followedon timestamp not null
);

grant select,insert,update,delete on links.* to 'opus'@'localhost';

