
    <ol class="breadcrumb">
        <li>
            <a href="/">Home</a>
        </li>
        @if(isset($breadcrumbs))
            @foreach($breadcrumbs as $url => $name)

                <li class="{{$loop->last ? ' active' : ''}}" @if($loop->last) aria-current="page"@endif>

                    @if($loop->last || is_numeric($url))
                        {{$name}}
                    @else
                        <a href="{{$url}}">{{$name}}</a>
                    @endif
                </li>
            @endforeach
        @endif
    </ol>
