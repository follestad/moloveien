# Moloveien - PHP Static HTML Generator
A simple and lightweight tool to generate static HTML sites with PHP and Twig.

### Some core features:
* Will use Twig as template engine. This makes HTML coding with "PHP" easier to read and work with.
* Has built in features to take any internal and external CSS or Javascript files and inline them for better performance.
* Will automatically take images and compress them to WebP with desired size. So you can just add normal HQ images to the source folder let the site generate optimized images for distribution.
* The core functionality with PHP, Twig, Typescript, PHP Composer and WebPack is only for development, so you can code quickly, ugly and use unoptimized scripts if you like.
* Has a simple docker file. Just run ````docker compose up -d```` visit http://localhost:8080 and the site will build the static HTML on page load.

### Composer
PHP Composer is required. Go the project in terminal and run
```
composer update
```
This will install all required dependencies. 

### Docker
Docker is optional, but will make things easier. Go to the project folder in your terminal and type ```docker compose up -d``` to get started. Visit http://localhost:8080 and your site should be running.

### Typescript - Webpack
The project comes with a predefined setup of Typescript with webpack. Just add your Javascript / Typescript code in the ```src/ts/main.ts``` file and run ```npm run build```. This will create an optimized .js file. Add this with a simple ```{{ js('main') }}``` inside your Twig template, and it will automatically be inlined.

### Image Optimization
Use ```{{ image('file.jpg') }}``` inside Twig to automatically create an image that will be optimized and stored in the ```dist``` folder.

### ENV-file
You must create a .env file in the root dir. Use the .env-example file as template.

---

One more thing, this project was just a fun little project, so the documentation is a bit short and incomplete. If you make something cool of this, please let me know.