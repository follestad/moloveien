<?php

namespace Core;

use Exception;
use Twig\TwigFunction;
use Gumlet\ImageResize;
use JetBrains\PhpStorm\Pure;
use Twig\Extension\AbstractExtension;

class TwigExtension extends AbstractExtension {


    /**
     * TwigExtension constructor.
     *
     * @param \Core\Config $config
     */
    public function __construct(
        private Config $config
    ) {
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
            new TwigFunction( 'config', [ $this, 'config' ] ),
            new TwigFunction( 'url', [ $this, 'url' ] ),
            new TwigFunction( 'css', [ $this, 'css' ] ),
            new TwigFunction( 'js', [ $this, 'js' ] ),
            new TwigFunction( 'image', [ $this, 'image' ] ),
            new TwigFunction( 'gallery', [ $this, 'gallery' ] ),
        ];
    }




    /**
     * Get Config data
     *
     * @param string $key
     *
     * @return mixed
     */
    #[Pure] public function config(string $key): mixed {
        return $this->config->env( $key );
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




    /**
     * Resolve file
     *
     * @param string $file
     * @param string $type
     *
     * @return string
     */
    public function resolveFile(string $file, string $type): string {
        if( ! str_starts_with( $file, 'http' ) ) {
            $file = SOURCE_DIR . "$type/" . rtrim( $file, ".$type" ) . ".$type";
        }
        return $file;
    }




    /**
     * Inline CSS
     *
     * @param string $file
     */
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




    /**
     * Pass a folder and it will automatically generate all images inside folder
     *
     * @param string $folder
     *
     * @throws \Exception
     */
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




    /**
     * @param string $file
     * @param int|null $width
     * @param bool $resize
     *
     * @return string
     * @throws \Gumlet\ImageResizeException
     * @throws \Exception
     */
    public function image(string $file, int $width = null, bool $resize = true): string {

        # Get path info
        $image = (object)pathinfo( $file );

        # Include width in filename
        $image->filename .= ( $width ) ? "-{$width}px" : '';

        # Set original extension
        $image->extension_original = $image->extension;

        # Define expected extension
        if( $resize && in_array( $image->extension_original, [ 'jpg', 'jpeg', 'png' ] ) ) {
            $image->extension = 'webp';
        }

        if( $image->dirname === '.' ) $image->dirname = '';

        # Define image folder
        $image->folder = rtrim( "/asset/image/" . $image->dirname, '/' ) . '/';

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
            throw new Exception( "Unable to find file: $source_file" );
        }

        # Let us convert image
        if( in_array( $image->extension_original, [ 'jpg', 'jpeg', 'png' ] ) && $resize ) {
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
            throw new Exception( 'Unable to resolve image' );
        }

    }


}
