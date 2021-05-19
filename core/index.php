<?php

use Whoops\Run;
use Core\Config;
use Twig\Environment;
use Core\TwigExtension;
use Twig\Loader\FilesystemLoader;
use Whoops\Handler\PrettyPageHandler;

/** Define root directory */
define( 'ROOT_DIR', realpath( __DIR__ . '/../' ) . '/' );

/** Require PSR-4 Autoloader */
require ROOT_DIR . 'vendor/autoload.php';

/**
 * Set Error handler
 *
 * Because the PHP stack is for development only, we don't care
 * too much about catching exceptions. Instead we let it bubble up
 * and let Whoops display a nice and useful error page.
 */
( new Run() )->pushHandler( new PrettyPageHandler() )->register();


/**
 * Load Config
 */
$config = new Config();


/**
 * Set source directory
 *
 * This is where Twig templates and resources are stored.
 * Source folder is not something we deploy for web, but
 * rather use as a builder for our websites.
 */
define( 'SOURCE_DIR', ROOT_DIR . $config->source_folder() );


/**
 * Define public directory
 *
 * PHP will generate and store static HTML to this folder.
 * This directory is what we want to deploy for web.
 * Because it is static we can use CDN networks like
 * Netlify and other similar services.
 */
define( 'PUBLIC_DIR', ROOT_DIR . $config->public_folder() );


/**
 * Get the requested path
 */
$path = $_SERVER['REQUEST_URI'] ?? 'index';
$path = trim( parse_url( $path, PHP_URL_PATH ), '/' );

/**
 * Set template path
 *
 * Should match either file or folder structure.
 * Path must be relative to the source directory,
 * as Twig is set to only look inside source dir.
 */
$template_path = $config->resolveTemplatePath( $path );


/**
 * Load Twig & extensions
 */
$twigLoader = new FilesystemLoader( SOURCE_DIR );
$twig = new Environment( $twigLoader, [ 'debug' => true ] );
$twig->addExtension( new TwigExtension( $config ) );

/** Load all json files as data */
$data = [];
$json_scan = scandir( SOURCE_DIR );
foreach( $json_scan as $file ) {
    if( str_ends_with( $file, 'json' ) ) {
        $data = array_merge( $data, json_decode( file_get_contents( SOURCE_DIR . $file ), true ) );
    }
}


/**
 * Render Twig template
 * This will fail hard if template path does not exist.
 *
 * @noinspection PhpUnhandledExceptionInspection
 */
$template = $twig->render( $template_path, $data );

/**
 * Define cache file
 *
 * The generated static HTML file should follow the same
 * structure as the source folder.
 */
$static_file = str_replace( '.twig', '.html', PUBLIC_DIR . $template_path );
$generate_static_file = true;


/**
 * Check if we need to regenerate static file
 *
 * This will ONLY look at the main TWIG template file.
 * If changes are made to a included, embedded or extended
 * twig template, it WILL NOT regenerate cache.
 *
 * @todo: Check if we can trigger regenerate static file
 *      based on changes in extended templates.
 *
 */
if( is_file( $static_file ) && filemtime( SOURCE_DIR . $template_path ) <= filemtime( $static_file ) ) {
    $generate_static_file = true;
}

/**
 * Generate static HTML file
 * Turn on output buffering using ob_start.
 * Clean and save the output to a static HTML file
 */
if( $generate_static_file ) {

    // Create folders if they do not exist
    $static_folder = pathinfo( $static_file, PATHINFO_DIRNAME );
    if( ! is_dir( $static_folder ) ) {
        mkdir( $static_folder, 0777, true );
    }

    ob_start( function ($content) use ($config, $static_file) {

        // Remove HTML Comments
        $content = preg_replace( '/<!--(.*)-->/Uis', '', $content );

        // Remove line breaks
        $content = preg_replace( '/[\r\n]/', '', $content );

        // Save content
        if( false !== ( $f = fopen( $static_file, 'w' ) ) ) {
            fwrite( $f, $content );
            fclose( $f );
        }

        return $content;

    } );
}

// Output the template data
echo $template;
