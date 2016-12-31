<!--
{{ $page_title = 'Super Fast Magento: Lessons learned implementing Head.js and Varnish' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'Super Fast Magento: Lessons learned implementing Head.js and Varnish', 'sub_txt' => 'Posted on August 31, 2013 at 6:44 pm'])



<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">


<p>Over the course of the past few months I&#8217;ve been on journey to score good numbers on Google&#8217;s <a href="https://developers.google.com/speed/pagespeed/">PageSpeed tool</a>. Now out of the box Magento doesn&#8217;t really conform to Google&#8217;s standards at all. Extensions you add to your store that includes it&#8217;s own CSS and JS files. These files result in separate requests and slow down the page load. I needed a way to make <a href="http://www.BuluBox.com">BuluBox.com</a> load fast. We where already distributing the load across multiple EC2 instances under a Elastic Load Balancer, each running it&#8217;s own copy of Varnish paired with the amazing <a href="https://github.com/nexcess/magento-turpentine">Nexcess Turpentine extension</a>. With some proper tweaking to account for the ability to perform individual Varnish flushes across multiple servers (<a href="https://github.com/tegansnyder/magento-turpentine">fork here</a>) we now have a pretty fast Magento store. The only thing left to do is improve the page loading experience.</p>
<p>To get started I only wanted to make these changes on our homepage for the time being. This is the most visited page, and it by default doesn&#8217;t need alot of the other javascript that downstream pages in Magento need. Magento allows you to load a separate head.phtml file for you homepage by modifiying your local.xml file like so:</p>
<p>app/design/frontend/mytheme/theme/layout/local.xml</p>


<pre><code>
&lt;!--?xml version=&quot;1.0&quot; encoding=&quot;UTF-8&quot;?--&gt;

	&lt;cms_index_index&gt;
		page/html/headhomepage.phtml

		&lt;!-- rest of your local.xml --&gt;

</code></pre>


<p>Using this method you can taylor your homepage to load Javascript asynchronously using <a href="http://headjs.com/">Head.JS</a>. Paired with Head.JS I built a build script for Magento. While it contains many files specific to my theme the concept can work for your store.</p>


<pre><code class="bash">
echo &quot;* * * * * * * * * * * * * * * * * * * * *&quot;
echo &quot; Build Script v0.1&quot;
echo &quot; by Tegan Snyder&quot;
echo &quot;* * * * * * * * * * * * * * * * * * * * *&quot;
echo &quot;&quot;

echo &quot;combining css to mysite.css&quot;
cat /store/skin/frontend/mytheme/theme/css/styles.css /store/skin/frontend/base/default/css/widgets.css /store/skin/frontend/mytheme/theme/css/skin.css /store/assets/css/jquery.modal.min.css &gt; /store/css/mysite.css

echo &quot;minifying combining css to mysite.min.css&quot;
java -jar /build/yuicompressor-2.4.7.jar /store/css/mysite.css -o /store/css/mysite.min.css

echo &quot;combining magento js to scriptaculous-varien-magento.js&quot;
cat /store/js/scriptaculous/builder.js /store/js/scriptaculous/effects.js /store/js/scriptaculous/dragdrop.js /store/js/scriptaculous/controls.js /store/js/scriptaculous/slider.js /store/js/varien/js.js /store/js/varien/form.js /store/js/mage/translate.js /store/js/mage/cookies.js | uglifyjs -o /store/js/scriptaculous-varien-magento.js

#echo &quot;minifying any other random js files&quot;

echo 'minifying prototype files'
java -jar /build/yuicompressor-2.4.7.jar /store/js/prototype/prototype.js -o /store/js/prototype/prototype.min.js
java -jar /build/yuicompressor-2.4.7.jar /store/js/prototype/validation.js -o /store/js/prototype/validation.min.js

echo &quot;done&quot;
</code></pre>


<p>So whats the result?</p>
<p><img alt="" src="/img/resp.png" /></p>
<p>Some notes on the implementation:<br />
I recently moved from a Memcache Cluster (3 Nodes ElastiCache on AWS) to Redis. I&#8217;ve seen very significant improvements using Redis. Amazon recently announced Redis on ElastiCache. I&#8217;m pleased to announce that after implementing two ElastiCache instances: one for Cache, and one for session storage I have seen big gains. If you are adventurous and seeking similar performance gains take a look at Colin Mollenhour&#8217;s extensions:</p>
<h1 itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><strong><a href="https://github.com/colinmollenhour/Cm_Cache_Backend_Redis">Cm_Cache_Backend_Redis<br />
</a></strong></h1>
<h1 itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><strong><a href="https://github.com/colinmollenhour/Cm_RedisSession">Cm_RedisSession</a></strong></h1>
<h1 itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><strong><a href="https://github.com/colinmollenhour/Cm_Cache_Backend_Redis"> </a></strong></h1>

	    
<div id="disqus_thread">
    </div>

<script>
var disqus_url = 'http://www.tegdesign.com/super-fast-magento-lessons-learned-implementing-head-js-and-varnish/';
var disqus_identifier = '968 http://www.tegdesign.com/?p=968';
var disqus_container_id = 'disqus_thread';
var disqus_shortname = 'tegdesign';
var disqus_title = "Super Fast Magento: Lessons learned implementing Head.js and Varnish";
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
        script.src = '?cf_action=sync_comments&post_id=968';

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