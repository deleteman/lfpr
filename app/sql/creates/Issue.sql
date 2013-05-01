CREATE TABLE `issue` (`id` int  auto_increment,
`created_at` datetime ,
`updated_at` datetime ,
`title` varchar(255) ,
`body` text ,
`num` int ,
`url` varchar(255) ,
`project_id` int ,
 PRIMARY KEY (id) );