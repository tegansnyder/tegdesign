<!--
{{ $page_title = 'Responsive CSS3 Animation 3D Rectangle' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'Responsive CSS3 Animation 3D Rectangle', 'sub_txt' => 'Posted on February 3, 2012 at 10:25 pm'])



<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">


<p>I was playing around with <a href="http://www.paulrhayes.com/2009-07/animated-css3-cube-interface-using-3d-transforms/">Paul Hayes 3D cube example</a> and thought I&#8217;d convert the Cube into a rectangle and modify the JavaScript selectors a bit to allow more than one 3D viewport. While I was doing this I thought, what the heck, lets make it responsive too. Resize your browser window to<a href="http://www.tegdesign.com/examples/css3-responsive-rect"> try it out</a>.</p>
<p><strong>Please note this only works in Chrome and Safari.</strong></p>
<p><a href="http://www.tegdesign.com/examples/css3-responsive-rect"><img class="alignnone size-full wp-image-209" title="CSS3 3D responsive animation" src="/img/ex.jpg" alt="" width="723" height="569" /></a></p>
<p>Code on <a href="https://github.com/tegansnyder/css3-responsive-3d-rectangle">Github</a></p>

	    
<div id="disqus_thread">
    </div>

<script>
var disqus_url = 'http://www.tegdesign.com/responsive-css3-animation-3d-rectangle/';
var disqus_identifier = '208 http://www.tegdesign.com/?p=208';
var disqus_container_id = 'disqus_thread';
var disqus_shortname = 'tegdesign';
var disqus_title = "Responsive CSS3 Animation 3D Rectangle";
var disqus_config_custom = window.disqus_config;
var disqus_config = function () {
    /*
    All currently supported events:
    onReady: fires when everything is ready,
    onNewComment: fires when a new comment is posted,
    onIdentify: fires when user is authenticated
    */
    
    
    this.language = '';
        this.callbacks.onReady.push(function () {

        // sync comments in the background so we don't block the page
        var script = document.createElement('script');
        script.async = true;
        script.src = '?cf_action=sync_comments&post_id=208';

        var firstScript = document.getElementsByTagName('script')[0];
        firstScript.parentNode.insertBefore(script, firstScript);
    });
    
    if (disqus_config_custom) {
        disqus_config_custom.call(this);
    }
};

(function() {
    var dsq = document.createElement('script'); dsq.type = 'text/javascript';
    dsq.async = true;
    dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
})();
</script>

	  


</div>

</div>

</div>

@endsection