@if (isset($errors))
    @if ($errors->count() > 0)
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>

            @if($errors->count() == 1)
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            @else
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif
@endif
