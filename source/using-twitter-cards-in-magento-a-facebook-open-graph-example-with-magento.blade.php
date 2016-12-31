<!--
{{ $page_title = 'Using Twitter Cards in Magento + A Facebook Open Graph Example with Magento' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'Using Twitter Cards in Magento + A Facebook Open Graph Example with Magento', 'sub_txt' => 'Posted on September 16, 2013 at 8:16 pm'])



<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">


<p>I recently came across <a href="http://blog.virali.ca/2013/09/twitter-product-cards/" target="_blank">this article</a> on HackerNews: <a href="http://blog.virali.ca/2013/09/twitter-product-cards/" target="_blank">How to Add Twitter’s New Product Cards to your Shopify Store?</a> I decided to take the same approach <a href="https://twitter.com/byViralica" target="_blank">they</a> took but instead of using Shopify why not try it on Magento&#8230; it is of course my e-commerce platform of choice <img src="http://www.tegdesign.com/wp-includes/images/smilies/icon_smile.gif" alt=":)" class="wp-smiley" /></p>
<p>Here is a snippet from my head.phtml file. You can find your head.phtml in your theme&#8217;s folder structure. Mine was in the following location:</p>
<p>app/design/frontend/mytheme/template/page/html/head.phtml</p>


<pre><code class="php">
&lt;meta http-equiv=&quot;Content-Type&quot; content=&quot;&lt;?php echo $this-&gt;getContentType() ?&gt;&quot; /&gt;
&lt;!--?php echo $this---&gt;getTitle() ?&gt;
&lt;meta http-equiv=&quot;X-UA-Compatible&quot; content=&quot;IE=edge,chrome=1&quot; /&gt;
&lt;!--?php  if (Mage::registry('current_product')) { 	$current_product = Mage::registry('current_product'); 	$desc = preg_replace('/\s+?(\S+)?$/', '', substr(strip_tags(str_replace(array(&quot;\r\n&quot;, &quot;\r&quot;, &quot;\n&quot;), &quot;&quot;, $this---&gt;getDescription())), 0, 150));
	$desc = str_replace(&quot;’&quot;,&quot;'&quot;, $desc);
	$desc = str_replace(&quot; &quot;,&quot; &quot;, $desc);
?&gt;
&lt;meta name=&quot;description&quot; content=&quot;&lt;?php echo $desc; ?&gt;&quot; /&gt;
&lt;!--?php } else { ?--&gt;
&lt;meta name=&quot;keywords&quot; content=&quot;&lt;?php echo htmlspecialchars($this-&gt;getKeywords()) ?&gt;&quot; /&gt;
&lt;!--?php } ?--&gt;
&lt;meta name=&quot;robots&quot; content=&quot;&lt;?php echo htmlspecialchars($this-&gt;getRobots()) ?&gt;&quot; /&gt;
	&lt;link href=&quot;&lt;?php echo $this-&gt;getFaviconFile(); ?&gt;&quot; rel=&quot;icon&quot; type=&quot;image/x-icon&quot; /&gt;
	&lt;link href=&quot;&lt;?php echo $this-&gt;getFaviconFile(); ?&gt;&quot; rel=&quot;shortcut icon&quot; type=&quot;image/x-icon&quot; /&gt;
&lt;meta content=&quot;website&quot; /&gt;
&lt;!--?php  if (Mage::registry('current_product')) {  $product_image = Mage::getModel('catalog/product_media_config')---&gt;getMediaUrl($current_product-&gt;getSmallImage());
?&gt;
&lt;meta content=&quot;&amp;quot;&lt;?php&quot; /&gt;&quot;/&gt;
&lt;meta content=&quot;&lt;?php echo $product_image;?&gt;&quot; /&gt;
&lt;meta content=&quot;&lt;?php echo $current_product-&gt;getName();?&gt;&quot; /&gt;
&lt;meta content=&quot;&amp;quot;&lt;?php&quot; /&gt;getStore()-&gt;getCurrentUrl(false); ?&gt;&quot;/&gt;
&lt;meta name=&quot;twitter:site&quot; value=&quot;@YourTwitterHandle&quot;&gt;
&lt;meta name=&quot;twitter:card&quot; content=&quot;product&quot; /&gt;
&lt;meta name=&quot;twitter:title&quot; content=&quot;&lt;?php echo $current_product-&gt;getName();?&gt;&quot; /&gt;
&lt;meta name=&quot;twitter:description&quot; content=&quot;&lt;?php echo $desc;?&gt;&quot; /&gt;
&lt;meta name=&quot;twitter:image&quot; content=&quot;&lt;?php echo $product_image;?&gt;&quot; /&gt;
&lt;meta name=&quot;twitter:data1&quot; content=&quot;$&lt;?php echo number_format($current_product-&gt;getPrice(),2);?&gt;&quot; /&gt;
&lt;meta name=&quot;twitter:label1&quot; content=&quot;PRICE&quot; /&gt;
&lt;!--?php if($current_product---&gt;getStockItem()-&gt;getIsInStock()) { ?&gt;
&lt;meta name=&quot;twitter:data2&quot; content=&quot;In stock!&quot; /&gt;
&lt;!--?php } else { ?--&gt;
&lt;meta name=&quot;twitter:data2&quot; content=&quot;Out of stock&quot; /&gt;
&lt;!--?php } ?--&gt;
&lt;meta name=&quot;twitter:label2&quot; content=&quot;AVAILABILITY&quot; /&gt;
&lt;!--?php } else { // regular CMS page... not a product ?--&gt;
&lt;meta content=&quot;Your website description for facebook&quot; /&gt;
&lt;meta content=&quot;My Company Name Store&quot; /&gt;
&lt;meta content=&quot;http://www.domain.com/facebook-image.png&quot; /&gt;
&lt;meta content=&quot;http://www.domain.com/&quot; /&gt;
&lt;!--?php } ?--&gt;
&lt;meta content=&quot;en_US&quot; /&gt;
&lt;meta content=&quot;My Company Name Store&quot; /&gt;
</code></pre>



	    
<div id="disqus_thread">
            <div id="dsq-content">


            <ul id="dsq-comments">
                    <li class="comment even thread-even depth-1" id="dsq-comment-5667">
        <div id="dsq-comment-header-5667" class="dsq-comment-header">
            <cite id="dsq-cite-5667">
                <span id="dsq-author-user-5667">Digital Development</span>
            </cite>
        </div>
        <div id="dsq-comment-body-5667" class="dsq-comment-body">
            <div id="dsq-comment-message-5667" class="dsq-comment-message"><p>Made my site just turn white. Here&#8217;s a copy of my head text.</p>
<p>?&gt;<br />
&lt;meta http-equiv=&quot;Content-Type&quot; content=&quot;getContentType() ?&gt;&#8221; /&gt;<br />
getTitle() ?&gt;<br />
&lt;meta name=&quot;description&quot; content=&quot;getDescription()) ?&gt;&#8221; /&gt;<br />
&lt;meta name=&quot;keywords&quot; content=&quot;getKeywords()) ?&gt;&#8221; /&gt;<br />
&lt;meta name=&quot;robots&quot; content=&quot;getRobots()) ?&gt;&#8221; /&gt;</p>
<p>&lt;link rel=&quot;icon&quot; href=&quot;getFaviconFile(); ?&gt;&#8221; type=&#8221;image/x-icon&#8221; /&gt;<br />
&lt;link rel=&quot;shortcut icon&quot; href=&quot;getFaviconFile(); ?&gt;&#8221; type=&#8221;image/x-icon&#8221; /&gt;</p>
<p>&lt;link media=&quot;all&quot; href=&quot;getSkinUrl()?&gt;css/styles.css3.php?url=getSkinUrl()?&gt;&#8221; type=&#8221;text/css&#8221; rel=&#8221;stylesheet&#8221; /&gt;</p>
<p><!--[if lt IE 7]&gt;--></p>
<p>//&lt;![CDATA[<br />
    var BLANK_URL = &#039;helper('core/js')-&gt;getJsUrl('blank.html') ?&gt;';<br />
    var BLANK_IMG = 'helper('core/js')-&gt;getJsUrl('spacer.gif') ?&gt;';<br />
//]]&gt;</p>
<p>getCssJsHtml() ?&gt;<br />
getChildHtml() ?&gt;<br />
helper(&#8216;core/js&#8217;)-&gt;getTranslatorScript() ?&gt;<br />
getIncludes() ?&gt;</p>
</div>
        </div>

    </li><!-- #comment-## -->
    <li class="comment odd alt thread-odd thread-alt depth-1" id="dsq-comment-5670">
        <div id="dsq-comment-header-5670" class="dsq-comment-header">
            <cite id="dsq-cite-5670">
                <span id="dsq-author-user-5670">Lenz Nice</span>
            </cite>
        </div>
        <div id="dsq-comment-body-5670" class="dsq-comment-body">
            <div id="dsq-comment-message-5670" class="dsq-comment-message"><p>Good approach but then you have to use the same descriptions and titles you use in your shop.<br />
But there is an extension that will do all the stuff for you and gives you the opportunity to provide specific text and images for your social shares with several fallbacks to the normal information: <a href="http://www.magentocommerce.com/magento-connect/social-meta-tags-for-open-graph-and-twitter-cards.html" rel="nofollow">http://www.magentocommerce.com/magento-connect/social-meta-tags-for-open-graph-and-twitter-cards.html</a><br />
It also supports most of the different Twitter Card Types!</p>
</div>
        </div>

    </li><!-- #comment-## -->
            </ul>


        </div>

    </div>

<script>
var disqus_url = 'http://www.tegdesign.com/using-twitter-cards-in-magento-a-facebook-open-graph-example-with-magento/';
var disqus_identifier = '978 http://www.tegdesign.com/?p=978';
var disqus_container_id = 'disqus_thread';
var disqus_shortname = 'tegdesign';
var disqus_title = "Using Twitter Cards in Magento + A Facebook Open Graph Example with Magento";
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
        script.src = '?cf_action=sync_comments&post_id=978';

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