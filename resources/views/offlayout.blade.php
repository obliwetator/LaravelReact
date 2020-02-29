<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="description" content="Summoner look up tool for LoL">
	<meta name="keywords" content="League of Legends, Summoner">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta id="csrf-token" name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title', 'Home')</title>
	{{-- Complied with laravel mix. compiles bootstrap css --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
	<link rel="stylesheet" type="text/css" href="css/upload.css"

	{{-- Dark Mode --}}
	<link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
	<link rel="stylesheet" type="text/css" href="/css/tipped.css"/>

	<style>.container {
		display: flex;
		flex-direction: column;
		font-family: sans-serif;
	  }
	  
	  .container > p {
		font-size: 1rem;
	  }
	  
	  .container > em {
		font-size: .8rem;
	  }
	  
	  .dropzone {
		flex: 1;
		display: flex;
		flex-direction: column;
		align-items: center;
		padding: 20px;
		border-width: 2px;
		border-radius: 2px;
		border-color: #eeeeee;
		border-style: dashed;
		background-color: #fafafa;
		color: #bdbdbd;
		outline: none;
		transition: border .24s ease-in-out;
	  }
	  
	  .dropzone:focus {
		border-color: #2196f3;
	  }
	  
	  .dropzone.disabled {
		opacity: 0.6;
	  }
	  </style>

{{-- <link
  rel="stylesheet"
  href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
  integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
  crossorigin="anonymous"
  id="bootstrapLink"
  /> --}}
</head>

<body>
	<div id="index"></div>
	
</body>
	{{-- Complied with laravel mix. Takes all dependencies and combines them all into 1 big js file --}}
	<script src={{ mix('/js/upload.js') }}></script>

	{{-- Custom js the will be used across all pages --}}
	{{-- <script src="{{ asset('js/custom/js.js')}}"></script> --}}
	@yield('js')
	{{-- Here will go custom js from different views --}}
</html>