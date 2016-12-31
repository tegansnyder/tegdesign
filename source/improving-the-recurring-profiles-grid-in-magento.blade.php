<!--
{{ $page_title = 'Improving the Recurring Profiles Grid in Magento' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'Improving the Recurring Profiles Grid in Magento', 'sub_txt' => 'Posted on December 24, 2012 at 4:48 pm'])



<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">


<p>Recurring Profiles are in Beta, but that doesn&#8217;t mean we can&#8217;t spice up the grid and add some new columns for customer information, email, id, next bill date, etc. Here is an example:</p>
<div class="wp-caption"><img class="size-full wp-image-814" alt="Recurring Profile Grid" src="/img/rec.png" />I added new columns to the recurring profile grid</div>
<p>Just <a href="https://github.com/tegansnyder/Magento-Recurring-Beta-Grid-Improvements">download my code from Github</a> and upload the folder structure to the root of your Magento folder. It doesn&#8217;t alter any core files (good), it is a app/code/local/Mage version of the grid.</p>
<p>It will add a few columns to the &#8220;Sales&#8221; -&gt; &#8220;Recurring Profiles (beta)&#8221; tab in the Magento admin.</p>
<pre>Additional Info (unserialized) Coupon Used, Customer Email, Customer Id, Customer Name, Next Bill Date</code></pre>



	    
<div id="disqus_thread">
    </div>

<script>
var disqus_url = 'http://www.tegdesign.com/improving-the-recurring-profiles-grid-in-magento/';
var disqus_identifier = '813 http://www.tegdesign.com/?p=813';
var disqus_container_id = 'disqus_thread';
var disqus_shortname = 'tegdesign';
var disqus_title = "Improving the Recurring Profiles Grid in Magento";
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
        script.src = '?cf_action=sync_comments&post_id=813';

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