<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Streams Setup
|--------------------------------------------------------------------------
|
*/

$config['namespace']	= 'ee_namespace';

$config ['prefix']	= 'cp_';

$config ['pagination']	= 10;

/*
|--------------------------------------------------------------------------
| All Streams Fields
|--------------------------------------------------------------------------
|
*/

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

/*
|--------------------------------------------------------------------------
| All Streams and Streams Assginments 
|--------------------------------------------------------------------------
|
*/

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

/*
|--------------------------------------------------------------------------
| Streams Data
|--------------------------------------------------------------------------
|
*/

$config ['data']	= array(

'categories' => array(
		array('category_title' => 'News'),
		'ref' => array('category_title' => 'Cars'), /* a unique key is used here to mark this row 'ref' */
		array('category_title' => 'Music')
	),

'faqs' => array(
		array('question' => 'What is the best green car for 2013?','answer' => 'Nothing', 'category_select' => 'categories@ref'),
	)

);

/* End of file streams.php */




