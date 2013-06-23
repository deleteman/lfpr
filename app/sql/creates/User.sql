CREATE TABLE `user` (`id` int  auto_increment,
`created_at` datetime ,
`updated_at` datetime ,
`username` varchar(255) ,
`password` varchar(255) ,
`role` varchar(255) ,
 PRIMARY KEY (id) );