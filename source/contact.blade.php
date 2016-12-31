<!--
{{ $page_title = 'Contact' }}
{{ $page_body_class = 'page-contact' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'Contact Me', 'sub_txt' => 'Feel free to give me a call or shoot me an email. I would love to hear from you.'])

<div class="container">

	<div class="row">

		<div class="col-xs-12 col-sm-9 col-md-8 col-lg-8 no-pad-l">

			<form class="well form-horizontal" action="/contact-submit" method="post" id="contact_form">

				<fieldset>

					<div class="form-group">
						<label class="col-md-4 control-label">First Name</label>  
						<div class="col-md-4 inputGroupContainer">
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
								<input  name="first_name" placeholder="First Name" class="form-control"  type="text">
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label" >Last Name</label> 
						<div class="col-md-4 inputGroupContainer">
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
								<input name="last_name" placeholder="Last Name" class="form-control"  type="text">
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label">E-Mail</label>  
						<div class="col-md-4 inputGroupContainer">
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
								<input name="email" placeholder="E-Mail Address" class="form-control"  type="text">
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label">Phone #</label>  
						<div class="col-md-4 inputGroupContainer">
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
								<input name="phone" placeholder="(845)555-1212" class="form-control" type="text">
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label">Website or domain name</label>  
						<div class="col-md-4 inputGroupContainer">
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-globe"></i></span>
								<input name="website" placeholder="Website or domain name" class="form-control" type="text">
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label">Project Description</label>
						<div class="col-md-4 inputGroupContainer">
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
								<textarea class="form-control" name="comment" placeholder="Project Description"></textarea>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label"></label>
						<div class="col-md-4">
							<button type="submit" class="btn btn-warning" >Send <span class="glyphicon glyphicon-send"></span></button>
						</div>
					</div>

				</fieldset>
			</form>

		</div>

		<div class="col-xs-12 col-sm-3 col-md-4 col-lg-4 no-pad-l">

			<div class="github-card" data-github="tegansnyder" data-width="400" data-height="150" data-theme="default"></div>
			<script src="//cdn.jsdelivr.net/github-cards/latest/widget.js"></script>

	      	<h3>Other ways to reach me</h3>
	      	<p>Don't feel like filling out the form. Try me on my phone number, email, or social networks below.</p>
	      	<p>
		      	<strong>By Phone:</strong> <a href="https://clarity.fm/tegansnyder/precall?c=t&utm_source=blog_widget_request&utm_medium=blog_widget_request&utm_term=widget_tegansnyder_5706&utm_campaign=blog_widget_request" target="_blank">Request a Call</a><br>
			</p>

			<a href="http://www.linkedin.com/in/tegansnyder" target="_blank"><img class="bio-social" src="/img/linkedin.png" alt="LinkedIn" width="20" height="20"></a><a href="https://www.facebook.com/teg.snyder" target="_blank"><img class="bio-social" src="/img/facebook.png" alt="Facebook" width="20" height="20"></a><a href="http://www.twitter.com/tegansnyder" target="_blank"><img class="bio-social" src="/img/twitter.png" alt="Twitter" width="20" height="20"></a>


		</div>

	</div>

</div>


@endsection
