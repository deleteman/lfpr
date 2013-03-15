CREATE TABLE `project_delta` (`id` int  auto_increment,
`created_at` datetime ,
`updated_at` datetime ,
`sample_date` datetime ,
`stars` int ,
`delta_stars` int ,
`forks` int ,
`delta_forks` int ,
 PRIMARY KEY (id) );