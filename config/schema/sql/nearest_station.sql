create table `nearest_stations`(
	`id` int unsigned not null auto_increment primary key,
	`station_id` int unsigned not null,
	`reference` int unsigned not null,
	`distance` varchar(10) not null
);
