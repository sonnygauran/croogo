drop table if exists `warnings`;
create table `warnings`(
    `id` int unsigned not null auto_increment primary key,
    `color` varchar(255) not null,
    `created` datetime not null,
    `modified` datetime not null,
    `reading_id` int unsigned not null
);