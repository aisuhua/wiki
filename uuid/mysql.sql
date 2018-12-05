create table sequence (
  `name` varchar(64) not null,
  `value` bigint unsigned not null,
  primary key (`name`)
) engine=innodb;

delimiter //

create function nextval (sequence_name varchar(64)) returns bigint
begin
    declare result bigint unsigned;
    update sequence set value = last_insert_id(value + 1) where name = sequence_name;
    set result = last_insert_id();
    return result;
end //

create function `next_id`(seq_name varchar(64)) returns bigint
begin
    declare result bigint unsigned;
    declare our_epoch bigint default 1543939200000;
    declare shard_id int default 1;
    declare seq_id bigint unsigned;
    declare cur_time char(12);
    declare cur_millis char(19);

    select nextval(seq_name) into seq_id;

    set cur_time = curtime(3);
    set cur_millis = concat(floor(unix_timestamp(concat(curdate(), ' ', left(cur_time, 8)))), right(cur_time, 3));

    set result = (cur_millis - our_epoch) << 23;
    set result = result | (shard_id % 8192) << 10;
    set result = result | (seq_id % 1024);

    return result;
end //

create procedure `next_ids`(in seq_name varchar(64), in num int)
begin
    declare counter int default 0;
    create temporary table if not exists tb (id bigint unsigned) engine = myisam;

    start transaction;
    while counter < num do
        insert into tb select next_id(seq_name);
        set counter = counter +1;
    end while;
    commit;

    select * from tb;
    drop table tb;
end //

delimiter ;

insert into sequence values ('default', 0);
select next_id('default');
call next_ids('default', 1000);
