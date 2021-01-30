@extends('layouts.app')
@section('content')
    <section class="section">
        <div class="container">
            <index :cooldown="{{ config('app.synchronize_cooldown_seconds') }}"></index>
        </div>
    </section>
@endsection

