<h1>{{ $modo }} Empleado</h1>
<div class="form-group">
    <label for="Nombre">Nombre</label>
    <input type="text" class="form-control" name="Nombre" id="Nombre" value="{{ isset($empleado->Nombre) ? $empleado->Nombre: '' }}">
</div>
<div class="form-group">
    <label for="Nombre">Apellido Paterno</label>
    <input type="text" class="form-control" name="ApellidoPaterno" id="ApellidoPaterno" value="{{ isset($empleado->ApellidoPaterno) ? $empleado->ApellidoPaterno : '' }}">
</div>
<div class="form-group">
    <label for="ApellidoMaterno">Apellido Materno</label>
    <input type="text" class="form-control" name="ApellidoMaterno" id="ApellidoMaterno" value="{{ isset($empleado->ApellidoMaterno) ? $empleado->ApellidoMaterno : '' }}">
</div>
<div class="form-group">
    <label for="Correo">Correo</label>
    <input type="text" class="form-control" name="Correo" id="Correo" value="{{ isset($empleado->Correo) ? $empleado->Correo :'' }}">
</div>
<div class="form-group">
    <!--    <label for="Foto">Foto</label> -->
    @if(isset($empleado->Foto))
    <img class="img-thumbnail img-fluid" src="{{ asset('storage').'/'.$empleado->Foto}}" alt="" width="100">
    @endif
    <input type="file" class="form-control" name="Foto" id="Foto">
</div>
<br>
<input type="submit" class="btn btn-success" value="{{ $modo }} datos" id="Enviar">

<a class="btn btn-primary" href="{{ url('empleado/')}}">Regresar</a>
