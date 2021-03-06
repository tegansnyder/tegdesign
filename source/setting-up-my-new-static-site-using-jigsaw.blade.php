<!--
{{ $page_title = 'Setting up my new static site using Jigsaw' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'Setting up my new static site using Jigsaw', 'sub_txt' => 'Posted on Jan 1, 2017 at 10:00 am'])


<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">


<p>Welcome to 2017. Each year over the holidays I try to do something new to my neglected website. This year I decided to update the layout and use the static site generator <a href="http://jigsaw.tighten.co/" target="_blank">Jigsaw</a> to make it easier to maintain.</p>
 
<p>Jigsaw is a framework for rapidly building static sites and bakes in support for Laravel Elixir so you can compile your CSS and Javascript assets the same way you're used to in Laravel. Since I love Laravel and PHP it seems like a perfect fit for my website.</p>

<h2>How I got started</h2>

<p>To begin I simply created a new directory on my local computer and ran a composer command to bring in the dependencies:</p>
<pre><code class="bash">composer global require tightenco/jigsaw</code></pre>

<p>Let composer do it's work then you will have a basic structure setup. Next to take advantage of browsify and gulp you will need to:</p>
<pre><code class="bash">npm install
npm install -g browserify
npm install -g gulp</code></pre>

<p>I wanted to use SASS and also use the wonderful bootstrap framework to help build the site quickly. To use Bootstrap SASS with Jigsaw you need to make a quick edit to your "package.json" file. Here is what mine looks like:</p>
<pre><code class="json">{
  "private": true,
  "devDependencies": {
    "gulp": "^3.8.8",
    "laravel-elixir": "^4.2.0",
    "yargs": "^4.6.0",
    "bootstrap-sass": "^3.0.0"
  },
  "dependencies": {
    "jquery": "^3.1.1"
  }
}</code></pre>

<p>Afterwards you need to run "npm install" to bring in the new dependencies:</p>

<pre><code class="bash">npm install
npm install -g browserify</code></pre>

<p>To bring in the fonts associated with bootsrap I made another change to my "gulffile.js". Here is what it looks like:</p>
<pre><code class="json">var gulp = require('gulp');
var elixir = require('laravel-elixir');
var argv = require('yargs').argv;

elixir.config.assetsPath = 'source/_assets';
elixir.config.publicPath = 'source';

elixir(function(mix) {
    var env = argv.e || argv.env || 'local';
    var port = argv.p || argv.port || 3000;

    mix.copy(
        'node_modules/bootstrap-sass/assets/fonts/bootstrap/glyphicons-halflings-regular.eot',
        'source/fonts'
    ).copy(
        'node_modules/bootstrap-sass/assets/fonts/bootstrap/glyphicons-halflings-regular.ttf',
        'source/fonts'
    ).copy(
        'node_modules/bootstrap-sass/assets/fonts/bootstrap/glyphicons-halflings-regular.woff',
        'source/fonts'
    ).copy(
       'node_modules/bootstrap-sass/assets/fonts/bootstrap/glyphicons-halflings-regular.woff2',
       'source/fonts'
    );

    mix.sass('main.scss')
        .exec('jigsaw build ' + env, ['./source/*', './source/**/*', '!./source/_assets/**/*'])
        .browserify('app.js')
        .browserSync({
            port: port,
            server: { baseDir: 'build_' + env },
            proxy: null,
            files: [ 'build_' + env + '/**/*' ]
        });
});</code></pre>

<p>I created a few files to segment the layout of my site, In my "_source/layouts/master.blade.php" I have the following:</p>

@verbatim
<pre><code class="html">&#x3C;!DOCTYPE html&#x3E;
&#x3C;html lang=&#x22;en&#x22;&#x3E;
    &#x3C;head&#x3E;
        @include('_partials.head', ['title' => $page_title])
    &#x3C;/head&#x3E;
    &#x3C;body class=&#x22;{{ $page_body_class }}&#x22;&#x3E;

    	@include('_partials.header')

    	&#x3C;div class=&#x22;page-wrapper&#x22;&#x3E;
        	@yield('body')
        &#x3C;/div&#x3E;

        @include('_partials.footer')
        
    &#x3C;/body&#x3E;
&#x3C;/html&#x3E;</code></pre>
@endverbatim


<p>To segment out my sites head tag HTML I created a new partial in "_partials/head.blade.php" with the following content:</p>

@verbatim
<pre><code class="php">&#x3C;!DOCTYPE html&#x3E;
&#x3C;meta charset=&#x22;utf-8&#x22;&#x3E;
&#x3C;meta name=&#x22;viewport&#x22; content=&#x22;width=device-width, initial-scale=1, shrink-to-fit=no&#x22;&#x3E;
&#x3C;meta http-equiv=&#x22;x-ua-compatible&#x22; content=&#x22;ie=edge&#x22;&#x3E;
&#x3C;link rel=&#x22;stylesheet&#x22; href=&#x22;/css/main.css&#x22;&#x3E;

@if ($page_body_class == 'page-blog-post')
&#x3C;link rel=&#x22;stylesheet&#x22; href=&#x22;/css/syntax-highlighter/dracula.css&#x22;&#x3E;
&#x3C;script src=&#x22;/js/highlight.pack.js&#x22;&#x3E;&#x3C;/script&#x3E;
&#x3C;script&#x3E;hljs.initHighlightingOnLoad();&#x3C;/script&#x3E;
@endif

&#x3C;title&#x3E;{{ $title }}&#x3C;/title&#x3E;
</code></pre>
@endverbatim

<p>To segment out the header of my site I created a new partial in "_partials/header.blade.php" with the following content:</p>

@verbatim
<pre><code class="php">&#x3C;header&#x3E;

&#x9;&#x3C;div class=&#x22;container&#x22;&#x3E;

&#x9;&#x9;&#x3C;div class=&#x22;row&#x22;&#x3E;

&#x9;&#x9;&#x9;&#x3C;div class=&#x22;col-xs-5 col-sm-4 col-md-4 col-lg-4 no-pad-lr logo-wrapper&#x22;&#x3E;
&#x9;&#x9;&#x9;&#x9;&#x3C;div class=&#x22;logo-container&#x22;&#x3E;
&#x9;&#x9;&#x9;&#x9;&#x9;&#x3C;span class=&#x22;logo&#x22; onclick=&#x22;window.location=&#x27;/&#x27;&#x22;&#x3E;
&#x9;&#x9;&#x9;&#x9;&#x9;&#x9;&#x3C;strong&#x3E;teg&#x3C;/strong&#x3E;
&#x9;&#x9;&#x9;&#x9;&#x9;&#x9;&#x3C;span&#x3E;design&#x3C;/span&#x3E;
&#x9;&#x9;&#x9;&#x9;&#x9;&#x3C;/span&#x3E;
&#x9;&#x9;&#x9;&#x9;&#x9;&#x3C;span class=&#x22;motto&#x22;&#x3E;
&#x9;&#x9;&#x9;&#x9;&#x9;&#x9;web work by tegan snyder
&#x9;&#x9;&#x9;&#x9;&#x9;&#x3C;/span&#x3E;
&#x9;&#x9;&#x9;&#x9;&#x3C;/div&#x3E;
&#x9;&#x9;&#x9;&#x3C;/div&#x3E;

&#x9;&#x9;&#x9;&#x3C;div class=&#x22;col-xs-7 col-sm-8 col-md-8 col-lg-8 nav-wrapper&#x22;&#x3E;
&#x9;&#x9;&#x9;&#x9;&#x3C;nav class=&#x22;pull-right&#x22;&#x3E;
&#x9;&#x9;&#x9;&#x9;&#x9;&#x3C;a href=&#x22;/&#x22; class=&#x22;home-link&#x22;&#x3E;Home&#x3C;/a&#x3E;
&#x9;&#x9;&#x9;&#x9;&#x9;&#x3C;a href=&#x22;/about&#x22;&#x3E;About&#x3C;/a&#x3E;
&#x9;&#x9;&#x9;&#x9;&#x9;&#x3C;a href=&#x22;/blog&#x22;&#x3E;Blog&#x3C;/a&#x3E;
&#x9;&#x9;&#x9;&#x9;&#x9;&#x3C;a href=&#x22;/contact&#x22;&#x3E;Contact&#x3C;/a&#x3E;
&#x9;&#x9;&#x9;&#x9;&#x3C;/nav&#x3E;
&#x9;&#x9;&#x9;&#x3C;/div&#x3E;

&#x9;&#x9;&#x3C;/div&#x3E;

&#x9;&#x3C;/div&#x3E;

&#x3C;/header&#x3E;</code></pre>
@endverbatim

<p>Finally to segment out the footer HTML of my site I created a new partial in "_partials/footer.blade.php" with the following content:</p>

<pre><code class="html">&#x3C;footer class=&#x22;container-fluid&#x22;&#x3E;
&#x9;&#x3C;div class=&#x22;container&#x22;&#x3E;
&#x9;&#x9;&#x3C;div class=&#x22;row footer-row&#x22;&#x3E;
&#x9;&#x9;&#x9;&#x3C;div class=&#x22;col-xs-12 col-sm-6 col-md-6 col-lg-6&#x22;&#x3E;
&#x9;&#x9;&#x9;&#x9;&#x3C;p&#x3E;&#x26;copy; 2017 Tegan Snyder, All Rights Reserved.&#x3C;/p&#x3E;
&#x9;&#x9;&#x9;&#x3C;/div&#x3E;
&#x9;&#x9;&#x9;&#x3C;div class=&#x22;col-xs-12 col-sm-6 col-md-6 col-lg-6&#x22;&#x3E;
&#x9;&#x9;&#x9;&#x9;&#x3C;div class=&#x22;footer-question&#x22;&#x3E;
&#x9;&#x9;&#x9;&#x9;&#x9;Have a question? &#x3C;a href=&#x22;/contact&#x22; class=&#x22;contact-btn&#x22;&#x3E;Contact Me&#x3C;/a&#x3E;
&#x9;&#x9;&#x9;&#x9;&#x3C;/div&#x3E;
&#x9;&#x9;&#x9;&#x3C;/div&#x3E;
&#x9;&#x9;&#x3C;/div&#x3E;
&#x9;&#x3C;/div&#x3E;
&#x3C;/footer&#x3E;

&#x3C;script src=&#x22;/js/app.js&#x22;&#x3E;&#x3C;/script&#x3E;</code></pre>

<p>When I structure new pages on my site I simply create a new page in called "page-name-here.blade.php" and place it in the "source" folder:</p>

<pre><code class="bash">touch source/page-name-here.blade.php</code></pre>

<p>The contents of this file look something like this:</p>

@verbatim
<pre><code><!--
{{ $page_title = 'My Page Title Goes Here this variable is used by head partial' }}
{{ $page_body_class = 'put-a-class-name-here-for-css-styling-specific-pages-by-body-classs' }}
-->

@extends('_layouts.master')

@section('body')

&#x3C;p&#x3E;Page content goes here&#x3C;/p&#x3E;

@endsection</code></pre>
@endverbatim

<p>Notice the variables on this first two lines. That is a hacky way to control the page title in the "_partials/head.blade.php" and also append a body class, so we can apply individual styles to pages using the SASS file in "_assets/sass/main.scss".</p>

<p>In my SASS file ("_assets/sass/main.scss") I changed the first few lines to bring in the bootstrap fonts and also include the bootstrap SASS styles.</p>

@verbatim
<pre><code class="sass">$icon-font-path: '../fonts/';

@function font-path($path) {
  @return $path;
}

@import "node_modules/bootstrap-sass/assets/stylesheets/bootstrap";

/* Page: Example of styling specific to a page */
.page-blog-post {
	.whatever {
		color: #000;
	}
}</code></pre>
@endverbatim

<p>Finally in the javascript file you need to bring in the bootstrap JS and JQuery. To do this edit "_assets/js/app.js" and make the first few lines look like this:</p>

<pre><code class="js">window.$ = window.jQuery = require('jquery')
require('bootstrap-sass');</code></pre>

<p>To build the website and preview it locally you just need to run:</p>

<pre><code class="bash">gulp watch</code></pre>

<p>You will get a preview page up for your website that will automatically update as you make changes, thanks to browsify.</p>

<p>Sometimes you make run into an issue where a new page doesn't get picked up. You can always run jigsaw to build it manually:

<pre><code class="bash">jigsaw build</code></pre>

<p>Jigsaw places your locally compiled website in the "build_local" folder. You can also issue a command to build a production version:</p>

<pre><code class="bash">jigsaw build production</code></pre>

<p>When it comes time to deploying you can setup a Github webhook on your repository, or follow one of the many methods in the <a href="http://jigsaw.tighten.co/docs/deploying-your-site/" target="_blank">Jigsaw documnentation.</a>

<p>Overall I'm liking this setup so far. It allows me complete flexibility over the control of the website and produces fast loading static site I can easily host on a Digital Ocean $5 droplet.</p>


<div id="disqus_thread"></div>
<script>

var disqus_config = function () {
this.page.url = 'http://tegdesign.com/setting-up-my-new-static-site-using-jigsaw';
this.page.identifier = 'setting-up-my-new-static-site-using-jigsaw';
};

(function() {
var d = document, s = d.createElement('script');
s.src = '//tegdesign.disqus.com/embed.js';
s.setAttribute('data-timestamp', +new Date());
(d.head || d.body).appendChild(s);
})();
</script>
                                

</div>

</div>

</div>

@endsection