<!--
{{ $page_title = 'Using the FullContact API on customers who complete purchases. My Magento Extension.' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'Using the FullContact API on customers who complete purchases. My Magento Extension.', 'sub_txt' => 'Posted on December 24, 2012 at 7:30 pm'])



<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">


	      <div class="wp-caption"><img alt="" src="https://raw.github.com/tegansnyder/Magento-FullContact-After-Sale-Customer-Details/master/screenshot1.png" /></div>
<p>If you are not familiar with FullContact check out their website at: <a href="http://www.fullcontact.com">http://www.fullcontact.com</a>. They have a nifty service that allows developers to gather social details for an email address. For instance, lets say a customer makes a purchase at your e-commerce website. What if after they made a purchase you could identify them as a influential blogger and reach out to them and offer a discount for writing a review of your product.</p>
<p>I built a very basic Magento extension that pulls the FullContact API and puts the details in a table for you to view.</p>
<p>Note: You will need a FullContact API key to get started.</p>
<p><a class="btn btn-primary" href="https://github.com/tegansnyder/Magento-FullContact-After-Sale-Customer-Details">Source code at Github</a></p>
<div class="wp-caption"><img alt="" src="https://raw.github.com/tegansnyder/Magento-FullContact-After-Sale-Customer-Details/master/screenshot2.png" /></div>

	    
<div id="disqus_thread">
    </div>

<script>
var disqus_url = 'http://www.tegdesign.com/using-the-fullcontact-api-on-customers-who-complete-purchases-my-magento-extension/';
var disqus_identifier = '826 http://www.tegdesign.com/?p=826';
var disqus_container_id = 'disqus_thread';
var disqus_shortname = 'tegdesign';
var disqus_title = "Using the FullContact API on customers who complete purchases. My Magento Extension.";
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
        script.src = '?cf_action=sync_comments&post_id=826';

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