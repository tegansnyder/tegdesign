<!--
{{ $page_title = 'Harness The Power of Google Custom Search on Your Website' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'Harness The Power of Google Custom Search on Your Website', 'sub_txt' => 'Posted on August 27, 2010 at 3:13 am'])



<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">


<p>I often find myself wanting to incorporate a search engine into a website. While there are many solutions available in a variety of different languages both open sourced and commercial out there sometimes the best solution is right in front of you. This is what Google does best, now isn&#8217;t it? In this tutorial I will show you how to include Google search technology into your website.<span id="more-48"></span></p>
<p><strong>Step 1</strong></p>
<p>Head on over to the <a href="http://www.google.com/cse/" target="_blank">Google Custom Search website</a>.</p>
<p><a href="http://www.tegdesign.com/wp-content/uploads/2010/08/cse1.jpg"><img class="alignnone size-medium wp-image-52" title="cse1" src="http://www.tegdesign.com/wp-content/uploads/2010/08/cse1-300x220.jpg" alt="" width="300" height="220" /></a></p>
<p>Signup for an account.</p>
<p><a href="http://www.tegdesign.com/wp-content/uploads/2010/08/cse2.jpg"><img class="alignnone size-medium wp-image-53" title="cse2" src="http://www.tegdesign.com/wp-content/uploads/2010/08/cse2-300x220.jpg" alt="" width="300" height="220" /></a></p>
<p>Fill in the required fields.</p>
<p><a href="http://www.tegdesign.com/wp-content/uploads/2010/08/cse3.jpg"><img class="alignnone size-medium wp-image-54" title="cse3" src="http://www.tegdesign.com/wp-content/uploads/2010/08/cse3-300x184.jpg" alt="" width="300" height="184" /></a></p>
<p>Click on Look &amp; Feel</p>
<p>Change the display option to IFrame. Here you can alter the settings. Once complete click Get-Code.</p>

	    
<div id="disqus_thread">
    </div>

<script>
var disqus_url = 'http://www.tegdesign.com/harness-the-power-of-google-custom-search-on-your-website/';
var disqus_identifier = '48 http://www.tegdesign.com/?p=48';
var disqus_container_id = 'disqus_thread';
var disqus_shortname = 'tegdesign';
var disqus_title = "Harness The Power of Google Custom Search on Your Website";
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
        script.src = '?cf_action=sync_comments&post_id=48';

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