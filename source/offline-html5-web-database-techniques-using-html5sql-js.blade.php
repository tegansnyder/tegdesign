<!--
{{ $page_title = 'Offline HTML5 web database techniques using html5sql.js and JQuery Mobile' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'Offline HTML5 web database techniques using html5sql.js and JQuery Mobile', 'sub_txt' => 'Posted on November 16, 2011 at 6:09 pm'])



<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">


<p><img class="alignleft size-medium wp-image-127" style="margin: 5px 15px;" title="img1" alt="" src="http://www.tegdesign.com/wp-content/uploads/2011/11/img1-300x246.png" width="300" height="246" align="left" /></p>
<p>I recently came across Ken Corbett&#8217;s helper module developed to assist in working with HTML5 Web Databases. While the debate continues on offline storage and the HTML5 spec I decided to give this library a try. Below I will detail a few pointers for those interested in getting started. You can also find some documentation on<a href="https://github.com/KenCorbettJr/html5sql#readme"> github</a>.</p>
<p>The first step to getting started is to think about your database structure. Since you are going to be suing offline browser storage it is important to have the browser setup the database and create your tables for you when someone visits your web app.</p>
<p><strong>UPDATE: </strong>For quick access to the code download my example on Github:<br />
Create a file named &#8220;setup.sql&#8221;<a href="https://github.com/tegansnyder/JQuery-Mobile---HTML5-Web-DB-Example#readme">https://github.com/tegansnyder/JQuery-Mobile&#8212;HTML5-Web-DB-Example#readme</a></p>


<pre><code class="sql">
CREATE TABLE person (id INTEGER NOT NULL, first_name VARCHAR(35), last_name VARCHAR(35), age INTEGER, money DOUBLE, PRIMARY KEY (id));
CREATE TABLE places (id INTEGER NOT NULL, name VARCHAR(35), location VARCHAR(35) PRIMARY KEY (id));
</code></pre>


<p>This file will execute the first time a user visits your web app and will create all your required tables.</p>
<p>Download html5sql from github. Then create a blank JQuery mobile page using this structure.</p>


<pre><code>

	My Web App

&lt;script type=&quot;text/javascript&quot; src=&quot;http://code.jquery.com/jquery-1.6.4.min.js&quot;&gt;&lt;/script&gt;&lt;script type=&quot;text/javascript&quot; src=&quot;http://code.jquery.com/mobile/1.0rc3/jquery.mobile-1.0rc3.min.js&quot;&gt;&lt;/script&gt;

&lt;script type=&quot;text/javascript&quot; src=&quot;js/html5sql.js&quot;&gt;&lt;/script&gt;&lt;script type=&quot;text/javascript&quot; src=&quot;js/app.js&quot;&gt;&lt;/script&gt;&lt;/pre&gt;
&lt;div class=&quot;type-index&quot; data-role=&quot;page&quot; data-theme=&quot;a&quot;&gt;
&lt;div data-role=&quot;header&quot; data-theme=&quot;a&quot;&gt;
&lt;h1&gt;My Web App&lt;/h1&gt;
&lt;/div&gt;
&lt;div data-role=&quot;content&quot;&gt;
&lt;ul id=&quot;person&quot; data-role=&quot;listview&quot; data-inset=&quot;true&quot; data-theme=&quot;a&quot; data-dividertheme=&quot;a&quot;&gt;
	&lt;li data-icon=&quot;plus&quot;&gt;&lt;a href=&quot;new_run.html&quot;&gt;Person Details&lt;/a&gt;&lt;/li&gt;
&lt;/ul&gt;
&lt;/div&gt;
&lt;/div&gt;
&lt;pre&gt;

</code></pre>


<p>In your app.js file setup your connection like this:</p>


<pre><code class="javascript">
$(document).ready(function(){

    if(!html5sql.database){

        html5sql.openDatabase(&quot;com.myapp.appdb&quot;, &quot;App Data&quot;, 5*1024*1024);

		$.get('setup.sql',function(sqlStatements){
			html5sql.process(
				sqlStatements,
				function(){

				},
				function(error, statement){
					console.error(&quot;Error: &quot; + error.message + &quot; when processing &quot; + statement);
				}
			);
		});

    }

	html5sql.logInfo = true;
	html5sql.logErrors = true;
	html5sql.putSelectResultsInArray = true;

});
</code></pre>


<p>This will process on DOM ready and setup your table. You can see if it succeed by opening up Google Chrome web inspector and clicking on the Resources tab. You should see your database has been created.</p>
<p>To add data use:</p>


<pre><code class="javascript">
		var first_name = 'Tegan';
		var last_name = 'Snyder';
		var age = '25';
		var money = '5500';

 		html5sql.process(
			[
			    &quot;INSERT INTO person (first_name, last_name, age, money) VALUES ('&quot; + first_name + &quot;','&quot; + last_name + &quot;','&quot; + age + &quot;','&quot; + money + &quot;')&quot;
			],
			function(){

				alert('sucess');

			},
			function(error, statement){
				console.error(&quot;Error: &quot; + error.message + &quot; when processing &quot; + statement);
			}
		);
</code></pre>


<p>To select data use:</p>


<pre><code class="javascript">
	html5sql.process(
		[
		    &quot;SELECT * FROM person WHERE id = 1&quot;
		],
		function(transaction, results, rowArray) {

			var html = '&lt;/pre&gt;
&lt;ul&gt;
	&lt;li data-theme=&quot;b&quot;&gt;';

 $.each(rowArray, function(index, value) {
 html += '
First name: ' + value.first_name + '

';
 html += '
Last name: ' + value.last_name + '

';
 html += '
Age: ' + value.age + '

';
 });

 html += '&lt;/li&gt;
&lt;/ul&gt;
&lt;pre&gt;
';

			//refresh jquery mobile listview

			$('#person').append(html);
			$('#person').listview(&quot;refresh&quot;);

		},
		function(error, statement){
			console.error(&quot;Error: &quot; + error.message + &quot; when processing &quot; + statement);
		}
	);
</code></pre>


<p>This is just a quick overview. More details to come as I start playing with this and session storage.</p>
<p>Check out Kens website: <a href="http://html5sql.com/">http://html5sql.com</a></p>
<p><img class="alignleft size-medium wp-image-134" title="img3" alt="" src="http://www.tegdesign.com/wp-content/uploads/2011/11/img31-300x175.png" width="300" height="175" /><img class="alignleft size-medium wp-image-129" title="img2" alt="" src="http://www.tegdesign.com/wp-content/uploads/2011/11/img2-218x300.png" width="218" height="300" /></p>

	    
<div id="disqus_thread">
            <div id="dsq-content">


            <ul id="dsq-comments">
                    <li class="comment even thread-even depth-1" id="dsq-comment-1828">
        <div id="dsq-comment-header-1828" class="dsq-comment-header">
            <cite id="dsq-cite-1828">
                <span id="dsq-author-user-1828">Tim Bendall</span>
            </cite>
        </div>
        <div id="dsq-comment-body-1828" class="dsq-comment-body">
            <div id="dsq-comment-message-1828" class="dsq-comment-message"><p>Hi Tegan.</p>
<p>I&#8217;m playing around with Ken&#8217;s module at the moment and wanted to ask a question about it that you hopefully know the answer to.</p>
<p>If I pass in an array of three SQL select statements, how do I extract the values from these three statements?</p>
<p>Normally you would use results.rows.item(x).value.  Does this syntax still work?  Or do you need to iterate through the array that gets created and keep track of which element of the array corresponds to which SQL statement?</p>
<p>Nice article, I&#8217;m looking forward to seeing you expand this and maybe I&#8217;ll be able to help contribute in the future.</p>
<p>Tim</p>
</div>
        </div>

    </li><!-- #comment-## -->
    <li class="comment byuser comment-author-tegan bypostauthor odd alt thread-odd thread-alt depth-1" id="dsq-comment-1918">
        <div id="dsq-comment-header-1918" class="dsq-comment-header">
            <cite id="dsq-cite-1918">
                <span id="dsq-author-user-1918">tegan</span>
            </cite>
        </div>
        <div id="dsq-comment-body-1918" class="dsq-comment-body">
            <div id="dsq-comment-message-1918" class="dsq-comment-message"><p>Tim can you provide an example?</p>
</div>
        </div>

    <ul class="children">
    <li class="comment even depth-2" id="dsq-comment-1919">
        <div id="dsq-comment-header-1919" class="dsq-comment-header">
            <cite id="dsq-cite-1919">
                <span id="dsq-author-user-1919">Tim Bendall</span>
            </cite>
        </div>
        <div id="dsq-comment-body-1919" class="dsq-comment-body">
            <div id="dsq-comment-message-1919" class="dsq-comment-message"><p>Sure, thanks for your help.</p>
<p>This is a made up example:</p>
<p>html5sql.process(<br />
        [select price from cars, select tax from duty, select discount from catalogue]<br />
        function(results){<br />
        alert((results.rows.item(0).price &#8211; results.rows.item(0).discount) * (1- results.rows.item(0).tax));<br />
        },<br />
        function(error){<br />
        alert(&#8216;Error: &#8216; + error.message);<br />
        }<br />
    );<br />
});</p>
<p>I know that the results.rows.item() code needs to be replaced with the array that gets created by html5sql.js, but I don&#8217;t know how that array is arranged and how to get the data out.</p>
<p>Cars, production and stock are non-related tables but I need to use values from all within the one function.  Without html5sql.js I would nest transactions within transactions in order to get all the data I needed, then perform the calculation.  If you don&#8217;t nest the transactions then you aren&#8217;t guaranteed to have all the values by the time the calculation is made (because of javascripts asynchronous nature).</p>
<p>What code do I use to get the data from the 3 SQL statements?</p>
<p>Cheers.</p>
</div>
        </div>

    <ul class="children">
    <li class="comment byuser comment-author-tegan bypostauthor odd alt depth-3" id="dsq-comment-1920">
        <div id="dsq-comment-header-1920" class="dsq-comment-header">
            <cite id="dsq-cite-1920">
                <span id="dsq-author-user-1920">tegan</span>
            </cite>
        </div>
        <div id="dsq-comment-body-1920" class="dsq-comment-body">
            <div id="dsq-comment-message-1920" class="dsq-comment-message"><p>Does this help?</p>


<pre><code class="javascript">
	html5sql.process(
		[
		    &quot;SELECT * FROM people&quot;
		],
		function(transaction, results, rowArray) {
		
			var html = '';

			$.each(rowArray, function(index, value) {

				console.log(value.person_id);
				console.log(value.person_name); 
			  
			});
			
		},
		function(error, statement){
			//console.error(&quot;Error: &quot; + error.message + &quot; when processing &quot; + statement);
		}        
	);
</code></pre>


</div>
        </div>

    <ul class="children">
    <li class="comment even depth-4" id="dsq-comment-1921">
        <div id="dsq-comment-header-1921" class="dsq-comment-header">
            <cite id="dsq-cite-1921">
                <span id="dsq-author-user-1921">Tim Bendall</span>
            </cite>
        </div>
        <div id="dsq-comment-body-1921" class="dsq-comment-body">
            <div id="dsq-comment-message-1921" class="dsq-comment-message"><p>Yes it does, thanks heaps for taking the time to help</p>
</div>
        </div>

    <ul class="children">
    <li class="comment byuser comment-author-tegan bypostauthor odd alt depth-5" id="dsq-comment-1922">
        <div id="dsq-comment-header-1922" class="dsq-comment-header">
            <cite id="dsq-cite-1922">
                <span id="dsq-author-user-1922">tegan</span>
            </cite>
        </div>
        <div id="dsq-comment-body-1922" class="dsq-comment-body">
            <div id="dsq-comment-message-1922" class="dsq-comment-message"><p>Not a problem! Glad I could help.</p>
</div>
        </div>

    </li><!-- #comment-## -->
</ul><!-- .children -->
</li><!-- #comment-## -->
    <li class="comment even depth-4" id="dsq-comment-2160">
        <div id="dsq-comment-header-2160" class="dsq-comment-header">
            <cite id="dsq-cite-2160">
                <span id="dsq-author-user-2160">Eric Xin Zhang</span>
            </cite>
        </div>
        <div id="dsq-comment-body-2160" class="dsq-comment-body">
            <div id="dsq-comment-message-2160" class="dsq-comment-message"><p>Hi Tegan,</p>
<p>Sorry, I don&#8217;t understand your reply here. The html5sql.js can process multiple SQLs sequentially, and I think Tim&#8217;s question actually was, if he provides 3 SELECT statements, how he could get the 3 results of the 3 SELECT statements accordingly in the final success callback function. From your reply above I could not see that. Forgive me if I&#8217;m wrong, and thanks in advance if you know how to get those results.</p>
</div>
        </div>

    </li><!-- #comment-## -->
</ul><!-- .children -->
</li><!-- #comment-## -->
</ul><!-- .children -->
</li><!-- #comment-## -->
</ul><!-- .children -->
</li><!-- #comment-## -->
    <li class="comment odd alt thread-even depth-1" id="dsq-comment-4087">
        <div id="dsq-comment-header-4087" class="dsq-comment-header">
            <cite id="dsq-cite-4087">
                <span id="dsq-author-user-4087">Rachel</span>
            </cite>
        </div>
        <div id="dsq-comment-body-4087" class="dsq-comment-body">
            <div id="dsq-comment-message-4087" class="dsq-comment-message"><p>I was happy to find this code. It is great as far as it goes&#8230;but, the source files don&#8217;t match the tutorial. The list doesn&#8217;t display the saved data. I&#8217;d really like to understand how to use this code, but, I&#8217;m new and wish the tutorial would have provided a full working version that could be tweaked to use by newbies.<br />
1. Input data (present)<br />
2. Save data (present)<br />
3. Display saved data in a new page for each record (missing)</p>
<p>Please consider updating your code for those of us who are floundering. </p>
<p>Thank you for your work. -Rachel</p>
</div>
        </div>

    </li><!-- #comment-## -->
            </ul>


        </div>

    </div>

<script>
var disqus_url = 'http://www.tegdesign.com/offline-html5-web-database-techniques-using-html5sql-js/';
var disqus_identifier = '115 http://www.tegdesign.com/?p=115';
var disqus_container_id = 'disqus_thread';
var disqus_shortname = 'tegdesign';
var disqus_title = "Offline HTML5 web database techniques using html5sql.js and JQuery Mobile";
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
        script.src = '?cf_action=sync_comments&post_id=115';

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