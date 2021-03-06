<!--
{{ $page_title = 'Converting SAP Xcelsius .SWF File to iOS and Android' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'Converting SAP Xcelsius .SWF File to iOS and Android', 'sub_txt' => 'Posted on June 10, 2011 at 2:37 pm'])



<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">


<p><strong>UPDATE:</strong> Adobe AIR 2.7 has been released with much improvement for IOS. Please read this <a href="http://blogs.adobe.com/flashplayer/2011/06/adobe-air-2-7-now-available-ios-apps-4x-faster.html">post</a>.</p>
<p>There are a lot of neat things going on with Adobe AIR. They recently released version 2.6 along with a new version of Flash. Flash CS5 had some early support for compiling iOS applications, but CS 5.5 adds support for Android apps as well.</p>
<p>This post assumes you have already created a developer account and both apple and android marketplace. If you need help creating the necessary files check out Adobes great documentation:<br />
<a href="http://help.adobe.com/en_US/as3/iphone/WS789ea67d3e73a8b2-240138de1243a7725e7-8000.html">Getting started building AIR applications for the iPhone</a></p>
<p>Note you will need to know the location of Air 2.6. If you have installed Flash CS 5.5 it is located here:<br />
C:\Program Files (x86)\Adobe\Adobe Flash CS5.5\AIR2.6\bin</p>
<p>You will also need to create a XML file that contains the most basic app settings. Here is my sample XML:</p>


<pre><code>
&lt;?xml version=&quot;1.0&quot; encoding=&quot;UTF-8&quot; standalone=&quot;no&quot; ?&gt;
&lt;application xmlns=&quot;http://ns.adobe.com/air/application/2.6&quot;&gt;

  &lt;id&gt;com.mycompany.mobile&lt;/id&gt;
  &lt;versionNumber&gt;1.0&lt;/versionNumber&gt;
  &lt;filename&gt;demo&lt;/filename&gt;
  &lt;description/&gt;
  &lt;copyright/&gt;

  &lt;initialWindow&gt;
    &lt;content&gt;demo.swf&lt;/content&gt;
    &lt;systemChrome&gt;standard&lt;/systemChrome&gt;
    &lt;transparent&gt;false&lt;/transparent&gt;
    &lt;visible&gt;true&lt;/visible&gt;
    &lt;fullScreen&gt;false&lt;/fullScreen&gt;
    &lt;renderMode&gt;auto&lt;/renderMode&gt;
    &lt;autoOrients&gt;false&lt;/autoOrients&gt;
    &lt;aspectRatio&gt;landscape&lt;/aspectRatio&gt;&lt;/initialWindow&gt;

  &lt;icon/&gt;

  &lt;customUpdateUI&gt;false&lt;/customUpdateUI&gt;

  &lt;allowBrowserInvocation&gt;false&lt;/allowBrowserInvocation&gt;

  &lt;iPhone&gt;
    &lt;InfoAdditions&gt;
      &lt;![CDATA[&lt;key&gt;UIDeviceFamily&lt;/key&gt;&lt;array&gt;&lt;string&gt;2&lt;/string&gt;&lt;/array&gt;]]&gt;
    &lt;/InfoAdditions&gt;
    &lt;requestedDisplayResolution&gt;standard&lt;/requestedDisplayResolution&gt;
  &lt;/iPhone&gt;

  &lt;android&gt;
    &lt;manifestAdditions&gt;

      &lt;![CDATA[&lt;manifest&gt;
&lt;uses-permission android:name=&quot;android.permission.INTERNET&quot;/&gt;
&lt;/manifest&gt;]]&gt;
    &lt;/manifestAdditions&gt;
  &lt;/android&gt;

&lt;/application&gt;
</code></pre>


<p>Make sure to change the name of the .swf file to match your Xcelsius swf file. Then save the XML file in the same directory as AIR. NOTE: You will also need to move your .swf file to the same directory as AIR. (C:\Program Files (x86)\Adobe\Adobe Flash CS5.5\AIR2.6\bin)</p>
<p>Next create some icons and place them in an icons folder. They need to be PNGs. I created some blank images and called them:<br />
Icon512.png (512&#215;512)<br />
Icon57.png (57&#215;57)<br />
Icon29.png (29&#215;29)<br />
And you also need a Default.png (320&#215;480) (the splash image that shows when app is loading)</p>
<p>Fire up command prompt from the AIR directory and run the following command to convert the swf to an iOS ipa file:</p>


<pre><code>
adt -package -target ipa-test -storetype pkcs12 -keystore iphone_dev.p12 -storepass yourcertpasswordhere -provisioning-profile COMPANY.mobileprovision demo.ipa demo.xml demo.swf icons/Icon512.png icons/Icon57.png icons/Icon29.png Default.png
</code></pre>


<p>You can install the ipa file by adding it to your iTunes library and syncing your device.</p>
<p>To create the android file:</p>


<pre><code>
adt -package -target apk-debug -storetype pkcs12 -keystore android.p12 -storepass YOURPASS demo.apk demo.xml demo.swf icons
</code></pre>


<p>To install the android file you can upload the apk file to your webserver and just browse to it on your device. Note you may need to allow unsigned apps to run from your settings.</p>
<p>Also I created the signed android.p12 file by using Flash CS5.5 and creating a blank Android project going to Publish Settings and using the wizard it provides.</p>
<p>This is a really rough explanation. Note I don&#8217;t think this will work with really advance Xcelsius files. I tested it on some simple files and it works fine, but is a little slow to start.</p>
<p>Related links:<br />
<a href="http://www.sdn.sap.com/irj/scn/weblogs?blog=/pub/wlg/20589%3Fgoback%3D%252Egde_1778623_member_34423075"> Xcelsius Running on Apple Devices<br />
</a><br />
<a href="http://forums.adobe.com/thread/862373?tstart=0">Air for IOS Including files in IPA (SWF)</a><br />
<a href="http://help.adobe.com/en_US/air/build/WSfffb011ac560372f-5d0f4f25128cc9cd0cb-7ffb.html">Packaging a mobile AIR application</a></p>
	    </div>
	    <footer>
	      	      <ul class="entry-tags"><li><a href="/tag/android/" rel="tag">Android</a></li><li><a href="/tag/ios/" rel="tag">iOS</a></li><li><a href="/tag/ipa/" rel="tag">ipa</a></li><li><a href="/tag/swf/" rel="tag">swf</a></li><li><a href="/tag/xcelsius/" rel="tag">Xcelsius</a></li></ul>	    </footer>
	    
<div id="disqus_thread">
            <div id="dsq-content">


            <ul id="dsq-comments">
                    <li class="comment even thread-even depth-1" id="dsq-comment-653">
        <div id="dsq-comment-header-653" class="dsq-comment-header">
            <cite id="dsq-cite-653">
                <span id="dsq-author-user-653">deepak</span>
            </cite>
        </div>
        <div id="dsq-comment-body-653" class="dsq-comment-body">
            <div id="dsq-comment-message-653" class="dsq-comment-message"><p>I have adt package in command prompt as instructed above but its giving me the error &#8220;output file is not writable&#8221;</p>
</div>
        </div>

    <ul class="children">
    <li class="comment byuser comment-author-tegan bypostauthor odd alt depth-2" id="dsq-comment-690">
        <div id="dsq-comment-header-690" class="dsq-comment-header">
            <cite id="dsq-cite-690">
                <span id="dsq-author-user-690">tegan</span>
            </cite>
        </div>
        <div id="dsq-comment-body-690" class="dsq-comment-body">
            <div id="dsq-comment-message-690" class="dsq-comment-message"><p>Make sure you have ran command prompt as administrator.</p>
</div>
        </div>

    </li><!-- #comment-## -->
</ul><!-- .children -->
</li><!-- #comment-## -->
            </ul>


        </div>

    </div>

<script>
var disqus_url = 'http://www.tegdesign.com/ios_android_xcelsius/';
var disqus_identifier = '106 http://www.tegdesign.com/?p=106';
var disqus_container_id = 'disqus_thread';
var disqus_shortname = 'tegdesign';
var disqus_title = "Converting SAP Xcelsius .SWF File to iOS and Android";
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
        script.src = '?cf_action=sync_comments&post_id=106';

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