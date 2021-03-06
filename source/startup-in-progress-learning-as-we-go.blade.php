<!--
{{ $page_title = 'Startup in progress. Learning as we go.' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'Startup in progress. Learning as we go.', 'sub_txt' => 'Posted on June 6, 2012 at 12:57 am'])



<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">


<p><a href="/img/fwc.jpg"><img class="alignleft size-full wp-image-247" title="fwc" alt="" src="/img/fwc.jpg" width="403" height="403" /></a>In late February I began working on a product that quickly became my obsession called <a href="http://www.etpio.com">Footwork</a>. At it&#8217;s core a straight forward idea &#8211; a system for canvassing door to door. A system that my partner, Phil Montag, and I thought was broken. What Phil and I set off to do was analyze the system and find ways to improve it.</p>
<p>When I started building Footwork I thought of it as a cool project to expand my skill set. Something I could learn from and share with people who felt frustrations in the system. It has been an amazing ride over the past few months with infrastructure decisions, UI challenges, late nights programming, beta testing&#8230; the works. But Footwork has evolved into a solid foundation for the problems my partner Phil Montag and I set out to solve.</p>
<h1>Todays problems</h1>
<p>Political campaigns spend countless hours knocking on doors to find your support. They spend time assigning address lists to canvassers, they spend time manually entering and collecting data, and they spend time managing canvassers to ensure they hit their targets. The canvassers, on the other hand, typically bring with them a list of addresses on a clipboard. From there they go door to door and ask people if they support the candidate. It is up to the canvasser to decide what house to visit by shuffling through stacks of paper.</p>
<p><a href="/assets/tesdf.png"><img class="alignright size-medium wp-image-251" title="tesdf" alt="" src="/img/tesdf-300x191.png" width="300" height="191" /></a></p>
<h1>How we can help</h1>
<p>Footwork aims to make things easier by allowing campaign staff and canvassers to do less work. Campaign staff import a list of address into Footwork and create a route plan for each canvasser. Canvassers then use their mobile device to visit doors and collect data. They should not have to worry about what house to visit next. They should not have to think more than a few seconds about what to do after visiting a door.</p>
<p>Campaign staff can track each canvasser from a web based console and see data collected in real time. This helps campaigns because they have visibility on whether or not their canvassers are visiting locations or just sitting at home faking it.</p>
<p><img class="alignleft size-medium wp-image-256" title="testdb" alt="" src="/img/testdb-300x191.png" width="300" height="191" /></p>
<p>When I was building Footwork I wanted to make sure it was device agnostic. It was built as a hybrid application so it can run on the multitude of devices in the market. This is an important differentiator to one of our main competitors. Footwork also stands apart from the crowd by including a new concept called <a href="http://www.etpio.com/facebook.php">Social Canvassing</a>. Okay I will be quite honest, I&#8217;m not sure it Social Canvassing is a term, but I claim it, if it isn&#8217;t.</p>
<p>Social Canvassing allows campaigns to bring awareness to their canvassing efforts on Facebook. Canvassers using Footwork can choose to enable our Facebook application. Once enabled their canvassing efforts will be broadcast into Facebook&#8217;s realtime ticker, similar to Spotify. When a Facebook friend clicks on the ticker item they are taken to a landing page for the candidate. When a canvasser finishes canvassing for the day they have the option to post a Nike Runkeeper-style map to Facebook&#8217;s timeline that shows the distance they covered. These techniques bring social awareness to canvassing, something that has a potential to double the impact a candidate has.</p>
<h1>Technology involved</h1>
<p>In an effort to build a product that could scale with minimal growing pains we have made a few development decisions early on that we have come to love and are continually looking at ways to build an efficient system for handling hundreds of millions of records.</p>
<h2><a href="/assets/specs.png"><img class="size-full wp-image-263 alignnone" title="specs" alt="" src="/img/specs.png" width="617" height="88" /></a></h2>
<h2>Mobile App</h2>
<p>As I said before the mobile application is built as a Hybrid app. We use HTML5/JQuery Mobile/Phonegap. This allows us to quickly create an application that works cross platform. I&#8217;ve been building for the mobile web for 2 years now. I started developing mobile web applications before tools like JQuery Mobile existed. It has begun to mature as a solid platform. Paired with Phonegap and a few performance tweaks for each device you are targeting you can squeeze a lot of it.</p>
<h2>Database</h2>
<p>Our database servers run MongoDB which has worked wonders for our application. MongoDB includes geospatial indexing, which is fantastic. The PHP drivers for Mongo have worked great so far and as we begin building process workers I see good potential for scalability.</p>
<h2>Servers</h2>
<p>Linux running Apache and PHP 5.3 servers at Mediatemple, but are evaluating the following PaaS providers: PHPFog, Orchestra, and looking at Red Hat&#8217;s Openshift offering (pending pricing). I appreciate anyone&#8217;s input on their experiences scaling infrastructure in the cloud. Particularly if you have done so using MongoDB with replication and sharding. Making sound infrastructure choices is important to me.</p>
<hr />
<h1>What&#8217;s next</h1>
<p>Learning, lots of learning. The best way to build is to learn everything you can about a process. I&#8217;m dead set on learning all that I can about improving our product and changing the game of political canvassing. There is still work to be done, clients to land, improvements to be made, but I&#8217;ve never been happier about something I&#8217;ve helped to create in my entire life. I feel like I know the feeling that entrepreneurs get when they are on the verge of something great.</p>
<p>Product Site: <a href="http://www.gofootwork.com">www.gofootwork.com</a> [Footwork]<br />
Silicon Prairie News &#8211; <a href="http://www.siliconprairienews.com/2012/05/footwork-app-aims-to-reduce-legwork-in-the-political-canvassing-process">Footwork app aims to reduce legwork in the political canvassing process</a></p>

	    
<div id="disqus_thread">
    </div>

<script>
var disqus_url = 'http://www.tegdesign.com/startup-in-progress-learning-as-we-go/';
var disqus_identifier = '242 http://www.tegdesign.com/?p=242';
var disqus_container_id = 'disqus_thread';
var disqus_shortname = 'tegdesign';
var disqus_title = "Startup in progress. Learning as we go.";
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
        script.src = '?cf_action=sync_comments&post_id=242';

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