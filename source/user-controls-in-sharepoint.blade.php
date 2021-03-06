<!--
{{ $page_title = 'Creating User Controls in Microsoft SharePoint Master Pages' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'Creating User Controls in Microsoft SharePoint Master Pages', 'sub_txt' => 'Posted on August 25, 2010 at 2:45 am'])



<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">


<p>If you find the need to extend SharePoint by including a Web User Control then you are in the right place. I scoured the web looking for a good tutorial for creating User Controls for SharePoint and found many of them incomplete and leaving out important pieces of information. In this tutorial I will show you how to create a User Control with Code-Behind and incorporate it in your SharePoint Master Page.</p>
<p><span id="more-1"></span></p>
<p>Create a New C# ASP.NET Web Application Project (.NET 2.0 &#8212; 3.5)</p>
<p><a href="http://www.tegdesign.com/wp-content/uploads/2010/08/1.jpg"><img title="Step1" src="http://www.tegdesign.com/wp-content/uploads/2010/08/1-300x207.jpg" alt="" width="300" height="207" /></a><br />
</p>
<p>Delete the Default.aspx, Scripts Folder, and App_Data folder.</p>
<p><a href="http://www.tegdesign.com/wp-content/uploads/2010/08/2.jpg"><img class="alignnone size-medium wp-image-12" title="2" src="http://www.tegdesign.com/wp-content/uploads/2010/08/2-300x256.jpg" alt="" width="300" height="256" /></a><br />
</p>
<p>Your Solution Explorer should only contain the following:</p>
<p><a href="http://www.tegdesign.com/wp-content/uploads/2010/08/3.jpg"><img class="alignnone size-medium wp-image-13" title="3" src="http://www.tegdesign.com/wp-content/uploads/2010/08/3-300x218.jpg" alt="" width="300" height="218" /></a></p>
<p>Right Click on Your Project &#8211; Add &#8211; New Files&#8230;</p>
<p><a href="http://www.tegdesign.com/wp-content/uploads/2010/08/4.jpg"><img class="alignnone size-medium wp-image-14" title="4" src="http://www.tegdesign.com/wp-content/uploads/2010/08/4-300x218.jpg" alt="" width="300" height="218" /></a></p>
<p>Add a Web User Control to the project</p>
<p><a href="http://www.tegdesign.com/wp-content/uploads/2010/08/5.jpg"><img class="alignnone size-medium wp-image-15" title="5" src="http://www.tegdesign.com/wp-content/uploads/2010/08/5-300x207.jpg" alt="" width="300" height="207" /></a></p>
<p>Expand the User Control and Open it&#8217;s Code-Behind .cs file:</p>
<p><a href="http://www.tegdesign.com/wp-content/uploads/2010/08/5.jpg"></a><a href="http://www.tegdesign.com/wp-content/uploads/2010/08/6.jpg"><img class="alignnone size-medium wp-image-16" title="6" src="http://www.tegdesign.com/wp-content/uploads/2010/08/6-300x199.jpg" alt="" width="300" height="199" /></a></p>
<p>In the user controls&#8217; .cs file add this to the top:</p>


<pre><code class="vbnet">
using System;
using System.Collections;
using System.Configuration;
using System.Data;
using System.Data.SqlClient;
using System.Web;
using System.Security;
using System.Web.UI;
using System.Web.UI.HtmlControls;
using System.Web.UI.WebControls;
using System.Web.UI.WebControls.WebParts;
</code></pre>


<p>Program the file as you so wish.</p>
<p><a href="http://www.tegdesign.com/wp-content/uploads/2010/08/7.jpg"><img class="alignnone size-medium wp-image-17" title="7" src="http://www.tegdesign.com/wp-content/uploads/2010/08/7-300x199.jpg" alt="" width="300" height="199" /></a></p>
<p>Right Click on the Solution and Choose Properties.</p>
<p><a href="http://www.tegdesign.com/wp-content/uploads/2010/08/8.jpg"><img class="alignnone size-medium wp-image-18" title="8" src="http://www.tegdesign.com/wp-content/uploads/2010/08/8-300x235.jpg" alt="" width="300" height="235" /></a></p>
<p>Change the build options to Sign the Assembly.</p>
<p><a href="http://www.tegdesign.com/wp-content/uploads/2010/08/9.jpg"><img class="alignnone size-medium wp-image-19" title="9" src="http://www.tegdesign.com/wp-content/uploads/2010/08/9-300x188.jpg" alt="" width="300" height="188" /></a></p>
<p>Choose a New from the drop down</p>
<p><a href="http://www.tegdesign.com/wp-content/uploads/2010/08/10.jpg"><img class="alignnone size-medium wp-image-20" title="10" src="http://www.tegdesign.com/wp-content/uploads/2010/08/10-300x188.jpg" alt="" width="300" height="188" /></a></p>
<p>Give your key a name. I choose to disable password protection.</p>
<p><a href="http://www.tegdesign.com/wp-content/uploads/2010/08/111.jpg"><img class="alignnone size-medium wp-image-21" title="11" src="http://www.tegdesign.com/wp-content/uploads/2010/08/111-300x182.jpg" alt="" width="300" height="182" /></a></p>
<p>Build the Project</p>
<p><a href="http://www.tegdesign.com/wp-content/uploads/2010/08/13.jpg"><img class="alignnone size-medium wp-image-23" title="13" src="http://www.tegdesign.com/wp-content/uploads/2010/08/13-300x177.jpg" alt="" width="300" height="177" /></a></p>
<p>Open the Visual Studio Tools command prompt and run:</p>
<p>SN -k pathto/bin/mycompiled.dll</p>
<p>This will give you the PublicKeyToken</p>
<p><a href="http://www.tegdesign.com/wp-content/uploads/2010/08/14.jpg"><img class="alignnone size-medium wp-image-24" title="14" src="http://www.tegdesign.com/wp-content/uploads/2010/08/14-267x300.jpg" alt="" width="267" height="300" /></a></p>
<p>We need slightly modify to the ascx file so that it uses the compiled DLL instead of the .cs Code-Behind. Change the Inherit tag in the ascx file to something like below :</p>


<pre><code class="vbnet">
&lt;%@ Control Language=&quot;C#&quot; AutoEventWireup=&quot;true&quot; Inherits=&quot;SomeControlProject.SomeControl, SomeControlProject, Version=1.0.0.0, Culture=neutral, PublicKeyToken=a66181fff4923fe5&quot; %&gt;
</code></pre>


<p>Copy the ascx file to your SharePoint server and place it in: TEMPLATE\CONTROLTEMPLATES</p>
<p>To add the control to SharePoint drop the ascx file into controltemplates folder of SharePoint.</p>
<p>Usually : C:\Program Files\Common Files\Microsoft Shared\web server extensions\12\TEMPLATE\CONTROLTEMPLATES</p>
<p>Next, drag and drop your signed assembly (DLL file) into GAC or C:\WINDOWS\assembly folder.</p>
<p>In order for SharePoint to trust your new user control it is important to create a SafeControl declarion in the web.config file.</p>


<pre><code class="vbnet">
&lt;SafeControls&gt;
  &lt;SafeControl Assembly=&quot;SomeControlProject, Version=1.0.0.0, Culture=neutral, PublicKeyToken=a66181fff4923fe5&quot; Namespace=&quot;SomeControlProject&quot; TypeName=&quot;*&quot; Safe=&quot;True&quot; /&gt;
&lt;/SafeControls&gt;
</code></pre>


<p>Finally you are ready to add our user control to your SharePoint Master Page. Open your SharePoint site in SharePoint designer and check out the master page.</p>
<p>To register the control add this at the top of the page:</p>


<pre><code class="vbnet">
&lt;%@ Register TagPrefix=”uc” src=”~/_controltemplates/SomeControl.ascx” TagName=”MyControlName” %&gt;
</code></pre>


<p>Finally add the control anywhere on your page:</p>


<pre><code class="vbnet">
&lt;uc:MyControlName id=&quot;whateveruwant&quot; runat=&quot;server&quot;&gt;
</code></pre>


<p><strong>Note: If you make changes to the DLL you must change the version number of the assembly. You must also TAP the ASCX files. Add a few spaces to the files and then save them to get teh Date Modified to be different to prevent caching. May need to refresh the GAC cache.</strong></p>

	    
<div id="disqus_thread">
    </div>

<script>
var disqus_url = 'http://www.tegdesign.com/user-controls-in-sharepoint/';
var disqus_identifier = '1 http://www.tegdesign.com/?p=1';
var disqus_container_id = 'disqus_thread';
var disqus_shortname = 'tegdesign';
var disqus_title = "Creating User Controls in Microsoft SharePoint Master Pages";
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
        script.src = '?cf_action=sync_comments&post_id=1';

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