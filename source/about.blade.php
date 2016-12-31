<!--
{{ $page_title = 'About' }}
{{ $page_body_class = 'page-about' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'About Me', 'sub_txt' => 'I like road trips, being outdoors, and hanging out with my wife and son.'])

<div class="container">

	<div class="row">

		<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">

			<p>I'm a multi-talented professional with a knack for entrepreneurship. I started my career developing websites for local businesses in the late 90s when I was ten years old. I love technology and love to follow emerging trends in data science, machine learning, programming, security, and distributed computing. I'm a big advocate of open source culture and challenge myself to contribute code to OSS projects when I find time.</p>

			<section class="subtle-brags">

				<h3>Entrepreneurial Endeavors</h3>

				<ul>
					<li>I started the first 56k dialup internet service provider (TegNet) in small rural Nebraska town I grew up in.</li>
					<li>With the help of some good high school friends we built a wireless internet service provider (<a href="https://www.youtube.com/watch?v=PIhM-VdUpXU" target="_blank">Split-Wire</a>) and serviced rural areas in Nebraska and Kansas. Business was later sold and is still operational under a different name.</li>
					<li>Built a cross-platform mobile application (Footwork) to help political campaign managers, non-profits, and salesmen keep track of their ground operations.</li>
				</ul>

			</section>

			<section class="subtle-brags">

				<h3>Recent Work History</h3>
			
				<p>I currently <a href="http://www.linkedin.com/in/tegansnyder/" target="_blank">work</a> for <a href="http://www.3m.com" target="_blank">3M</a> where I'm the core architect responsible for tooling and self-service applications that used to conduct eCommerce market assesments, evaluate eCommerce product landscapes, and evaluate eCommerce channels. Prior to 3M I was the CTO for <a href="http://www.bulubox.com">Bulu Box</a>, the first vitamin, supplement and general health sample box. As the CTO of Bulu Box I managed the development of Bulu Box's e-commerce applications, subscription billing services, cloud-based infrastructure and technology stack. Prior to Bulu Box I worked as a Web Architect at <a href="http://www.nppd.com">Nebraska Public Power District</a>, Nebraska’s largest electric utility, where I developed <a href="http://www.slideshare.net/TeganSnyder" target="_blank">web applications, mobile applications</a> and integrated solutions on both the front-end and back-end.</p>

			</section>

		</div>

		<div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">

			<img title="Tegan Snyder" src="/img/tegan_canoe.jpg" class="img-fit" alt="Tegan Snyder">

			<br><br>

				<div class="row">

					<div class="col-xs-12 col-sm-8 col-md-10 col-lg-10">

						<p>In my spare time I like to spend time with my wife and son. I also like to read about science, philosophy, and technology. I enjoy the outdoors, especially time on the water or a hike through the woods. You can find me on twitter <a href="http://www.twitter.com/tegansnyder" target="_blank">@tegansnyder</a>.</p>

					</div>

					<div class="col-xs-12 col-sm-4 col-md-2 col-lg-2">

						<p>
						<a href="http://www.magentocommerce.com/certification/directory/dev/1532912/"><img src="/img/developer_plus.png" border="0"></a>
						</p>

					</div>

				</div>

		</div>

	</div>

</div>

<div class="callout-banner">

	<div class="container">

		<div class="row">

			<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 no-pad-l">

				<section class="boxed-in">
					<h3>Specialities</h3>
					<ul>
						<li>eCommerce Market Analysis</li>
						<li>Web Application Development</li>
						<li>Automation and Integration</li>
						<li>eCommerce Platforms and Tooling</li>
						<li>Backend Programming</li>
					</ul>
				</section>

			</div>

			<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 no-pad-l">

				<section class="boxed-in">

					<div class="row">

						<h3>Key Technologies</h3>

						<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 no-pad-l">

							<ul>
								<li>Apache</li>
								<li>PHP</li>
								<li>Magento</li>
								<li>MySQL</li>
								<li>RabbitMQ</li>
							</ul>

						</div>

						<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 no-pad-l">

							<ul>
								<li>Rundeck</li>
								<li>Gearman</li>
								<li>Redis</li>
								<li>Docker</li>
								<li>Javascript</li>
							</ul>

						</div>

						<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 no-pad-l">

							<ul>
								<li>SASS / CSS</li>
								<li>RHEL / CentOS</li>
								<li>Apache</li>
								<li>Ngnix</li>
								<li>Git</li>
							</ul>

						</div>

						<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 no-pad-l">

							<ul>
								<li>Elasticsearch</li>
								<li>Kibana</li>
								<li>Logstash</li>
								<li>Bash</li>
								<li>HTML</li>
							</ul>

						</div>

					</div>

				</section>

			</div>

		</div>

	</div>

</div>


@endsection
