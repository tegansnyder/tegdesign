<!--
{{ $page_title = 'iScroll + JQuery Mobile Example' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'iScroll + JQuery Mobile Example', 'sub_txt' => 'Posted on January 11, 2012 at 4:28 pm'])



<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">


<p>For your viewing pleasure I would like to present a nice starting point for working with <a href="http://jquerymobile.com/">JQuery Mobile</a> and <a href="http://cubiq.org/iscroll-4">iScroll</a>.</p>
<p>Source code available on my Github account here: <a href="https://github.com/tegansnyder/iScroll-Example">https://github.com/tegansnyder/iScroll-Example</a></p>
<p><em>Note: this example is setup to work with PhoneGap 1.3 and Cross Domain Scripts.</em></p>
<p>&nbsp;</p>
<p>Here is a screenshot. I also included a nice purple theme.</p>
<p><a href="/img/iscroll.jpg"><img class="alignnone size-full wp-image-204" title="iscroll" src="/img/iscroll.jpg" alt="" width="320" height="480" /></a></p>
<p><a href="/img/purple.png"><img class="alignnone size-full wp-image-205" title="purple" src="/img/purple.png" alt="" width="568" height="582" /></a></p>

	    
<div id="disqus_thread">
    </div>

<script>
var disqus_url = 'http://www.tegdesign.com/iscroll-jquery-mobile-example/';
var disqus_identifier = '203 http://www.tegdesign.com/?p=203';
var disqus_container_id = 'disqus_thread';
var disqus_shortname = 'tegdesign';
var disqus_title = "iScroll + JQuery Mobile Example";
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
        script.src = '?cf_action=sync_comments&post_id=203';

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