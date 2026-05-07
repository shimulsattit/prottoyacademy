<!DOCTYPE HTML>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<title>We are coming soon - {{ get_settings('system_name') ?? 'Prottoy Academy' }}</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="UTF-8">
	
	<!-- Font -->
	<link rel="shortcut icon" href="{{ asset(get_settings('system_favicon') ?? 'portal-resource/images/favicon.ico') }}" />
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700%7CPoppins:400,500" rel="stylesheet">
	
	
	<link href="{{ asset('coming/css/ionicons.css') }}" rel="stylesheet">
	<link href="{{ asset('coming/css/jquery.classycountdown.css') }}" rel="stylesheet" />
	<link href="{{ asset('coming/css/styles.css') }}" rel="stylesheet">
	<link href="{{ asset('coming/css/responsive.css') }}" rel="stylesheet">
</head>
<body>
	<div class="main-area-wrapper" style="background-image:url({{ asset('coming/images/background.jpg') }});">
		<div class="main-area center-text" >
			<div class="display-table">
				<div class="display-table-cell">
                    
					<img src="{{ get_settings('system_logo') }}" alt="{{ get_settings('system_name') }}" style="width: 250px;">
					<h1 class="title"><b>Prottoy Academy</b></h1>
					<p class="desc font-white">Our website is currently building. We Should be back shortly. Thank you for your patience.</p>
					
					<div id="normal-countdown" data-date="2025/04/14"></div>
					
					<a class="notify-btn" href="#"><b>NOTIFY US</b></a>
					
					<ul class="social-btn">
						<li class="list-heading">Follow us for update</li>
						
						@if (get_settings('system_facebook_url'))
							<li>
								<a href="{{ get_settings('system_facebook_url') }}" target="_blank">
									<i class="ion-social-facebook"></i>
								</a>
							</li>
						@endif

						@if (get_settings('system_twitter_url'))
							<li>
								<a href="{{ get_settings('system_twitter_url') }}" target="_blank">
									<i class="ion-social-twitter"></i>
								</a>
							</li>
						@endif
						
						@if (get_settings('system_instagram_url'))
							<li>
								<a href="{{ get_settings('system_instagram_url') }}" target="_blank">
									<i class="ion-social-instagram-outline"></i>
								</a>
							</li>
						@endif
						
						@if (get_settings('system_linkedin_url'))
							<li>
								<a href="{{ get_settings('system_linkedin_url') }}" target="_blank">
									<i class="ion-social-linkedin-outline"></i>
								</a>
							</li>
						@endif
						
						@if (get_settings('system_youtube_url'))
							<li>
								<a href="{{ get_settings('system_youtube_url') }}" target="_blank">
									<i class="ion-social-youtube"></i>
								</a>
							</li>
						@endif

					</ul>
					
				</div>
			</div>
		</div>
	</div>
	
	<script src="{{ asset('coming/js/jquery-3.1.1.min.js') }}"></script>
	<script src="{{ asset('coming/js/jquery.countdown.min.js') }}"></script>
	<script src="{{ asset('coming/js/scripts.js') }}"></script>
	
</body>
</html>
