表结构

```
CREATE TABLE `u_user_cid_0` (
  `user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `cid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

方法

```
CREATE DEFINER=`root`@`localhost` FUNCTION `get_cid_0`(user int) RETURNS int(11)
begin
    declare result int unsigned;
    update u_user_cid_0 set cid=last_insert_id(cid+1) where user_id=user;
    set result = last_insert_id();
    return result;
end
```

使用

```
$cid = select get_cid_0($user_id) AS cid
```
