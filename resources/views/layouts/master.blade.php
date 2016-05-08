<!DOCTYPE html>
<html>
<head>
	@stack('css')
	<meta charset="utf-8">
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<title>AIP | Demos @hasSection('title') @yield('title') @else @endif</title>
</head>
<body>
	@yield('body-content')
	@stack('scripts')
</body>
</html>