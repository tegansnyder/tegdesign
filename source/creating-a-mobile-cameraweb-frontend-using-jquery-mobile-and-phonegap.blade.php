<!--
{{ $page_title = 'Creating a Mobile Camera/Web Frontend using JQuery Mobile and PhoneGap' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'Creating a Mobile Camera/Web Frontend using JQuery Mobile and PhoneGap', 'sub_txt' => 'Posted on December 22, 2011 at 4:19 pm'])



<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">


<p>I&#8217;m really starting to enjoy the most recent JQuery Mobile release paired with PhoneGap. It really allows a developer to build a hybrid mobile application relatively quickly. I recently started a project to develop a mobile app that takes pictures and uploads them to a web server (LAMP stack).</p>
<p>Here is a short explanation how to get a quick app rolling.</p>
<ul>
<li>Download <a href="http://phonegap.com/">PhoneGap</a> (if you haven&#8217;t already)</li>
<li>Download <a href="http://jquerymobile.com/download/">JQuery Mobile</a> (the ZIP file &#8211; scroll down the download page a bit)</li>
<li>Download <a href="http://code.jquery.com/jquery-1.6.4.min.js">JQuery framework</a></li>
<li>Download James Padolsey&#8217;s Cross Domain Ajax Plugin -<a href="https://github.com/padolsey/jQuery-Plugins/blob/master/cross-domain-ajax/jquery.xdomainajax.js"> jquery.xdomainajax.js</a></li>
<li>Unzip both files and create a new folder for your project. (mine is called &#8220;myCameraApp&#8221;)</li>
</ul>
<p><a href="/img/Screen-Shot-2011-12-21-at-6.39.25-PM.png"><img class="size-medium wp-image-171" title="Screen Shot 2011-12-21 at 6.39.25 PM" src="/img/Screen-Shot-2011-12-21-at-6.39.25-PM-300x209.png" alt="" width="300" height="209" /></a></p>
<div><strong>Step 2:</strong></div>
<div>
<ul>
<li>The PhoneGap zip file contains a folder called &#8220;iOS&#8221;.</li>
<li>Open that folder and install the DMG file (PhoneGap-1.3.0.dmg)</li>
<li>After installing PhoneGap open up XCode &#8211; (Note if you don&#8217;t have a MAC you can still follow these instructions using a good text editor like Notepad++ and compile your app using PhoneGap Build.)</li>
</ul>
<div>Follow the steps from PhoneGaps Documentation: <a href="http://phonegap.com/start#ios-x4">http://phonegap.com/start#ios-x4</a></div>
<div><a href="/assets/Screen-Shot-2011-12-21-at-6.41.48-PM.png"><img class="size-full wp-image-172 alignnone" title="Screen Shot 2011-12-21 at 6.41.48 PM" src="/img/Screen-Shot-2011-12-21-at-6.41.48-PM.png" alt="" width="730" height="489" /></a></div>
<div><a href="/assets/Screen-Shot-2011-12-21-at-6.42.25-PM.png"><img class="size-full wp-image-173 alignnone" title="Screen Shot 2011-12-21 at 6.42.25 PM" src="/img/Screen-Shot-2011-12-21-at-6.42.25-PM.png" alt="" width="729" height="494" /></a></div>
<div><a href="/img/Screen-Shot-2011-12-21-at-6.43.27-PM.png"><img class="size-full wp-image-174 alignnone" title="Screen Shot 2011-12-21 at 6.43.27 PM" src="/img/Screen-Shot-2011-12-21-at-6.43.27-PM.png" alt="" width="586" height="617" /></a></div>
</div>
<div></p>
<ul>
<li>Choose a directory to store your app</li>
<li>You should see your project in Xcode 4 now.</li>
<li>Now it&#8217;s time to run your application. Click the run icon in top left corner.</li>
</ul>
<p>
<a href="/img/Screen-Shot-2011-12-21-at-6.50.19-PM.png"><img class="size-full wp-image-175 alignnone" title="Screen Shot 2011-12-21 at 6.50.19 PM" src="/img/Screen-Shot-2011-12-21-at-6.50.19-PM.png" alt="" width="427" height="82" /></a></p>
<ul>
<li>The first time you run your application you <strong>will get an error</strong>. Don&#8217;t fret this is normal and its required to go through this stage.</li>
</ul>
<p></p>
<div><a href="/img/Screen-Shot-2011-12-21-at-6.52.42-PM.png"><img class="size-full wp-image-176 alignnone" title="Screen Shot 2011-12-21 at 6.52.42 PM" src="/img/Screen-Shot-2011-12-21-at-6.52.42-PM.png" alt="" width="382" height="719" /></a></div>
<div></p>
<ul>
<li>To fix this, we need to copy the <strong>www</strong> directory into the project. Right click on the project in the left navigation window and click show in finder.</li>
</ul>
<p></p>
<div><a href="/img/Screen-Shot-2011-12-21-at-6.54.54-PM.png"><img class="size-full wp-image-177 alignnone" title="Screen Shot 2011-12-21 at 6.54.54 PM" src="/img/Screen-Shot-2011-12-21-at-6.54.54-PM.png" alt="" width="412" height="79" /></a></div>
</div>
</div>
<div></p>
<ul>
<li>Finder will open to the project folder. You should see a &#8220;www&#8221; folder along with your project files.</li>
</ul>
</div>
<p></p>
<div><img class="size-full wp-image-179 alignnone" title="up" src="/img/up.png" alt="" width="838" height="433" /></div>
<div></p>
<ul>
<li>Drag the &#8220;www&#8221; folder into your Xcode project and drop it on top of the &#8220;myCameraApp&#8221; heading.</li>
</ul>
<p></p>
<div><a href="/img/Screen-Shot-2011-12-21-at-6.58.09-PM.png"><img class="alignnone size-full wp-image-180" title="Screen Shot 2011-12-21 at 6.58.09 PM" src="/img/Screen-Shot-2011-12-21-at-6.58.09-PM.png" alt="" width="732" height="498" /></a></div>
<div></p>
<ul>
<li>After you do the drag operation a new dialog will appear.</li>
<li>Make sure to select &#8220;Create folder references for any added folders&#8221; and the top checkbox to copy the files into the destination group.</li>
</ul>
<p></p>
<div><a href="/img/Screen-Shot-2011-12-21-at-7.03.32-PM.png"><img class="alignnone size-full wp-image-181" title="Screen Shot 2011-12-21 at 7.03.32 PM" src="/img/Screen-Shot-2011-12-21-at-7.03.32-PM.png" alt="" width="711" height="427" /></a></div>
<div></p>
<ul>
<li>You can now expand the &#8220;www&#8221; folder in Xcode. We are ready to start putting JQuery Mobile code in.</li>
</ul>
<p></p>
<div>
<ul>
<li>Go back to the JQuery Mobile Zip file you extracted and copy the following files into your &#8220;www&#8221; folder.</li>
</ul>
</div>
</div>
<p></p>
<div><a href="/img/co.png"><img class="alignnone size-full wp-image-184" title="co" src="/img/co.png" alt="" width="858" height="405" /></a></div>
<div></p>
<ul>
<li>Note: I forgot to include the <a href="http://code.jquery.com/jquery-1.6.4.min.js">JQuery Framework</a>. You will also need to download it and copy it to the www folder.</li>
<li>Note: I also forgot to include James Padolsey&#8217;s cross-domain-ajax script. Download <a href="https://github.com/padolsey/jQuery-Plugins/blob/master/cross-domain-ajax/jquery.xdomainajax.js">jquery.xdomainajax.js</a> from his Github and copy it to your &#8220;www&#8221; folder.</li>
<li>So these are the files you should have in your &#8220;www&#8221; folder:</li>
</ul>
<p></p>
<div><a href="/img/Screen-Shot-2011-12-21-at-7.21.58-PM.png"><img class="alignnone size-full wp-image-185" title="Screen Shot 2011-12-21 at 7.21.58 PM" src="/img/Screen-Shot-2011-12-21-at-7.21.58-PM.png" alt="" width="448" height="237" /></a></div>
</div>
</div>
</div>
<p></p>
<div>
<ul>
<li>Open up Xcode and replace everything in the &#8220;index.html&#8221; with the code below.</li>
</ul>


<pre><code>



        myCameraApp



&lt;script charset=&quot;utf-8&quot; type=&quot;text/javascript&quot; src=&quot;phonegap-1.2.0.js&quot;&gt;&lt;/script&gt;&lt;script type=&quot;text/javascript&quot; src=&quot;jquery-1.6.4.min.js&quot;&gt;&lt;/script&gt;
&lt;script type=&quot;text/javascript&quot; src=&quot;jquery.mobile-1.0.min.js&quot;&gt;&lt;/script&gt;&lt;script type=&quot;text/javascript&quot; src=&quot;jquery.xdomainajax.js&quot;&gt;&lt;/script&gt;
</code></pre>




<pre><code class="javascript">
&lt;script type=&quot;text/javascript&quot;&gt;// &lt;![CDATA[

                    $(function() {

                      $.ajaxSetup ({
                                   cache: false
                                   });

                      $.support.cors = true;

                      });

                    $(document).bind('mobileinit', function() {

                                     $.mobile.allowCrossDomainPages = true;

                                     });

                    $('#main').live('pageinit', function(event) {

                                    $('#browse_photo').click(function() {

                                                             navigator.camera.getPicture(uploadPhoto, function(message) {
                                                                                         alert('get picture failed');
                                                                                         },{ quality: 50, destinationType: navigator.camera.DestinationType.FILE_URI, sourceType: navigator.camera.PictureSourceType.PHOTOLIBRARY });

                                                             });

                                    $('#take_photo').click(function() {

                                                           navigator.device.capture.captureImage(captureSuccess, captureError, {limit: 1});

                                                           });

                                    });

                    $('#view').live('pageinit', function(event) {

                                    $.get('http://www.domain.com/view.php', function (data) {

                                          $('#photos').html(data.responseText);

                                          });

                                    });

                    function captureError(error) {

                        $('#loading').hide();

                        var msg = 'An error occurred during capture: ' + error.code;
                        navigator.notification.alert(msg, null, 'Uh oh!');
                    }

                    function captureSuccess(mediaFiles) {

                        var i, len;
                        for (i = 0, len = mediaFiles.length; i &lt; len; i += 1) {
                            uploadFile(mediaFiles[i]);
                        }

                        $('#loading').show();

                    }

                    function uploadFile(mediaFile) {
                        var ft = new FileTransfer(),
                        path = mediaFile.fullPath,
                        name = mediaFile.name;

                        ft.upload(path, &quot;http://www.domain.com/upload.php&quot;, function(result) {

                                  //var msg = result.bytesSent + ' bytes sent';
                                  //navigator.notification.alert(msg, null, 'Upload success');

                                  sessionStorage.setItem('filename', result.response);
                                  findLocation();

                                  $('#loading').hide();

                                  }, function(error) {

                                  $('#loading').hide();

                                  var msg = 'Error uploading file ' + path + ': ' + error.code;
                                  navigator.notification.alert(msg, null, 'Error');

                                  },{ fileName: name, fileKey: 'file' });   
                    }

                    function uploadPhoto(imageURI) {

                        $('#loading').show();

                        var options = new FileUploadOptions();
                        options.fileKey=&quot;file&quot;;
                        options.fileName=imageURI.substr(imageURI.lastIndexOf('/')+1);
                        options.mimeType=&quot;image/jpeg&quot;;

                        var params = new Object();
                        params.value1 = &quot;test&quot;;
                        params.value2 = &quot;param&quot;;

                        options.params = params;

                        var ft = new FileTransfer();
                        ft.upload(imageURI, &quot;http://www.domain.com/upload.php&quot;, win, fail, options);

                    }

                    function win(result) {

                        $('#loading').hide();

                        //var msg = result.bytesSent + ' bytes sent';
                        //navigator.notification.alert(msg, null, 'Upload success');

                        sessionStorage.setItem('filename', result.response);

                        findLocation();

                    }

                    function fail(error) {

                        $('#loading').hide();

                        var msg = 'An error has occurred: Code = ' + error.code;
                        navigator.notification.alert(msg, null, 'Error');

                    }

// ]]&gt;&lt;/script&gt;
</code></pre>




<pre><code>
&lt;/pre&gt;
&lt;div id=&quot;main&quot; data-role=&quot;page&quot;&gt;
&lt;div data-role=&quot;content&quot;&gt;
Choose an option below&lt;/div&gt;
&lt;div data-role=&quot;footer&quot; data-position=&quot;fixed&quot; data-theme=&quot;e&quot;&gt;
&lt;div data-role=&quot;navbar&quot; data-iconpos=&quot;bottom&quot;&gt;
&lt;ul&gt;
	&lt;li&gt;&lt;a id=&quot;take_photo&quot; href=&quot;#&quot; data-icon=&quot;grid&quot;&gt;Take Photo&lt;/a&gt;&lt;/li&gt;
	&lt;li&gt;&lt;a id=&quot;browse_photo&quot; href=&quot;#&quot; data-icon=&quot;star&quot;&gt;Browse Photo&lt;/a&gt;&lt;/li&gt;
	&lt;li&gt;&lt;a href=&quot;view.html&quot; data-icon=&quot;gear&quot;&gt;View&lt;/a&gt;&lt;/li&gt;
&lt;/ul&gt;
&lt;/div&gt;
&lt;/div&gt;
&lt;/div&gt;
&lt;pre&gt;


</code></pre>


<ul>
<li>Now run your app in the simulator.</li>
</ul>
<p><a href="/img/Screen-Shot-2011-12-21-at-7.35.03-PM.png"><img class="alignnone size-full wp-image-188" title="Screen Shot 2011-12-21 at 7.35.03 PM" src="/img/Screen-Shot-2011-12-21-at-7.35.03-PM.png" alt="" width="338" height="624" /></a></p>
</div>
<ul>
<li>Now you app really doesn&#8217;t do anything yet because we haven&#8217;t created the PHP script necessary to upload the files.</li>
<li>Here is the &#8220;upload.php&#8221; file.</li>
</ul>


<pre><code class="php">
&lt;?php	
if (isset($_FILES['file'])) {

	$filename = md5(date('Y-m-d H:i:s:u')) . $_FILES[&quot;file&quot;][&quot;name&quot;];
	move_uploaded_file($_FILES[&quot;file&quot;][&quot;tmp_name&quot;], &quot;uploads/&quot; . $filename);
	echo $filename;

}
?&gt;
</code></pre>


<ul>
<li>Upload the the PHP file to your web server and create a &#8220;uploads&#8221; directory. Make sure to CHMOD it 777 or 755.</li>
</ul>
<div><a href="/assets/Screen-Shot-2011-12-22-at-10.09.23-AM.png"><img class="alignnone size-full wp-image-192" title="Screen Shot 2011-12-22 at 10.09.23 AM" src="/img/Screen-Shot-2011-12-22-at-10.09.23-AM.png" alt="" width="266" height="117" /></a></div>
<p></p>
<ul>
<li>You can run your application now. You will not be able to access the camera from the iPhone simulator. You will need to have a provision profile installed on your iPhone. If you don&#8217;t already have an Apple developer account fork over the $100 bucks and grab one.</li>
<li>Bonus: If you want to allow people to view photos from your app, create a view.php file and read the contents of a directory.</li>
</ul>
<p>This is a very primitive example. Using the knowledge I&#8217;ve gained from working with PhoneGap and JQuery Mobile I was able to build a full featured, database driven, camera/video/audio recording mobile app that tags media using geolocation features and organizes the media into content groups. Each content group has an owner. When new content is submitted to that group the owner receives an email notification. Make sure to check out the <a href="http://docs.phonegap.com/en/1.3.0/index.html">PhoneGap documentation</a>.</p>

	    
<div id="disqus_thread">
            <div id="dsq-content">


            <ul id="dsq-comments">
                    <li class="comment even thread-even depth-1" id="dsq-comment-5679">
        <div id="dsq-comment-header-5679" class="dsq-comment-header">
            <cite id="dsq-cite-5679">
                <span id="dsq-author-user-5679">renato0408</span>
            </cite>
        </div>
        <div id="dsq-comment-body-5679" class="dsq-comment-body">
            <div id="dsq-comment-message-5679" class="dsq-comment-message"><p>Nice post. But i cant reproduce it. Where is the #loading div and #photos div?</p>
</div>
        </div>

    </li><!-- #comment-## -->
            </ul>


        </div>

    </div>

<script>
var disqus_url = 'http://www.tegdesign.com/creating-a-mobile-cameraweb-frontend-using-jquery-mobile-and-phonegap/';
var disqus_identifier = '168 http://www.tegdesign.com/?p=168';
var disqus_container_id = 'disqus_thread';
var disqus_shortname = 'tegdesign';
var disqus_title = "Creating a Mobile Camera\/Web Frontend using JQuery Mobile and PhoneGap";
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
        script.src = '?cf_action=sync_comments&post_id=168';

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