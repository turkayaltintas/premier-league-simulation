<html>
<head>
    @include('includes.head')
</head>
<body class="">
<div class="jumbotron text-center" style="max-width:100%;">
    <div class="text-center">
        <img width="100" src="{{asset('images/logo.png')}}">
    </div>
    <div>
        <h1 style="color: #38003c;">Premier League</h1>
    </div>

    <table class="table text-center">
        <tr>
            <td>
                <button class="btn btn-success" id="play-all">Play All Weeks</button>
            </td>
            <td>
                <button class="btn btn-secondary" id="play-all">Generate Fixtures</button>
            </td>
            <td>
                <button class="btn btn-danger" id="reset">Reset Fixture</button>
            </td>
        </tr>
    </table>
</div>
<div id="main" class="row" style="margin:0px;">
    @yield('content')
</div>
@include('includes.footer')
</body>
</html>
