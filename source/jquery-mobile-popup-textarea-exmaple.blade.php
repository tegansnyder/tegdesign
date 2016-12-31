<!--
{{ $page_title = 'JQuery Mobile Popup Textarea Exmaple' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'JQuery Mobile Popup Textarea Exmaple', 'sub_txt' => 'Posted on June 2, 2012 at 4:58 am'])



<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">


<p>This example is simple. Fixed header. Fixed footer. One button centered in the footer.</p>
<p>Click the &#8220;My Notes&#8221; button to display a popup containing a textbox.</p>
<p>Here is the link: <a title="Demo" href="http://tegdesign.com/tegansnyder-JQuery-Mobile-Popup-Textbox/">http://tegdesign.com/tegansnyder-JQuery-Mobile-Popup-Textbox/</a></p>
<p>Github source here: <a href="https://github.com/tegansnyder/JQuery-Mobile-Popup-Textbox">https://github.com/tegansnyder/JQuery-Mobile-Popup-Textbox</a></p>
<p><img alt="" src="https://raw.github.com/tegansnyder/JQuery-Mobile-Popup-Textbox/master/photo.png" /></p>

	    
<div id="disqus_thread">
    </div>

<script>
var disqus_url = 'http://www.tegdesign.com/jquery-mobile-popup-textarea-exmaple/';
var disqus_identifier = '238 http://www.tegdesign.com/?p=238';
var disqus_container_id = 'disqus_thread';
var disqus_shortname = 'tegdesign';
var disqus_title = "JQuery Mobile Popup Textarea Exmaple";
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
        script.src = '?cf_action=sync_comments&post_id=238';

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