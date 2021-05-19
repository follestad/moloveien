<?php

namespace Core;

use Twig\TwigFunction;
use Gumlet\ImageResize;
use Twig\Extension\AbstractExtension;

class TwigExtension extends AbstractExtension {

    public function __construct(private Config $config) {
    }

    /*
    |---------------------------------------------------------------------------
    | Load Extensions
    |---------------------------------------------------------------------------
    */

    /**
     * Get Functions - Register TwigFunctions
     *
     * @return array
     */
    public function getFunctions(): array {
        return [
            new TwigFunction( 'url', [ $this, 'url' ] ),
            new TwigFunction( 'css', [ $this, 'css' ] ),
            new TwigFunction( 'js', [ $this, 'js' ] ),
            new TwigFunction( 'image', [ $this, 'image' ] ),
            new TwigFunction( 'gallery', [ $this, 'gallery' ] ),
        ];
    }




    /**
     * Get site URL with optional path
     *
     *
     * @return string
     */
    public function url(): string {
        return 'I AM WORKING!';
    }




    public function resolveFile(string $file, string $type): string {
        if( ! str_starts_with( $file, 'http' ) ) {
            $file = SOURCE_DIR . "$type/" . rtrim( $file, ".$type" ) . ".$type";
        }
        return $file;
    }




    public function css(string $file): void {
        echo "<style>";
        $css = file_get_contents( $this->resolveFile( $file, 'css' ) );
        $css = str_replace( '@charset "UTF-8";', '', $css );
        echo $css;
        echo "</style>";
    }




    public function js(string $file): void {
        echo file_get_contents( $this->resolveFile( $file, 'js' ) ) . PHP_EOL;
    }




    public function gallery(string $folder) {
        $directory = SOURCE_DIR . "image/$folder";
        $images = scandir( $directory );
        foreach( $images as $image ) {
            foreach( [ 'jpg', 'jpeg', 'gif', 'webp', 'png' ] as $type ) {
                if( str_ends_with( strtolower( $image ), $type ) ) {
                    $image_url = $this->image( "$folder/$image", 650 );
                    echo "<figure><img src='$image_url' alt='gallery-$image' class='lazy' /></figure>";
                }
            }
        }
    }



    public function image(string $file, int $width = null): string {

        # Get path info
        $image = (object)pathinfo( $file );

        # Include width in filename
        $image->filename .= ( $width ) ? "-{$width}px" : '';

        # Set original extension
        $image->extension_original = $image->extension;

        # Define expected extension
        if( in_array( $image->extension_original, [ 'jpg', 'jpeg', 'png' ] ) ) {
            $image->extension = 'webp';
        }

        # Define image folder
        $image->folder = "/asset/image/" . $image->dirname . '/';

        # Define path relative to public dir
        $image->path = $image->folder . $image->filename . '.' . $image->extension;

        # Output path
        if( is_file( PUBLIC_DIR . $image->path ) ) {
            return '/' . ltrim( $image->path, '/' );
        }

        # Start with creating folders
        if( ! is_dir( PUBLIC_DIR . ltrim( $image->folder, '/' ) ) ) {
            mkdir( PUBLIC_DIR . $image->folder, 0777, true );
        }

        # Define source file
        $source_file = SOURCE_DIR . "image/$file";

        if( ! is_file( $source_file ) ) {
            throw new \Exception( "Unable to find file: $source_file" );
        }

        # Let us convert image
        if( in_array( $image->extension_original, [ 'jpg', 'jpeg', 'png' ] ) ) {
            $image_resize = new ImageResize( $source_file );
            if( $width ) {
                $image_resize = $image_resize->resizeToWidth( $width );
            }
            $image_resize->save( PUBLIC_DIR . $image->path, IMAGETYPE_WEBP, 60 );
        } else {
            copy( $source_file, PUBLIC_DIR . $image->path );
        }

        if( is_file( PUBLIC_DIR . $image->path ) ) {
            return '/' . ltrim( $image->path, '/' );
        } else {
            throw new \Exception( 'Unable to resolve image' );
        }

    }


}
