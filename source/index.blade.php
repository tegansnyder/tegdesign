<!--
{{ $page_title = 'Teg Design - The development diary and web musings of Tegan Snyder' }}
{{ $page_body_class = 'page-home' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'I enjoy what I do', 'sub_txt' => 'I automate things, integrate things and break things. This is my long-standing and often neglected website.'])

<section class="working-on">

	<div class="container">

		<div class="row">

			<h2>Some of my work</h2>

			<div class="container pad">

				<div class="row">
					<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 no-pad-l mb-pad-bottom">
						<a href="https://vimeo.com/channels/316318" target="_blank"><img src="/img/footwork_on.png" class="featured-img" alt="Footwork" border="0"></a>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 no-pad-l mb-pad-bottom">
						<a href="http://www.bulubox.com" target="_blank"><img src="/img/bulu_on.png" class="featured-img" alt="Bulu Box" border="0"></a>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 no-pad-l mb-pad-bottom">
						<a href="http://www.compengine.com" target="_blank"><img src="/img/compengine_on.png" class="featured-img" alt="Comp Engine" border="0"></a>
					</div>
				</div>

			</div>

		</div>

	</div>

</section>

<section class="working-on secondary">

	<div class="container">

		<div class="row">

			<h2>Magento Extensions</h2>

			<div class="container pad">

				<div class="row">

					<div class="container pad">

						<div class="row">
							<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 no-pad-l mb-pad-bottom">
								<a href="http://www.magentocommerce.com/magento-connect/popup-email-collector.html" target="_blank"><img src="/img/popupcollector_on.png" class="featured-img" alt="Popup Email Collector" border="0"></a>
							</div>
							<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 no-pad-l mb-pad-bottom">
								<a href="http://www.magentocommerce.com/magento-connect/advanced-analytics-with-mixpanel.html" target="_blank"><img src="/img/mixpanel_on.png" class="featured-img" alt="Mixpanel Magento" border="0"></a>
							</div>
							<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 no-pad-l mb-pad-bottom">
								<a href="https://github.com/tegansnyder/Magento-Recurring-Beta-Grid-Improvements" target="_blank"><img src="/img/recurring_profile_on.png" class="featured-img" alt="Mixpanel Magento" border="0"></a>
							</div>
						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

</section>

@endsection
