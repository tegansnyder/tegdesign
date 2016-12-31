<!--
{{ $page_title = 'CNN iPhone app like Swipe-From-Top menu using Zepto.js and Phonegap' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'CNN iPhone app like Swipe-From-Top menu using Zepto.js and Phonegap', 'sub_txt' => 'Posted on December 24, 2012 at 7:45 pm'])



<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">


<p>This is an example prepared to show off a type of sliding drawer menu similar to CNN&#8217;s iPhone app, but using HTML5 Hybrid approach with Javascript, CSS3, and Zepto.js. Paired with PhoneGap its a great menu treatment technique.<a href="https://github.com/tegansnyder/CNN-Like-Swipe-From-Top-Menu-Zepto.js-Mobile-HTML5#readme"><br />
</a></p>
<div id="repository_homepage">
<div class="wp-caption"><img alt="" src="https://camo.githubusercontent.com/3124a279789e78a66c6f38a36771a04af7cf764f/687474703a2f2f7777772e74656764657369676e2e636f6d2f434e4e2d4c696b652d53776970652d46726f6d2d546f702d4d656e752d5a6570746f2e6a732d4d6f62696c652d48544d4c352f73637265656e732d746f6765746865722e6a7067" /></div>
<p>This is an example prepared to show off a type of sliding drawer menu similar to CNN&#8217;s iPhone app, but using HTML5 Hybrid approach with Javascript, CSS3, and Zepto.js. Paired with PhoneGap its a great menu treatment technique.</p>
<p>DEMO: <a href="http://www.tegdesign.com/CNN-Like-Swipe-From-Top-Menu-Zepto.js-Mobile-HTML5">http://www.tegdesign.com/CNN-Like-Swipe-From-Top-Menu-Zepto.js-Mobile-HTML5</a></p>
<p>Note: A real demo of this requires compiling this to a Hybrid app using PhoneGap Build with UIWebView bounce turned off. I have included a config.xml starter file for these purpose.</p>
<p>Install it on iOS from Phonegap Build by scanning this QR: <a href="https://a248.e.akamai.net/camo.github.com/5966aabf05df8f25ed9d68ab0c367e2203401391/687474703a2f2f7777772e74656764657369676e2e636f6d2f434e4e2d4c696b652d53776970652d46726f6d2d546f702d4d656e752d5a6570746f2e6a732d4d6f62696c652d48544d4c352f71722e706e67" target="_blank"><img title="Install" alt="Alt text" src="https://camo.githubusercontent.com/5966aabf05df8f25ed9d68ab0c367e2203401391/687474703a2f2f7777772e74656764657369676e2e636f6d2f434e4e2d4c696b652d53776970652d46726f6d2d546f702d4d656e752d5a6570746f2e6a732d4d6f62696c652d48544d4c352f71722e706e67" /></a></p>
<p><a class="btn btn-primary" href="https://github.com/tegansnyder/CNN-Like-Swipe-From-Top-Menu-Zepto.js-Mobile-HTML5#readme">Download the source code at Github</a></p>
</div>
	    </div>
	    <footer>
	      	      <ul class="entry-tags"><li><a href="/tag/zepto-js/" rel="tag">Zepto.js</a></li></ul>	    </footer>
	    
<div id="disqus_thread">
            <div id="dsq-content">


            <ul id="dsq-comments">
                    <li class="comment even thread-even depth-1" id="dsq-comment-2044">
        <div id="dsq-comment-header-2044" class="dsq-comment-header">
            <cite id="dsq-cite-2044">
                <span id="dsq-author-user-2044">Jaganlal</span>
            </cite>
        </div>
        <div id="dsq-comment-body-2044" class="dsq-comment-body">
            <div id="dsq-comment-message-2044" class="dsq-comment-message"><p>Thanks a lot for the source. Any idea, did CNN app also use same HTML5 approach or its native?</p>
</div>
        </div>

    <ul class="children">
    <li class="comment byuser comment-author-tegan bypostauthor odd alt depth-2" id="dsq-comment-2045">
        <div id="dsq-comment-header-2045" class="dsq-comment-header">
            <cite id="dsq-cite-2045">
                <span id="dsq-author-user-2045">tegan</span>
            </cite>
        </div>
        <div id="dsq-comment-body-2045" class="dsq-comment-body">
            <div id="dsq-comment-message-2045" class="dsq-comment-message"><p>Glad you like it. My bets on them using a native approach.</p>
</div>
        </div>

    </li><!-- #comment-## -->
</ul><!-- .children -->
</li><!-- #comment-## -->
            </ul>


        </div>

    </div>

<script>
var disqus_url = 'http://www.tegdesign.com/cnn-iphone-app-like-swipe-from-top-menu-using-zepto-js-and-phonegap/';
var disqus_identifier = '835 http://www.tegdesign.com/?p=835';
var disqus_container_id = 'disqus_thread';
var disqus_shortname = 'tegdesign';
var disqus_title = "CNN iPhone app like Swipe-From-Top menu using Zepto.js and Phonegap";
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
        script.src = '?cf_action=sync_comments&post_id=835';

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