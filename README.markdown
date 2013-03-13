#Makiavelo

Yet Another PHP Web Framework. This is my take at the problem: creating a framework capable of making the developer's life easier and 
helping improve development time.

##Sample included

The current repository contains code within the `app` folder, which is where the web application's code would reside. That is the code for a very basic example of how to use some of the tools provided by the framework to create a (yet again) super basic bloging app.

##Basic layout 

I've developed this idea over time, after working for a while with Ruby on Rails, so there will be a lot of similarities. I tried to port as many features as I could.

##jQuery & Bootstrap 

In order to help with the development speed, the framework comes bundled with the following packages:

+ jQuery 1.8.2
+ jQueryUI 1.8.23
+ jQuery.timePicker
+ Bootstrap

##Folder structure

###App/Controllers

All controllers will be store inside this folder.
The controller files and their classes will follow a simple naming convention:

Since controllers are usually created to deal with a specific entity (usually but not always), we need to follow these conventions:

+ Controller file is named as follows: [EntityName]Controller.php
+ Controller class is named as follows: [EntityName]Controller and extends the class `ApplicationController`

###App/Entities

This folder contains all the entities for your application. They'll be auto-required when first called and must follow a naming convention in order to be found: 
This convention is pretty simple, just name your file as follows: [YourEntityName]Class.php

The name of the entity should always be camel-case and the first character be uppercased. 
Finally, your entity should not have the "Class" part on it's name. So for a user entity, you'll have the following:

+ A file called `UserClass.php` inside the entities folder.
+ A class called `User` inside the `UserClass.php` file. `User` will extend `MakiaveloEntity`.

###App/Helpers

This is a purely organizational folder. In here you can store the helper functions that you create. They'll be accessible from everywhere on your app and the'll be auto-included.


###App/Views

Inside this folder Makiavelo will store all the view files. Currently, the framework does not support any kind of templating engine, but we eventually want to add that in.
As of this writing, all view files will have the extension "html.php", thus, they're all php files and you can write php code inside them as you would normally.
The only thing to take into consideration, is that the files will be stored inside folders named after the corresponding entity.

###Mappings

In order to create a new entity (or model) on Makiavelo, we need to first create a mapping for it. Mappings tell Makiavelo the basic structure of the entity as well as the validations that we'll have in place for that structure.
To define this mappings, we use YAML files inside the mappings folder.
Here's an example of what a mapping for a user entity would look like:

_user.yml_
```yaml
crud:
	entity:
		name: User
		fields:
			first_name: string
			last_name: string
			birth_date: date
			email: string
			password: string
		validations:
			birth_date: [presence, date]
			email: [presence, email]
			password: [presence]

```

Once we have our _user.yml_ ready, we execute the following command from the terminal:

`./makiavelo.php g crud user.yml`

The framework will then take the following actions:

+ Create the entity class based on our mapping file and store it on the `entities` folder.
+ Create the controller called UserControllerClass, with the basic crud methods and store it on the `controllers` folder.
+ Create the basic crud routes for our entity and add them to the `routes.php` file.
+ Create the SQL file with the create table statement and store it on the `sql/creates` folder.
+ Create the SQL helper files, with the basic crud functions you'll need to access the database (such as save_user, load_user, etc) and store it on the `sql/helpers` folder.
+ Create the basic views required for all the crud actions (show, edit, new, _form) and store them on the `views/User` folder.

_That's right!_ Makiavelo just did all that for you!

The next logical step would be to load the SQL file into the database, and ideally, Makiavelo would provide a way to do that for you, but right now, that's not implemented, so you'll have to do something like this: `mysql -u[username] -p -h[yourhost] [yourdatabase] < app/sql/creates/user.sql
This will go away soon, promiss!!

After loading the SQL into your database, you're ready to start creating new users, cool uh!? Just go to the url created for you, and you'll see what I mean (i.e: yourhost.com/user/new will take you to the new user form).

###App/SQL/Helpers

SQL helpers are functions created automatically by the CRUD generator to help the developer access the database and work with the entities easily.
All helper functions are named using the following convention:

 `[action]_[underscored entity name]`

For example, if we created an entity called User using the generator, we'd end up with the following sql helper functions:

+ __save_user__: Saves the entity on the database, receives the entity as a paremeter.
+ __update_user__: Updates the entity on the database, receives the entity as a paremeter.
+ __delete_user: Deletes the entity from the database. It receives the ID as a paremter.
+ __load_user__: Loads the user from the database and returns the user entity. Recieves an ID as paremeter.
+ __load_user_where__: Loads the user from the database and returns the user entity. Receives a WHERE condition to look for the entity.
+ __list_user__: Returns a list of user entities. Receives 2 optional parameters: order and limit

###App/SQL/Creates

Inside this folder, Makiavelo will store the SQL files autogenerated when creating a new entity. Currently, these files will need to be manually loaded into the database to create the table.

###Public

The public folder contains all the assets for your application. It'll come with 3 folders which you'll have to use for each time of asset:

* javascripts: It'll contain all the javascript files.
* img: It'll contain all images for your application.
* stylesheets: It'll contain all css files for your application.

Whatever your store inside public, you'll be able to access it directly like so: `yourdomain.com/public/yourfolder/yourfile.js`

###Lib

The lib folder is meant to contain all extra code added by the developer and also, the code for the user defined tasks.

##Using the framework
There are two sides to Makiavelo:

+ The command line, which has some usefull commands to do things like creating the tables for the different entities, or creating the basic required files for a standard CRUD system.
+ The web interface, which would the site/app being developed.

###Command line

There are several usefull commands to execute from the command line.
As of right now, the frameworks provides a file named: `makiavelo.php` which should have execution privileges. Executing that file alone, will list the different commands available:

+ Generator command (g): This command tells the framework to generate one of many things:
    + CRUD (crud): This attribute for the generator command will tell it to generate the basic structure for a CRUD system for a specific entity.
    + controller: This attribute will tell the generator to create a controller and it's views.
+ Database creator (db:create): It'll connect to our database using the configuration file and it'll create the database for our application
+ Database loader (db:load): It'll create all required tables for all our entities. As of right now, it doesn't allow us to pick which entity, so it'll load all of them.
+ Tasks: Makiavelo has support for tasks (similar to the ones used on RoR with Rake).

####Some examples:

__Generating a basic CRUD system for our entity called Post__
```
./makiavelo.php g crud post.yml
```

__Running a task called "createSuperUser" on the task namespace "Setup"__
```
./makiavelo.php task setup:createSuperUser
```


###Installation/Setup

In order to be able to load the application you developed with Makiavelo, you'll need to following:

+ Allow mod_rewrite on your apache config.
+ Allow the use of .htaccess files
+ Create a virtual host for your app, pointing to it's "public" folder
+ Configure the database access on the `config/database.yml` file. Right now, only MySQL is supported.
+ Configure your `/etc/hosts` file to point the new virtual host to your localhost
+ ????
+ Profit!

_NOTE_: I need to add more details to each point, but it should be pretty straight forward.

#More details

##Routes

The aim regarding routes, is to keep them REST oriented. Right now, the only two verbs supported are GET and POST, so we need to work on that front.

When using the CRUD generator, the system will automatically generate the required routes for all of the basic CRUD operations and it'll store that information on the `routes.php` file, located on the `config` folder.

An example of what can be found in that file is:

```php
$_ROUTES[] = array(
	"list" => array("url" => "/post/", "controller" => "Post", "action" => "index"),
	"create" => array("url" => "/post/create", "controller" => "Post", "action" => "create", "via" => "post", "role" => "user"),
	"new" => array("url" => "/post/new", "controller" => "Post", "action" => "new", "role" => "user"),
	"retrieve" => array("url" => "/post/:id", "controller" => "Post", "action" => "show", "via" => "get", "role" => "user" ),
	"update" => array("url" => "/post/:id/edit", "controller" => "Post", "action" => "edit", "role" => "user"),
	"delete" => array("url" => "/post/:id/delete", "controller" => "Post", "action" => "delete", "via" => "post", "role" => "user")
	); 

```

That will create all the required routes for every action needed.
Here is an explanation of each of the keys of those arrays:

+ url: This will contain the URL for the action, can be anything, and can contain attributes in the form of `:ATTR_NAME`.
+ controller: The name of the controller, without the "Controller" part (all controllers have that word on the class name).
+ action: The name of the method to execute from the controller. It'll map to a method called: `ACTION_NAMEAction` (i,e: indexAction).
+ via: This is an optional parameter, and will force the route to work using the specified HTTP verb (only values supported right now are `get` and `post`). The default value here is `get`.
+ role: Another optional parameter, usefull when your application needs to filter out actions based on the role of the user. 

Each entry in the array, will auto-generate a url helper function, so you can use that instead of hard-coding the urls all throught the site.

###URL helpers

The helpers are created dinamically, and they basically return the url, replacing any attribute with the correct value.
Here is how the helper functions are named:

```
[underscored_controller_name]_[underscored_action_name]_path
```
For instance, using the above example, we will have:

+ `post_list_path()` to redirect to the list of all posts.
+ `post_create_path()` will save the post information.
+ `post_retrieve_path($post)` will take us to the show view of the post controller, and it'll grab the attribute `id` from the entity passed as a parameter.
+ and so on.

There are other url helper functions are can be use, when, for instance, you need to get the edit path for a particular entity, but you don't know the entity's class.

These helpers are:

+ __show_path_for__ : Receives an entity as parameter, and it'll return the right path for the show view of that entity.
+ __edit_path_for__ : Receives an entity as parameter, and it'll return the right path for the edit  view of that entity.
+ __delete_path_for__ : Receives an entity as parameter, and it'll return the right path for the delete action of that entity.

##HTML helpers
Makiavelo provides some basic html helper functions to ease the development process.

There are two types of functions, the ones that require an entity and the generic ones.

###Entity related functions


####_form_for_
Returns the HTML for the opening tag of the form element.

**Paramters**
1. $en: The entity we're working with.
2. $http_action: (Optional, "create" by default). Rerefences the action that we'll be doing. It's a string that must match the name of the action you setup on the routing array.
3. $html_attrs: (Optional) Contains all extra html options for the form.

####_text_field_
Returns the HTML code for a text field. 

**Parameters**

1. $en: The entity we're working with.
2. $attr: The name of the etity's attribute.
3. $label: (Optional) If non-null, it'll add a label field surrounding the text field with the content we pass on this parameter.
4. $html_attr: (Optiona) Array containing other html attributes for the input field. In a key => value format (i.e: <code>array("id" => "my_id")</code>)

####_password_field_
Returns the HTML code for an input field of type password. For more infor refer to the <code>text_field</code> helper.

####_hidden_field_
Returns the HTML code for an input field of type hidden.

**Parameters**

1. $en: The entity we're working with.
2. $attr: The attribute that we're referencing.

####_select_field_

Returns the HTML code for a select field and it's options.

**Paramaters**

1. $en: The entity we're working with.
2. $attr: The attribute that we're referencing. If the value of this attriute equals the value of one of the options, that option will be auto-selected.
3. $label: (Optional) If non-null, it'll add a label field surrounding the text field with the content we pass on this parameter.
4. $options: (Optiona) Array containing other html attributes for the input field. In a key => value format (i.e: <code>array("id" => "my_id")</code>)


####_time_field_ 
Just like a text_field, but has a timePicker associated with it.

####_date_field_

Just like a text_field, but has a jQuery calendar associated with it.

####_email_field_

No difference with a text_field as of this writing.

####_file_field_
Returns the HTML code for an input field of type file.

**Parameters**
Refer to the parameters description of the <code>text_field</code> helper.

####_boolean_field_

Returns the HTML code for a checkbox. If the value of the attribute used is "1" it'll auto-check the checkbox.

**Parameters**

1. $en: The entity we're working with.
2. $attr: The attribute that we're referencing. If the value of this attriute equals the value of one of the options, that option will be auto-selected.
3. $label: (Optional) If non-null, it'll add a label field surrounding the text field with the content we pass on this parameter.

###Small example

Lets show how we would do a simple for for creating a <code>User</code> type entity:

```php
<?=form_for($this->entity)?>
  <?=text_field($this->entity, "username", "User name")?>
  <?=email_field($this->entity, "email", "Email")?>
  <?=date_field($this->entity, "birthdate", "Birthdate")?> 
  <?=password_field($this->entity, "password", "Password")?>
  <?=submit("Save User", array("class" => "btn btn-primary"))?>
 <?=end_form_tag()?>
```

In that example, we also used the <code>submit</code> helper, which is that simple, and the <code>end_form_tag</code> helper, which should be used at the ned of the for, to print the closing tag.

That form from the example could be used as a "New" form aswell as an "Edit" form.

###Generic helper functions

+ form_for_tag
+ end_form_tag
+ text_field_tag
+ password_field_tag
+ hidden_field_tag
+ select_field_tag
+ time_field_tag
+ date_field_tag
+ file_field _tag
+ boolean_field_tag
+ email_field_tag
+ link_to
+ submit
+ image_tag

##Validations

Makiavelo allows for easy validation on entities before saving them to the database. In order to set the validations, you need to set a specific private attribute called `$validations`.
That attribute needs to have the following structure:

```php
$validations = array("attr_name" => array("validation_name_1", "validation_name_2", ....))
```

Currently the following validations are supported:

+ presence
+ email
+ integer

The plan is to allow for custom validations to be created by the developer.

_A simple example_: The following code will setup the Post entity to validate for it's content, title and owner's email fields:

```php
class PostClass extends MakiaveloEntity {
	
	private $title;
	private $content;
	private $owner_email;

	static public $validations = array("title"=> array('presence'),
									   "content"=> array('presence'),
									   "owner_email"=> array('presence', 'email'),
								);
}
```

##Security

Currently Makiavelo supports the definition of "roles" for a given entity (normally a user) and it'll create a hierarchy, based on the order in which the roles are defined.
Lets look at an example of defining roles:


```php
 $__SECURITY = array(
	"roles" => array("anonymous", "user", "admin"),
	"class_name" => "User"
	);

```
Using the special array `$__SECURITY` we're able to set the different access roles, and the entity which will be used with them.
According to our definition, the "anonymous" user role is assigned to the new visitor before they login. They, after they login, a role will be assigned. And as expected, the hierarchy is: admin > user > anonymous

##Allowing or denying access to a specific role

How do we use the above information to allow or deny certain roles from accesing part of our site/web app?
Easy! just add the minimum allowed role on the routes file, for each route you want to protect. 
Lets look at an example:

```php
$_ROUTES[] = array(
	"list" => array("url" => "/post/", "controller" => "Post", "action" => "index", "role" => "user"),
	"create" => array("url" => "/post/create", "controller" => "Post", "action" => "create", "via" => "post", "role" => "admin"),
	"new" => array("url" => "/post/new", "controller" => "Post", "action" => "new", "role" => "admin"),
	"retrieve" => array("url" => "/post/:id", "controller" => "Post", "action" => "show", "via" => "get", "role" => "user" ),
	"update" => array("url" => "/post/:id/edit", "controller" => "Post", "action" => "edit", "role" => "admin"),
	"delete" => array("url" => "/post/:id/delete", "controller" => "Post", "action" => "delete", "via" => "post", "role" => "admin")
	); 
```

With the above configuration, we're allowing only logged in users to interact with the post urls. And only admins can create d, update and delte posts.

##Database connection

Currently Makiavelo only supports MySQL databases.
The connection information is defined inside the `database.yml` file, located on the `config` folder.

##Localization

Makiavelo tries to handle internationalization just as Rails does. Inside the project, there is a sub-project (that we should eventually move out into its own project) called I18n, which provides the same helpers.

###Locale files

Inside the `config/locales/` folder the use must create the different locale files, organized by folders named after the desired language. 
i.e The folder _config/locales/en_ will contain all english localization strings, and a _config/locales/es_ will have all the spanish equivalents.
The format for the localization files is the same as the one used by I18n on rails, a YAML file with the following structure:

```yml
en:
	usuario:
		atributos:
			nombre: Name
			edad: Age
``` 

###Helper functions

The following helper functions are provided:

+ __t__: Short for `I18n::translate`, which receives a string parameter that acts as the key path inside the yml and returns the translation string or an error message if the key path is invalid. Using the yml from above, you could grab the translation for the `nombre` attribute of `usuario` like so: `t("usuario.atributos.nombre")`
+ __l__: Short for `I18n::localize`, which takes a time or date value and returns a string formatted in the right format.

###Setting the current locale

The I18n module provides a `config` method, which takes an array as an attribute. the array provided, will contain all the configuration options for the module.

The current supported configuration values are:

+ __locale__: The desired locale to use.

So, configuring the locale to __es__ would be done like this: 
```php
 I18n::config(array("locale" => "es"));
```

##Flash

Makiavelo tries to borrow the concept of flash message from Rails (and other frameworks) using the `flash` object available to the developer inside every controller.
The flash object implements the magic method `__call`, so the way to use this object is to call the method desired with the "set" prefix when we want to set the message, and the "get" prefix when we want to get the saved message.
We'll only be able to get the message once, after that, it'll be removed from the current session.

_Example_

```php
//... code snippet inside a controller
$this->flash->setError("There was a problem saving the user, please try again");
//...more code goes here...
```

Now, inside the view:
```php
<p class="error"><?=$this->flash->getError()?></p>
``` 

That example uses the "getError" and "setError" methods, but they could've easily been "getMyErrorMessage" and "setMyErrorMessage".
	
##Tasks

Makiavelo attempts to "borrow" the concept of tasks from Ruby on Rails, by allowing the developer to define custom methods that can be executed from the command line.

Lets look at an example of how you'd execute a task, and then, we'll talk about how to define one:

`./makiavelo.php task users:createFirsUser`

In that line, we're telling Makiavelo to execute a task within the "users" namespace, called "createFirstUser". That translates to the execution of the method "createFirstUser" from the class "UsersTask".

In order to define a task, the dev will have to following these conventions:

+ The file will have to be saved inside the __/lib/tasks__ folder.
+ The file will have to be named like this: [underscored namespace]_task.php
+ The class will have to be called [CamelCased namespace]Task.


#To-Do

Makiavelo is an ongoing project, and as such, it still requires a lot of testing, refactor and rethinking of modules. 
Some of the areas that need mayor work are:

+ __DB Access__: I'm currently using mysql_* functions, but in the future I wanted to improve the code, by using PDO and forgetting about the SQL helpers that Makiavelo creates for every entity.
+ __Routes__: Currently the global routes array feels wrong, there should be an easier, more elegant way of handling routes.
+ __Templating__: The original idea for Makiavelo, was to force the use of HAML, but the current implementation made it quite difficult to implement, so we need to refactor the way views and partials are handled, in order to be able to use HAML and any other templating engine.
+ __Lots more!__

#Contribute
Please, feel free to  fork, improve and create a pull request! All contributions are welcomed! :)
Also, if you want to get in touch with me, you can send e-mails to: deleteman[at]gmail[dot]com