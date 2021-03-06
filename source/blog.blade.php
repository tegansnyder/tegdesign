<!--
{{ $page_title = 'Blog' }}
{{ $page_body_class = 'page-blog' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'The Neglected Blog', 'sub_txt' => 'I like to share some of my experiences when I have the time. Please note the post dates.'])

<div class="container">

	<div class="row">

		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-pad-l">

			<article class="even">
				<h2><a href="/using-kafka-jdbc-connector-with-teradata-source-and-mysql-sink">Using Kafka JDBC Connector with Teradata Source and MySQL Sink</a></h2>
				<small><span class="label">Feb 14, 2017</span></small>
				<p>This post describes a recent setup of mine exploring the use of Kafka for pulling data out of Teradata into MySQL. Recent versions of Kafka provide purpose built connectors that are extremely useful ...</p>
			</article>

			<article class="odd">
				<h2><a href="/converting-a-nested-json-document-to-csv-using-scala-hadoop-and-apache-spark">Converting a nested JSON document to CSV using Scala, Hadoop, and Apache Spark</a></h2>
				<small><span class="label">Feb 13, 2017</span></small>
				<p>Usually when I want to convert a JSON file to a CSV I will write a simple script in PHP. Lately I've been playing more with Apache Spark and wanted to try converting a 600MB JSON file to a CSV using a 3 node cluster I have setup ...</p>
			</article>

			<article class="even">
				<h2><a href="/setting-up-my-new-static-site-using-jigsaw">Setting up my new static site using Jigsaw</a></h2>
				<small><span class="label">Jan 1st, 2017</span></small>
				<p>Welcome to 2017. Each year over the holidays I try to do something new to my neglected website. This year I decided to update the layout and use the static site generator Jigsaw to make it easier to maintain ...</p>
			</article>

			<article class="odd">
				<h2><a href="/compiling-php-7-with-pthreads-support-and-custom-odbc-teradata-on-macos-sierra">Compiling PHP 7 on MacOS Sierra with Threading and Custom ODBC Support</a></h2>
				<small><span class="label">Dec 27th, 2016</span></small>
				<p>I recently got a new 2016 Macbook Pro and needed to setup my custom PHP environment. I typically compile from source so I have the flexibility to include options not typically found in the typical installers. In this post I detail my setup which includes support for pthreads and teradata odbc ...</p>
			</article>

			<article class="even">
				<h2><a href="/using-redis-sentinel-haproxy-rhel-6">Using Redis + Sentinel HAProxy RHEL 6</a></h2>
				<small><span class="label">May 27th, 2016</span></small>
				<p>Some notes on mine on using HAProxy to help Redis manage failure with Redis Sentienl. Instructions for RHEL 6.</p>
			</article>

			<article class="odd">
				<h2><a href="/compiling-php-7-with-pthreads-support-and-custom-odbc-teradata-on-macos-sierra">Compiling PHP 7 on MacOS Sierra with Threading and Custom ODBC Support</a></h2>
				<small><span class="label">Dec 27th, 2016</span></small>
				<p>I recently got a new 2016 Macbook Pro and needed to setup my custom PHP environment. I typically compile from source so I have the flexibility to include options not typically found in the typical installers. In this post I detail my setup which includes support for pthreads and teradata odbc ...</p>
			</article>

			<article class="even">
				<h2><a href="/setting-up-hhvm-nginx-php-5-5-percona-5-5-and-redis-for-magento">Setting up HHVM, Nginx, PHP 5.5, Percona 5.5, and Redis for Magento</a></h2>
				<small><span class="label">November 11th, 2014</span></small>
				<p>I've had the opportunity to try a variety of different server configurations but never really got around to trying HHVM with Magento until recently. I thought I would share a detailed walkthrough of configuring a single instance Magento server running ...</p>
			</article>

			<article class="even">
				<h2><a href="/querying-github-api-golang-find-magento-repositories">Querying the Github API with Golang to find all Magento Repositories</a></h2>
				<small><span class="label">September 10th, 2014</span></small>
				<p>The purpose of this post is to describe my first attempt at a Golang programing. I decided it would be neat to use Golang to query the Github API for list Magento repositories and the location associated with the owner ...</p>
			</article>

			<article class="odd">
				<h2><a href="/deploying-magento-code-using-capistrano">Deploying your Magento code using Capistrano</a></h2>
				<small><span class="label">August 23rd, 2014</span></small>
				<p>I spend a lot of time lately working on server orchestration type of activities. With the amount of Magento instances I manage and the code bases that make them what they are it's very beneficial to me to have a ...</p>
			</article>

			<article class="even">
				<h2><a href="/using-twitter-cards-in-magento-a-facebook-open-graph-example-with-magento">Using Twitter Cards in Magento + A Facebook Open Graph Example with Magento</a></h2>
				<small><span class="label">September 16th, 2013</span></small>
				<p>I recently came across this article on HackerNews: 'How to Add Twitter's New Product Cards to your Shopify Store?'' I decided to take the same approach they took but instead of using Shopify why not try it on Magento ...</p>
			</article>

			<article class="odd">
				<h2><a href="/super-fast-magento-lessons-learned-implementing-head-js-and-varnish/">Super Fast Magento: Lessons learned implementing Head.js and Varnish</a></h2>
				<small><span class="label">August 31st, 2013</span></small>
				<p>Over the course of the past few months I've been on journey to score good numbers on Google's PageSpeed tool. Now out of the box Magento doesn't really conform to Google's standards at all. Extensions you add to your store ...</p>
			</article>

			<article class="even">
				<h2><a href="/moving-ecommerce-platforms-dont-blow-it/">Moving Ecommerce Platforms? Don't Blow It.</a></h2>
				<small><span class="label">January 10th, 2013</span></small>
				<p>Two months ago I was a 26 years old with a great job in the utility industry. I was respected as a leader in web development for electric utilities (NPPD). I spoke at conferences and I built mobile apps for ...</p>
			</article>

			<article class="odd">
				<h2><a href="/cnn-iphone-app-like-swipe-from-top-menu-using-zepto-js-and-phonegap/">CNN iPhone app like Swipe-From-Top menu using Zepto.js and Phonegap</a></h2>
				<small><span class="label">December 24th, 2012</span></small>
				<p>This is an example prepared to show off a type of sliding drawer menu similar to CNN's iPhone app, but using HTML5 Hybrid approach with Javascript, CSS3, and Zepto.js. Paired with PhoneGap its a great menu treatment technique. This is ...</p>
			</article>

			<article class="even">
				<h2><a href="/magento-programmatically-create-recurring-profiles-authorize-net-cim/">Magento Programmatically Create Recurring Profiles Authorize.net CIM</a></h2>
				<small><span class="label">December 24th, 2012</span></small>
				<p>When I took the job at Bulu Box. My goal was to move us into a move versatile ecommerce platform. For months we had been successfully using Shopify for product sales and Recurly for subscription billing, but as we wanted ...</p>
			</article>

			<article class="odd">
				<h2><a href="/using-the-fullcontact-api-on-customers-who-complete-purchases-my-magento-extension/">Using the FullContact API on customers who complete purchases. My Magento Extension.</a></h2>
				<small><span class="label">December 24th, 2012</span></small>
				<p>If you are not familiar with FullContact check out their website at: http://www.fullcontact.com. They have a nifty service that allows developers to gather social details for an email address. For instance, lets say a customer makes a purchase at your e-commerce website. What  ...</p>
			</article>

			<article class="even">
				<h2><a href="/improving-the-recurring-profiles-grid-in-magento/">Improving the Recurring Profiles Grid in Magento</a></h2>
				<small><span class="label">December 24th, 2012</span></small>
				<p>Recurring Profiles are in Beta, but that doesn't mean we can't spice up the grid and add some new columns for customer information, email, id, next bill date, etc. Here is an example: I added new columns to the recurring ...</p>
			</article>

			<article class="odd">
				<h2><a href="/git-webhook-php-post-receive-pull-method/">Git WebHook PHP Post Receive Pull Method</a></h2>
				<small><span class="label">June 27th, 2012</span></small>
				<p>I hope this finds someone well. I spent a few hours trying to iron out the process of deploying to git from my laptop and then automatically initiating a pull request on my server using Github's Web Hooks. There is ...</p>
			</article>

			<article class="even">
				<h2><a href="/startup-in-progress-learning-as-we-go/">Startup in progress. Learning as we go.</a></h2>
				<small><span class="label">June 6th, 2012</span></small>
				<p>In late February I began working on a product that quickly became my obsession called Footwork. At it's core a straight forward idea – a system for canvassing door to door. A system that my partner, Phil Montag, and I thought was broken. What ...</p>
			</article>

			<article class="odd">
				<h2><a href="/jquery-mobile-popup-textarea-exmaple/">JQuery Mobile Popup Textarea Example</a></h2>
				<small><span class="label">June 2nd, 2012</span></small>
				<p>This example is simple. Fixed header. Fixed footer. One button centered in the footer. Click the 'My Notes' button to display a popup containing a textbox ...</p>
			</article>

			<article class="even">
				<h2><a href="/jquery-mobile-slide-menu-like-facebook-or-path/">JQuery Mobile Slide Menu like Facebook or Path</a></h2>
				<small><span class="label">May 16th, 2012</span></small>
				<p>I wanted to create a JQuery Mobile menu that can be used similar to how the Facebook, Path, and Highlight apps work. Full code available on my github here. Demo here. Please note: It's not perfect, but it does allow ...</p>
			</article>

			<article class="odd">
				<h2><a href="/node-soup-mongolian-express-php-rest-example/">Node Soup = Mongolian, Express, PHP, Rest Example</a></h2>
				<small><span class="label">April 21st, 2012</span></small>
				<p>Just posted a little example of a node.js, mongolian deadbeef, express, and php working together on my Github. Here is my app.js Here is the php test file ...</p>
			</article>

			<article class="even">
				<h2><a href="/my-new-project-footwork/">My new project Footwork!</a></h2>
				<small><span class="label">April 16th, 2012</span></small>
				<p>I have been working on a interesting project for the past few months. Footwork! It's a mobile application and backend management console that allows political campaigns to manage canvassing efforts over mobile phones/tablets. The backend is built using PHP/Node.js/MongoDB/Twitter Bootstrap ...</p>
			</article>

			<article class="odd">
				<h2><a href="/iscroll-jquery-mobile-example/">iScroll + JQuery Mobile Example</a></h2>
				<small><span class="label">January 11th, 2012</span></small>
				<p>For your viewing pleasure I would like to present a nice starting point for working with JQuery Mobile and iScroll. Source code available on my Github account here: https://github.com/tegansnyder/iScroll-Example Note: this example is setup to work with PhoneGap 1.3 ...</p>
			</article>

			<article class="even">
				<h2><a href="/creating-a-mobile-cameraweb-frontend-using-jquery-mobile-and-phonegap/">Creating a Mobile Camera/Web Frontend using JQuery Mobile and PhoneGap</a></h2>
				<small><span class="label">December 22nd, 2011</span></small>
				<p>I'm really starting to enjoy the most recent JQuery Mobile release paired with PhoneGap. It really allows a developer to build a hybrid mobile application relatively quickly. I recently started a project to develop a mobile app that takes pictures ...</p>
			</article>

			<article class="odd">
				<h2><a href="/offline-html5-web-database-techniques-using-html5sql-js/">Offline HTML5 web database techniques using html5sql.js and JQuery Mobile</a></h2>
				<small><span class="label">November 16th, 2011</span></small>
				<p>I recently came across Ken Corbett's helper module developed to assist in working with HTML5 Web Databases. While the debate continues on offline storage and the HTML5 spec I decided to give this library a try. Below I will detail ...</p>
			</article>

			<article class="even">
				<h2><a href="/ios_android_xcelsius/">Converting SAP Xcelsius .SWF File to iOS and Android</a></h2>
				<small><span class="label">June 10th, 2011</span></small>
				<p>UPDATE: Adobe AIR 2.7 has been released with much improvement for IOS. Please read this post. There are a lot of neat things going on with Adobe AIR. They recently released version 2.6 along with a new version of Flash ...</p>
			</article>

			<article class="odd">
				<h2><a href="/handling-sms-messages-with-php-through-e-mail-piping/">Handling SMS Messages with PHP through E-mail Piping</a></h2>
				<small><span class="label">September 5th, 2010</span></small>
				<p>I have been throwing around a few ideas lately to automate certain tasks via my cell phone. Let's say that I'm in need of some information stored in a database on my webserver, or maybe I just want to upload ...</p>
			</article>

			<article class="even">
				<h2><a href="/importing-orders-into-magento-from-a-csv-using-php/">Importing Orders Into Magento from a CSV Using PHP</a></h2>
				<small><span class="label">September 5th, 2010</span></small>
				<p>Magento is a wonderful open source eCommerce platform written in PHP that provides are rich inventory management system that lacks a few features that if implemented and save time and money. One of those features missing is the ability to ...</p>
			</article>

			<article class="odd">
				<h2><a href="/import-user-list-from-excel-file-into-net-membership-provider/">Import User List from Excel File into .Net Membership Provider</a></h2>
				<small><span class="label">September 2nd, 2010</span></small>
				<p>So you have an existing ASP.NET Membership Provider and now your just got handed a Excel file full of username's and passwords. Today I was faced with the task of writing a simple import script to take users from a ...</p>
			</article>

			<article class="even">
				<h2><a href="/retrieving-user-profile-data-in-vb-net-from-sharepoint/">Retrieving User Profile Data in VB.NET from SharePoint</a></h2>
				<small><span class="label">September 1st, 2010</span></small>
				<p>A recent project I begin working on required me to pull data from SharePoint MySite User Profiles. Luckily Microsoft provided a great way to access the fields in user profiles. In this post I will croncical how to leverage VB.NET ...</p>
			</article>

			<article class="odd">
				<h2><a href="/user-controls-in-sharepoint/">Creating User Controls in Microsoft SharePoint Master Pages</a></h2>
				<small><span class="label">August 25th, 2010</span></small>
				<p>If you find the need to extend SharePoint by including a Web User Control then you are in the right place. I scoured the web looking for a good tutorial for creating User Controls for SharePoint and found many of ...</p>
			</article>

		</div>

	</div>

</div>

@endsection
