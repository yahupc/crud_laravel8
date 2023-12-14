@extends('layouts.app')
@section('content')
<div class="container">

    Formulario de creación de empleado
    <form action="{{ url('/empleado') }}" method="post" enctype="multipart/form-data">
        @csrf
        @include('empleado.form', ['modo' => 'Crear'])
    </form>
</div>
@endsection
