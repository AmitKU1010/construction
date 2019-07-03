<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html,body{background-color:#fff;background:url(http://marinosalonandspa.com/wp-content/uploads/bb-plugin/cache/Spa-panorama.jpg);background-size:cover;color:#636b6f;font-family:'Raleway', sans-serif;font-weight:100;height:100vh;margin:0;overflow:hidden}.full-height{height:100vh}.flex-center{align-items:center;display:flex;justify-content:center}.position-ref{position:relative}.top-right{position:absolute;right:10px;top:18px}.content{text-align:center}.title{font-size:84px;color:black}.links > a{color:#f98ca4;padding:0 25px;font-size:12px;font-weight:600;letter-spacing:.1rem;text-decoration:none;text-transform:uppercase}.m-b-md{margin-bottom:30px}html,body{width:100%;height:100%;background:-webkit-linear-gradient(80deg, #f4eba0 43%, #c0faca 43%);background:linear-gradient(10deg, #f4eba0 43%, #c0faca 43%)}h1{font-family:'CoreCircus', sans-serif;text-transform:uppercase;font-size:8vw;text-align:center;line-height:1;margin:0;top:50%;left:50%;-webkit-transform:translate(-50%, -50%);transform:translate(-50%, -50%);position:absolute;color:#f98ca4;text-shadow:-1px -1px 0 #6e1f58, 1px -1px 0 #6e1f58, -1px 1px 0 #6e1f58, 1px 1px 0 #6e1f58, 1px 0px 0px #65f283, 0px 1px 0px #65f283, 2px 1px 0px #65f283, 1px 2px 0px #65f283, 3px 2px 0px #65f283, 2px 3px 0px #65f283, 4px 3px 0px #65f283, 3px 4px 0px #65f283, 5px 4px 0px #65f283, 3px 5px 0px #6e1f58, 6px 5px 0px #6e1f58, -1px 2px 0 black, 0 3px 0 #6e1f58, 1px 4px 0 #6e1f58, 2px 5px 0px #6e1f58, 2px -1px 0 #6e1f58, 3px 0 0 #6e1f58, 4px 1px 0 #6e1f58, 5px 2px 0px #6e1f58, 6px 3px 0 #6e1f58, 7px 4px 0 #6e1f58, 10px 10px 4px #dac249}h1:after,h1:before{content:attr(data-heading);position:absolute;overflow:hidden;left:0;width:100%;top:0;z-index:5}h1:before{text-shadow:-1px -1px 0 #9e132c, 1px -1px 0 #9e132c, -1px 1px 0 #9e132c, 1px 1px 0 #9e132c, 1px 0px 0px #f5b10b, 0px 1px 0px #f5b10b, 2px 1px 0px #f5b10b, 1px 2px 0px #f5b10b, 3px 2px 0px #f5b10b, 2px 3px 0px #f5b10b, 4px 3px 0px #f5b10b, 3px 4px 0px #f5b10b, 5px 4px 0px #f5b10b, 3px 5px 0px #9e132c, 6px 5px 0px #9e132c, -1px 2px 0 black, 0 3px 0 #9e132c, 1px 4px 0 #9e132c, 2px 5px 0px #9e132c, 2px -1px 0 #9e132c, 3px 0 0 #2f3e9c, 4px 1px 0 #9e132c, 5px 2px 0px #9e132c, 6px 3px 0 #9e132c, 7px 4px 0 #9e132c, 10px 10px 4px rgba(106, 241, 119, 0.8);color:#65f283;height:66%}h1:after{height:33%;color:#4ad9db;text-shadow:-1px -1px 0 #2f3e9c, 1px -1px 0 #2f3e9c, -1px 1px 0 #2f3e9c, 1px 1px 0 #2f3e9c, 1px 0px 0px #f98ca4, 0px 1px 0px #f98ca4, 2px 1px 0px #f98ca4, 1px 2px 0px #f98ca4, 3px 2px 0px #f98ca4, 2px 3px 0px #f98ca4, 4px 3px 0px #f98ca4, 3px 4px 0px #f98ca4, 5px 4px 0px #f98ca4, 3px 5px 0px #2f3e9c, 6px 5px 0px #2f3e9c, -1px 2px 0 black, 0 3px 0 #2f3e9c, 1px 4px 0 #2f3e9c, 2px 5px 0px #2f3e9c, 2px -1px 0 #2f3e9c, 3px 0 0 #2f3e9c, 4px 1px 0 #2f3e9c, 5px 2px 0px #2f3e9c, 6px 3px 0 #2f3e9c, 7px 4px 0 #2f3e9c}@font-face{font-family:'CoreCircus2DDot1';src:url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/209981/333BF4_1_0.eot");src:url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/209981/333BF4_1_0.eot?#iefix") format("embedded-opentype"), url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/209981/333BF4_1_0.woff2") format("woff2"), url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/209981/333BF4_1_0.woff") format("woff"), url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/209981/333BF4_1_0.ttf") format("truetype")}@font-face{font-family:'CoreCircus';src:url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/209981/333BF4_8_0.eot");src:url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/209981/333BF4_8_0.eot?#iefix") format("embedded-opentype"), url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/209981/333BF4_8_0.woff2") format("woff2"), url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/209981/333BF4_8_0.woff") format("woff"), url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/209981/333BF4_8_0.ttf") format("truetype")}@font-face{font-family:'CoreCircusPierrot4';src:url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/209981/333BF4_13_0.eot");src:url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/209981/333BF4_13_0.eot?#iefix") format("embedded-opentype"), url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/209981/333BF4_13_0.woff2") format("woff2"), url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/209981/333BF4_13_0.woff") format("woff"), url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/209981/333BF4_13_0.ttf") format("truetype")}
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif
            <div class="content" style="margin-bottom: 30%">
                <h2>{{ 'Your Current IP is : '.$view_share['local_ip'] }}</h2>
                <h3>{{ $view_share['ip_message'] }}</h3>
            </div>
            <div class="content">
                {{-- <div class="title m-b-md"><i class="fa fa-area-chart"></i>
                    School ERP
                </div> --}}
                <h1 contenteditable data-heading="Construction Automation">Construction Automation</h1>
                {{-- <div class="links">
                    <a href="https://laravel.com/docs">Documentation</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">Login</a>
                    <a href="https://forge.laravel.com">Logout</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div> --}}
            </div>
        </div>
    </body>
</html>
