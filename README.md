# PyroCMS Streams CRUD Sample Module

This module makes it easy to take advantage of PyroCMS Streams Core by providing a simple CRUD controller. Streams can be described in a config file along with the fields and assignments. These are going to be created when the module is installed. This module also support Streams data, allowing to list the data values of each stream including for relationship type fields.

This module was written to make it easily modified and customized, including the module name (folder name etc).

## Installation

To install this module unzip the folder and place it into  `addons/default/modules`, then name it `StreamsCRUD`

## How to use

Here are the steps you need to follow to create a new stream(s). Start by editing `addons/default/modules/StreamsCRUD/config/streams.php`. The $config ['fields'] array contains all the fields used in your module, add more as approperiate. Notice that, `category_select` field is a relationship type. The value of `choose_stream` should be the target stream slug (i.e. `categories` in this example):

```php
$config ['fields']	= array(

	'question'        => array( 'name' => 'Question', 
											 			  'type' => 'text',
											 			  'extra' => array('max_length' => 200)),

	'answer'          => array( 'name' => 'Answer', 
										  		    'type' => 'textarea'),

	'category_title'  => array( 'name' => 'Title', 
										 	 			  'type' => 'text'),

	'category_select' => array( 'name' => 'Category', 
														  'type' => 'relationship',
														  'extra' => array('choose_stream' => 'categories'), /* place the stream_slug of the 
														  																											relationship target stream */
											 ),
	);
```
	
After all your fields are defined, create the required streams, see below:

```php
$config ['streams']	= array(

	'faqs' 			=> array('name'    => 'FAQs', 
											 'about'   => 'Faq table', 
											 'fields'  => array(
													'assign'       => array('question','answer','category_select'),
													'view_options' => array('id', 'question','answer','category_select'),
													'required'     => array('question','answer'),
													'unique'       => array('question','answer'),
													'title_column' => array('question')
												)
		),
	'categories' => array('name'   => 'Categories', 
												'about'  => 'Categories table', 
												'fields' => array(
														'assign'       => array('category_title'),
														'view_options' => array('id','category_title'),
														'required'     => array('category_title'),
														'unique'       => array('category_title'),
														'title_column' => array('category_title')
												)
		)
);
```

Data can be added to each stream:

```php
$config ['data']	= array(

'categories' => array(
		array('category_title' => 'News'),
		/* a unique key is used here to mark this row 'ref' */
		'ref' => array('category_title' => 'Cars'), 
		array('category_title' => 'Music')
	),

'faqs' => array(
		array('question' => 'What is the best green car for 2013?',
					'answer' => 'Toyota Avalon Hybrid ', 
					'category_select' => 'categories@ref'),
	)

);
```

Notice that, the question `What is the best green car for 2013?` is linked to the right category using a special reference key `ref`, combined with the target stream slug `categories@ref`.

For each new stream added, create a controller in `addons/default/modules/StreamsCRUD/controllers`, with `admin_` prefix. For example, `admin_faqs.php` for the `faqs` stream. The content of this controller is kept simple:

```php
require 'admin_streams.php';

class Admin_faqs extends Admin_streams {
    public $section = 'faqs';
}
```

Edit the module language file, `addons/default/modules/StreamsCRUD/language/english/StreamsCRUD_lang.php`, to include the necessarly language lines per stream, here's and example for the `faqs` stream

```php
// FAQs stream
$lang[MODULE_NAME.':faqs']            = 'FAQs';
$lang[MODULE_NAME.':faqs:new']				= 'New FAQ';
$lang[MODULE_NAME.':faqs:edit']				= 'Edit FAQ';
```

These lines are mandatory per stream.

Finally, edit the routes config file at `addons/default/modules/ee/config/routes.php` and insert routes for new streams as follows:

```php
$route[MODULE_NAME.'/admin/categories(:any)'] = 'admin_categories$1';
$route[MODULE_NAME.'/admin/faqs(:any)'] = 'admin_faqs$1';
```

## Change Module Name

It has been made easy to change the module name in this addon, so that you can use it with your own projects. To do so (for example, change the name to `MyModule`), follow these steps

* Rename the module folder to `MyModule`, `addons/default/modules/MyModule`
* Rename class `Module_StreamsCRUD` at `addons/default/modules/MyModule/details.php` to `Module_MyModule`
* Edit the file `addons/default/modules/MyModule/config/constants.php`, replace `StreamsCRUD` to `MyModule`
* Rename the language file at `addons/default/modules/MyModule/language/english` from `StreamsCRUD_lang.php` to `MyModule_lang.php`
* Rename the front-end controller `StreamsCRUD.php` at `addons/default/modules/MyModule/controllers` to `MyModule.php`, and do the same to the class name
* Finally, change the module details in `addons/default/modules/MyModule/config/config.php`
