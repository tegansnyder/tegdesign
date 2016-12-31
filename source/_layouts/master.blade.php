<!DOCTYPE html>
<html lang="en">
    <head>
        @include('_partials.head', ['title' => $page_title])
    </head>
    <body class="{{ $page_body_class }}">

    	@include('_partials.header')

    	<div class="page-wrapper">
        	@yield('body')
        </div>

        @include('_partials.footer')
        
    </body>
</html>