<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Contacts</title>

        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <script src="{{ mix('/js/app.js') }}" defer></script>
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
        
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous" defer></script>

        @stack('scripts')
        <script type="text/javascript" defer>
                window.addEventListener("load",()=> closeLoadOverlay());
        </script>

    </head>
    <body class="antialiased">
        
        {{-- Spinner de carregamento --}}
        <div id="load-overlay" class="overlay" style="display: block">
            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
         </div>

        {{-- conteudo   --}}
        <div id="app" >
            @yield('content')
        </div>     
</html>

