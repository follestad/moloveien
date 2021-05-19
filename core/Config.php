<?php

namespace Core;

use Dotenv\Dotenv;

class Config {

    private array $env = [];




    /**
     * Validate Required Environments
     *
     * @param Dotenv $env
     */
    private function validateRequiredEnvironments(Dotenv $env): void {
        $env->required( 'SOURCE_FOLDER' )->notEmpty();
        $env->required( 'PUBLIC_FOLDER' )->notEmpty();
    }




    /**
     * Config constructor.
     */
    public function __construct() {
        $env = Dotenv::createImmutable( ROOT_DIR );
        $env->load();
        $this->validateRequiredEnvironments( $env );
    }




    /**
     * Get Environment Config
     *
     * @param string $key
     *
     * @return mixed
     */
    public function env(string $key): mixed {

        $value = getenv( strtoupper( $key ) );

        if( in_array( $value, [ 'TRUE', 'FALSE', 'true', 'false' ] ) ) {
            $value = filter_var( $value, FILTER_VALIDATE_BOOLEAN ) ?? false;
        }

        if( ! is_bool( $value ) && filter_var( $value, FILTER_VALIDATE_INT ) !== false ) {
            $value = (int)$value;
        }

        return $value;

    }




    /**
     * Set source directory
     *
     * This is where Twig templates and resources are stored.
     * Source folder is not something we deploy for web, but
     * rather use as a builder for our websites.
     */
    public function source_folder(): string {
        return trim( $this->env( 'SOURCE_FOLDER' ), '/' ) . "/";
    }




    /**
     * Define public directory
     *
     * PHP will generate and store static HTML to this folder.
     * This directory is what we want to deploy for web.
     * Because it is static we can use CDN networks like
     * Netlify and other similar services.
     */
    public function public_folder(): string {
        return trim( $this->env( 'PUBLIC_FOLDER' ), '/' ) . "/";
    }




    public function resolveTemplatePath(string $path): string {
        $template_path = "$path.twig";
        if( ! is_file( SOURCE_DIR . $template_path ) ) {
            $template_path = "$path/index.twig";
        }
        return $template_path;
    }

}
