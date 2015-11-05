<?php
if ( function_exists('spl_autoload') ) {
  spl_autoload_extensions('.php');
  spl_autoload_register('uber_autoloader');
}

/* Class autoloader
  *      
  * @param string $class The fully-qualified class name.
  *
  * @return void
*/

function uber_autoloader($className) {
  $namespace = '';
  $dir = '';
  $namespaced_class = '';
  $class_prefix = 'class.';
  $dir_separator = '/';
  $theme_dir = 'wp-content/themes/bones/library';
  
  $FQCN = '';
  
  $path_components = explode("\\", $className);
  $namespace = strtolower( array_shift($path_components) );
  $directory = array_shift($path_components);
  $namespaced_class = current($path_components);
  
  $FQCN =   $theme_dir . '/' . $namespace . '/' . $directory . '/' . $class_prefix . $namespaced_class . spl_autoload_extensions();
  
  print '<pre>';var_dump($FQCN);print '</pre>';

  include ($FQCN);
}
