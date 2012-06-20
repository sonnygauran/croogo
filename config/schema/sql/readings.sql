create table readings(
    id int unsigned not null auto_increment primary key,
    datum date default null,
    utc varchar(255)  default null,
    min varchar(255) default null,
    ort1 varchar(255) default null,
    dir varchar(255) default null,
    ff varchar(255)  default null,
    g3h varchar(255) default null,
    tl varchar(255) default null,
    rr varchar(255) default null,
    sy varchar(255) default  null,
    rain6 varchar(255) default null,
    rh varchar(255) default null,
    sy2 varchar(255) default null,
    rain3 varchar(255) default null,
    g6h varchar(255) default null
 );