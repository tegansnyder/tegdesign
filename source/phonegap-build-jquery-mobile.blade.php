<!--
{{ $page_title = 'PhoneGap Build + JQuery Mobile' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'PhoneGap Build + JQuery Mobile', 'sub_txt' => 'Posted on December 4, 2011 at 5:39 pm'])



<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">


<p>I recently used PhoneGap build to turn a JQuery Mobile application into a native app for both iOS and Android. My first impression seems to be a little disappointed in the responsiveness of the application compared to it&#8217;s web counterpart.</p>
<p>One of the things that may through off first time PhoneGap build users is their submission process. The currently only allow you to submit one icon for the iOS build while you need two. One for retina and one for non-retina. 57&#215;57 and 72&#215;72 respectively.</p>
<p>To solve that issue it is necessary to build a config.xml file. Here is an example:</p>


<pre><code>
&lt;widget xmlns	= &quot;http://www.w3.org/ns/widgets&quot;
	xmlns:gap	= &quot;http://phonegap.com/ns/1.0&quot;
	id		    = &quot;com.companyname.someappname&quot;
	version 	= &quot;1.0&quot;&gt;

	&lt;name&gt;Some App Name&lt;/name&gt;

	&lt;description&gt;
Some App Name description
	&lt;/description&gt;

	&lt;author href=&quot;http://www.someapp.com&quot;
		email=&quot;<a class="__cf_email__" href="/cdn-cgi/l/email-protection" data-cfemail="592c2a3c2b192a36343c382929773a3634">[email&nbsp;protected]</a><script type="text/javascript">
/* <![CDATA[ */
(function(){try{var s,a,i,j,r,c,l,b=document.getElementsByTagName("script");l=b[b.length-1].previousSibling;a=l.getAttribute('data-cfemail');if(a){s='';r=parseInt(a.substr(0,2),16);for(j=2;a.length-j;j+=2){c=parseInt(a.substr(j,2),16)^r;s+=String.fromCharCode(c);}s=document.createTextNode(s);l.parentNode.replaceChild(s,l);}}catch(e){}})();
/* ]]> */
</script>&quot;&gt;
		Some App Name Company
	&lt;/author&gt;

  &lt;icon src=&quot;icons/icon-iphone-72.png&quot; width=&quot;72&quot; height=&quot;72&quot; /&gt;
  &lt;icon src=&quot;icons/icon-iphone-57.png&quot; width=&quot;57&quot; height=&quot;57&quot; /&gt;

  &lt;gap:splash src=&quot;splashes/splash.png&quot; /&gt;
&lt;/widget&gt;
</code></pre>


<p>Simply archive this xml file along with your JQM files and upload it to PhoneGap build.<br />
Note: If you are having troubles uploading your application to the app store use an older version of Apple&#8217;s application loader.</p>

	    
<div id="disqus_thread">
    </div>

<script>
var disqus_url = 'http://www.tegdesign.com/phonegap-build-jquery-mobile/';
var disqus_identifier = '137 http://www.tegdesign.com/?p=137';
var disqus_container_id = 'disqus_thread';
var disqus_shortname = 'tegdesign';
var disqus_title = "PhoneGap Build + JQuery Mobile";
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
        script.src = '?cf_action=sync_comments&post_id=137';

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