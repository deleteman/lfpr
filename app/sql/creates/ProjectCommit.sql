CREATE TABLE `project_commit` (`id` int  auto_increment,
`created_at` datetime ,
`updated_at` datetime ,
`project_id` int ,
`committer` varchar(255) ,
`commit_message` text ,
`sha` varchar(255) ,
 PRIMARY KEY (id) );