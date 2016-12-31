<!--
{{ $page_title = 'Retrieving User Profile Data in VB.NET from SharePoint' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'Retrieving User Profile Data in VB.NET from SharePoint', 'sub_txt' => 'Posted on September 1, 2010 at 6:15 pm'])



<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">


<p>A recent project I begin working on required me to pull data from SharePoint MySite User Profiles. Luckily Microsoft provided a great way to access the fields in user profiles. In this post I will croncical how to leverage VB.NET and the SharePoint web services to get the job done.<span id="more-56"></span></p>
<p><strong>Step 1</strong></p>
<p><strong><a href="http://www.tegdesign.com/wp-content/uploads/2010/09/p1.jpg"><img class="alignnone size-medium wp-image-63" title="p1" src="http://www.tegdesign.com/wp-content/uploads/2010/09/p1-300x207.jpg" alt="" width="300" height="207" /></a></strong></p>
<p>First step is to fire up Visual Studio and create a new project.</p>
<p><strong>Step 2</strong></p>
<p><strong><a href="http://www.tegdesign.com/wp-content/uploads/2010/09/21.jpg"><img class="alignnone size-medium wp-image-64" title="2" src="http://www.tegdesign.com/wp-content/uploads/2010/09/21-300x130.jpg" alt="" width="300" height="130" /></a></strong></p>
<p>The next step is to add a simple aspx page to your solution.</p>
<p><a href="http://www.tegdesign.com/wp-content/uploads/2010/09/p3.jpg"><img class="alignnone size-medium wp-image-66" title="p3" src="http://www.tegdesign.com/wp-content/uploads/2010/09/p3-300x207.jpg" alt="" width="300" height="207" /></a></p>
<p>Chose Web Form and give it the name Default.aspx and click Add</p>
<p><strong>Step 3</strong></p>
<p><strong><a href="http://www.tegdesign.com/wp-content/uploads/2010/09/p4.jpg"><img class="alignnone size-medium wp-image-67" title="p4" src="http://www.tegdesign.com/wp-content/uploads/2010/09/p4-300x207.jpg" alt="" width="300" height="207" /></a></strong></p>
<p>The next step is to add web reference(s) to SharePoint&#8217;s web services. In my project it was required that I collect a profile field for each user in SharePoint.</p>
<p><a href="http://www.tegdesign.com/wp-content/uploads/2010/09/p5.jpg"><img class="alignnone size-medium wp-image-65" title="p5" src="http://www.tegdesign.com/wp-content/uploads/2010/09/p5-300x207.jpg" alt="" width="300" height="207" /></a></p>
<p>Enter the URL to the web service:<br />
<a href="http://mysite/_vti_bin/userprofileservice.asmx?wsdl">http://mysite/_vti_bin/userprofileservice.asmx?wsdl</a></p>
<p>Enter a name for your web service&#8230; I called my &#8220;wsUP&#8221;</p>
<p><strong>Step 4</strong></p>
<p>Switch to the Code-Behind of the default.aspx file and add the following in the page_load event.</p>


<pre><code class="vbnet">
Partial Class _Default
    Inherits System.Web.UI.Page

    Protected Sub Page_Load(ByVal sender As Object, ByVal e As System.EventArgs) Handles Me.Load

        Dim wsUserProfiles As wsUP.UserProfileService = New wsUP.UserProfileService
        wsUserProfiles.Credentials = System.Net.CredentialCache.DefaultCredentials

        Dim numProfiles As Integer = CType(wsUserProfiles.GetUserProfileCount, Integer)

        For i As Integer = 0 To numProfiles

            Dim profileIndex As wsUP.GetUserProfileByIndexResult = wsUserProfiles.GetUserProfileByIndex(i)

            'Login Name
            Response.Write(profileIndex.UserProfile(1).Values(0).Value)

            'First Name
            Response.Write(profileIndex.UserProfile(2).Values(0).Value)

            'Last Name
            Response.Write(profileIndex.UserProfile(3).Values(0).Value)

        Next

    End Sub

End Class
</code></pre>


<p>This should get you an idea of what you can do. I reccommend setting a debug point on the &#8220;profileIndex&#8221; variable to figure out other indices of UserProfile like &#8220;work email, department, etc&#8221;.</p>
<p>One thing to look out for is users running this code that aren&#8217;t administrators. The GetUserProfileCount method needs administrator privileges for the user running the code in Sharepoint. If you need to use credentials different from the default machine credentials you can explicitly set them:</p>


<pre><code class="vbnet">
Dim credentials As NetworkCredential = New NetworkCredential(&quot;DOMAIN\accountLogin&quot;, &quot;mypassword&quot;)
wsUserProfiles.Credentials = credentials
</code></pre>


<p><em>Note: you will need to Imports System.Net</em></p>
<p>I&#8217;d like to find a more efficient way to grab specific profile properties from all the users without looping through the whole user profile. If anybody has any ideas please chime in.</p>
<p>Related Links:<br />
<a href="http://stackoverflow.com/questions/2992468/sharepoint-2010-get-the-distinct-values-of-a-user-profile-property">http://stackoverflow.com/questions/2992468/sharepoint-2010-get-the-distinct-values-of-a-user-profile-property</a><br />
<a href="http://usingsystem.wordpress.com/2008/01/18/sharepoint-user-profiles-part-2/#comments">http://usingsystem.wordpress.com/2008/01/18/sharepoint-user-profiles-part-2/#comments</a><br />
<a href="http://stackoverflow.com/questions/329535/sharepoint-get-a-list-of-current-users">http://stackoverflow.com/questions/329535/sharepoint-get-a-list-of-current-users</a></p>
	    </div>
	    <footer>
	      	      <ul class="entry-tags"><li><a href="/tag/sharepoint-2/" rel="tag">Sharepoint</a></li><li><a href="/tag/vb-net/" rel="tag">VB.NET</a></li></ul>	    </footer>
	    
<div id="disqus_thread">
            <div id="dsq-content">


            <ul id="dsq-comments">
                    <li class="comment even thread-even depth-1" id="dsq-comment-1553">
        <div id="dsq-comment-header-1553" class="dsq-comment-header">
            <cite id="dsq-cite-1553">
                <span id="dsq-author-user-1553">Ridhuan</span>
            </cite>
        </div>
        <div id="dsq-comment-body-1553" class="dsq-comment-body">
            <div id="dsq-comment-message-1553" class="dsq-comment-message"><p>Hi Tegan,<br />
I need your help regarding to retrieve current user profile in vb.net using userprofileservice. i need to populate current user profile data (name, department) into textbox field. i&#8217;ve try your solution but i still don&#8217;t get the data. Help me please&#8230;</p>
<p>Regards,<br />
Ridhuan</p>
</div>
        </div>

    </li><!-- #comment-## -->
            </ul>


        </div>

    </div>

<script>
var disqus_url = 'http://www.tegdesign.com/retrieving-user-profile-data-in-vb-net-from-sharepoint/';
var disqus_identifier = '56 http://www.tegdesign.com/?p=56';
var disqus_container_id = 'disqus_thread';
var disqus_shortname = 'tegdesign';
var disqus_title = "Retrieving User Profile Data in VB.NET from SharePoint";
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
        script.src = '?cf_action=sync_comments&post_id=56';

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