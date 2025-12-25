@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ $receipt->subject }}</h2>
    <hr>
    <div style="line-height:1.6;">
        {!! $receipt->body !!}
    </div>
</div>
@endsection
