<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="UTF-8">
	<meta name="description" content="Summoner look up tool for LoL">
	<meta name="keywords" content="League of Legends, Summoner">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title', 'Home')</title>
	{{-- Complied with laravel mix. compiles bootstrap css --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">

	{{-- Self made dependencies --}}

	{{-- CSS --}}
	{{-- Sprite CSS for champion icons --}}

	<link rel="stylesheet" type="text/css" href="{{ asset('css/champion120px.css') }}">
	{{-- Sprite CSS for items --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('css/itemSprite64.css')}}">
	{{-- Custom CSS  --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('css/customcss.css')}}">
	{{-- Dark Mode --}}
	<link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
	<link rel="stylesheet" type="text/css" href="/css/tipped.css"/>

	

<link
  rel="stylesheet"
  href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
  integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
  crossorigin="anonymous"
  id="bootstrapLink"
  />
</head>


<body>
	<div id="root"></div>
</body>
	{{-- Complied with laravel mix. Takes all dependencies and combines them all into 1 big js file --}}
	<script src={{ mix('js/app.js') }}></script>

	{{-- Custom js the will be used across all pages --}}
	{{-- <script src="{{ asset('js/custom/js.js')}}"></script> --}}
	@yield('js')
	{{-- Here will go custom js from different views --}}
</html>