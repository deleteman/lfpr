CREATE TABLE `faq` (`id` int  auto_increment,
`created_at` datetime ,
`updated_at` datetime ,
`project_id` int ,
`question` varchar(255) ,
`answer` text ,
`order` int ,
 PRIMARY KEY (id) );