<!DOCTYPE html>
<html lang="en">
<head>
	@stack('css')
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<title>AIP | Demos @hasSection('title') | @yield('title') @else @endif</title>
	@stack('custom-head')
</head>
@yield('body')
</html>