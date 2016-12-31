<!--
{{ $page_title = 'Import User List from Excel File into .Net Membership Provider' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'Import User List from Excel File into .Net Membership Provider', 'sub_txt' => 'Posted on September 2, 2010 at 4:17 pm'])



<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">


<p>So you have an existing ASP.NET Membership Provider and now your just got handed a Excel file full of username&#8217;s and passwords. Today I was faced with the task of writing a simple import script to take users from a Excel file and bring them into the membership database.<br />
<span id="more-75"></span></p>
<p>The code example below assumes you have a Microsoft Excel file (.xls) with a column called &#8220;user&#8221; and another column called &#8220;password&#8221;.</p>


<pre><code class="cs">
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Data;
using System.Data.Common;
using System.Web.Security;
using System.Web.Profile;

public partial class admin_admin_Import : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {

        string connectionString = @&quot;Provider=Microsoft.Jet.OLEDB.4.0;Data Source=C:\users.xls;Extended Properties=&quot;&quot;Excel 8.0;HDR=YES;&quot;&quot;&quot;;

        DbProviderFactory factory = DbProviderFactories.GetFactory(&quot;System.Data.OleDb&quot;);

        using (DbConnection connection = factory.CreateConnection())
        {
            connection.ConnectionString = connectionString;

            using (DbCommand command = connection.CreateCommand())
            {
                // Users$ comes from the name of the worksheet
                command.CommandText = &quot;SELECT user,password FROM [Users$]&quot;;

                connection.Open();

                using (DbDataReader dr = command.ExecuteReader())
                {
                    while (dr.Read())
                    {



                        if (Membership.GetUser(dr[&quot;user&quot;].ToString()) == null)
                        {

                            Membership.CreateUser(dr[&quot;user&quot;].ToString(), dr[&quot;password&quot;].ToString());

                        }
                       
                    } 

                }
            }
        }
    }

}
</code></pre>


	    </div>
	    <footer>
	      	      <ul class="entry-tags"><li><a href="/tag/asp-net/" rel="tag">ASP.NET</a></li><li><a href="/tag/c/" rel="tag">C#</a></li></ul>	    </footer>
	    
<div id="disqus_thread">
    </div>

<script>
var disqus_url = 'http://www.tegdesign.com/import-user-list-from-excel-file-into-net-membership-provider/';
var disqus_identifier = '75 http://www.tegdesign.com/?p=75';
var disqus_container_id = 'disqus_thread';
var disqus_shortname = 'tegdesign';
var disqus_title = "Import User List from Excel File into .Net Membership Provider";
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
        script.src = '?cf_action=sync_comments&post_id=75';

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