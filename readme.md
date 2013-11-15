#Looking For Pull Requests

The site where you can find possible projects to contribute to, or even publish your own projects and let others find it and help you!.

##Using LFPR

Using the site is dead simple, if you're just browsing, click on the "Find projects" button on the home page, and you'll be redirected to the main list of published projects. If you're interested in a particular programming language you can filter by it.

If you're looking to publish your project and let others find it, just click on the "Publish your project" button on the main page. You'll be redirected to a page, where you'll be invited to enter the project's URL (only works for projects hosted on Github) and after clicking on the "Query" button, all relevant information will be pulled using Github's API. Just click on "Send info" after that, and you'll be all set.

##What's inside it?

Apart from listing repos, LFPR is daily pulling statistics of those projecs from GitHub, trying to display in a graphical manner, how "alive" that project is, because, lets be honest, if you're going to spend your time contributing to someone else's project, you want to make sure that, at least, it's still under development, otherwise, you'll probably sending pull requests that will never be answered.

##Contributing to the project.

This project has a lot more features coming, specially related to project and user statistics, so if you feel like contributing, just go the it's repo, create a ticket and we'll go from there :)

OR, you can just email me at: deleteman[at]gmail[dot]com 

###Running the site locally

If you're planning on cloning the site and running it locally, follow these simple steps:

1. Clone the repo
2. Create a `tmp` directory on the project directory
3. `$ cp config/config{.base,}.yml && cp config/database{.base,}.yml`
4. Run ./makiavelo.php db:create
5. Run ./makiavelo.php db:load
6. Run ./makiavelo.php db:migrate
7. Configure a virtual host, with the following information
```
<VirtualHost *:80>
    ServerAdmin webmaster@dummy-host2.example.com
    DocumentRoot "<path to the public folder>""
    ServerName lfpr.local.com
    SetEnv makiavelo_env "development"
    
   <Directory "<path to the public folder>">
      AllowOverride All 
  </Directory>
</VirtualHost>
```
5.1 If you're using Apache 2.4+ then you should add one more line after "AlloOverride All": Require all granted
6. Add an entry to your hosts file, so that the virtual host will work.
7. Make sure the /tmp folder inside the project is writable by  Apache (if in doubt, give it a 777)
8. ????
9. Profit!



###Contributing

If you feel like helping out by bug-fixing, or contributing with a new feature or whatever, just follow these simple steps:

1. Create an issue for it.
2. Fork the repo.
3. Make your changes.
4. Commit and create a pull request.
5. Be happy :)


##More?

Wanna know more about the author? Check out my other repos at: http://github.com/deleteman
Wanna know more about the DadMokey Group? Go to http://dad.uy
