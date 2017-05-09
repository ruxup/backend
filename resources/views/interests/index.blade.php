<html>
<head><title>Interests</title></head>
<body>
<div>


    @if(count($interests) != 0)
        {
        @foreach($interests as $interest)
            {!! $interest !!}
        @endforeach
        }
    @else
        <div>Not available</div>
    @endif
        <a href="{!! url(route('login')) !!}">Stay on this page</a>
</div>
</body>
</html>
