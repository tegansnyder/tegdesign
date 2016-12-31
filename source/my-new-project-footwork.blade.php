<!--
{{ $page_title = 'My new project Footwork!' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'My new project Footwork!', 'sub_txt' => 'Posted on April 16, 2012 at 11:08 pm'])



<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">


<p>I have been working on a interesting project for the past few months. Footwork! It&#8217;s a mobile application and backend management console that allows political campaigns to manage canvassing efforts over mobile phones/tablets. The backend is built using PHP/Node.js/MongoDB/Twitter Bootstrap and the mobile application is built using the latest JQuery Mobile 1.1 and Phonegap 1.6. We plan on hitting a wide spectrum of phones by making available the web app by a URL as well.</p>
<p>Footwork is has very intuitive user experience and is super extensible. It&#8217;s designed to work with your existing location data and integrates seamlessly via CSV file imports/exports.</p>
<p>Footwork also includes optional social integration. If a canvasser chooses to publish their canvassing actions on Facebook we have a custom Open Graph action that automatically posts to their timeline their location and who they are canvasing for. At the end of the day of canvasing we build out a map on their timeline simular to that of a Run keeper app.</p>
<p>Videos and more information available at <a href="http://www.etpio.com">Etelligence Pioneers website</a>.</p>
<figure id="attachment_218" class="thumbnail wp-caption alignnone" style="width: 1077px"><a href="/img/android_babby.png"><img class="size-full wp-image-218" title="android_babby" src="/img/android_babby.png" alt="" width="1077" height="1208" /></a><figcaption class="caption wp-caption-text">Android Market!</figcaption></figure>
<figure id="attachment_219" class="thumbnail wp-caption alignnone" style="width: 1067px"><a href="/img/appstore_babby.png"><img class="size-full wp-image-219" title="appstore_babby" src="/img/appstore_babby.png" alt="" width="1067" height="1625" /></a><figcaption class="caption wp-caption-text">Apple Store - Waiting for Review (Lets see how long it takes)</figcaption></figure>
<p>Videos and more information available at <a href="http://www.etpio.com">Etelligence Pioneers website</a>.</p>

	    
<div id="disqus_thread">
    </div>

<script>
var disqus_url = 'http://www.tegdesign.com/my-new-project-footwork/';
var disqus_identifier = '217 http://www.tegdesign.com/?p=217';
var disqus_container_id = 'disqus_thread';
var disqus_shortname = 'tegdesign';
var disqus_title = "My new project Footwork!";
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
        script.src = '?cf_action=sync_comments&post_id=217';

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