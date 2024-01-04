## Extensiones vscode
- Bootstrap v4 snippets
- Laravel Snippets
## Instalacion
`composer create-project laravel/laravel:^8.0 example-app`
`create database crud_laravel8`
`php artisan migrate`
- Creando modelo Controlador Recurso
`php artisan make:model Empleado -mcr
- En nuestros archivo de migracion creado creamos nuestra tabla
```php
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('Nombre');
            $table->string('ApellidoPaterno');
            $table->string('ApellidoMaterno');
            $table->string('Correo');
            $table->string('Foto');
            $table->timestamps();
        });
    }
```
## Vistas
![](/.readme_img/Pasted%20image%2020231124164353.png)
- En el archivo routes/web.php, añadimos 
```php
Route::get('/empleado', function () {
    return view('empleado.index');
});
```
## Controlador
- En nuestro contrlador **EmpleadoController.php**, encontraremos todos los metodos necesarios para nuestro crud.
	- index
	- store
	- create
		- En este metodo agregaremos la logica para crear un nuevo empleado y para ello usaremos la vista siguiente.
		`return view('empleado.create');`
	- show
	- edit
	- update
	- destroy
- En nuestra archivo route/web.php, podemos agregar la siguiente ruta **resource** que tiene agrupadas el conjunto de rutas con su correspondiente metodo del controlador.
	`Route::resource('empleado', EmpleadoController::class);` 
- Podemos revisar la totalidad de las rutas con el comando en consola:
	`php artisan route:list` ó
	`php artisan r:l`
![](/.readme_img/Pasted%20image%2020231124175632.png)
## Formulario:  
- archivo **views/empleado/index.php**.
```php
Formulario de creación de empleado
<form action="" method="post" enctype="multipart/form-data">
    <label for="Nombre">Nombre</label>
    <input type="text" name="Nombre" id="Nombre">
    <br>
    <label for="Nombre">Apellido Paterno</label>
    <input type="text" name="ApellidoPaterno" id="ApellidoPaterno">
    <br>
    <label for="ApellidoMaterno">Apellido Materno</label>
    <input type="text" name="ApellidoMaterno" id="ApellidoMaterno">
    <br>
    <label for="Correo">Correo</label>
    <input type="text" name="Correo" id="Correo">
    <br>
    <label for="Foto">Foto</label>
    <input type="file" name="Foto" id="Foto">
    <br>
    <input type="submit" name="Enviar" id="Enviar">
    <br>
</form>
```
## Enviar datos de formulario:  
- Para esto debemos de indicar al formulario que accion realizar, en este caso indicamos que todo los datos del formulario lo enviaremos a la url: /empleado con el metodo post.
- Ademas debemos de añadir seguridad con la notacion @csrf ( para indicar que los datos son enviados desde el mismo formulario)
```php
Formulario de creación de empleado
<form action="{{ url('/empleado') }}" method="post" enctype="multipart/form-data">
    @csrf
    <label for="Nombre">Nombre</label>
    <input type="text" name="Nombre" id="Nombre">
    <br>
    <label for="Nombre">Apellido Paterno</label>
    <input type="text" name="ApellidoPaterno" id="ApellidoPaterno">
    <br>
    <label for="ApellidoMaterno">Apellido Materno</label>
    <input type="text" name="ApellidoMaterno" id="ApellidoMaterno">
    <br>
    <label for="Correo">Correo</label>
    <input type="text" name="Correo" id="Correo">
    <br>
    <label for="Foto">Foto</label>
    <input type="file" name="Foto" id="Foto">
    <br>
    <input type="submit" name="Enviar" id="Enviar">
    <br>
</form>
```
- Desde el controlador EmpleadoController.php, el metodo que recibe la ruta anteriormente descrita , es el metodo **store**, eso lo podemos constatar aqui.
![](/.readme_img/Pasted%20image%2020231124185238.png)
```php
    public function store(Request $request)
    {
        $datosEmpleado = request()->all();
        return response()->json($datosEmpleado);
    }
```
![](/.readme_img/Pasted%2020image%20231124185333.png)
![](/.readme_img/Pasted%2020image%20231124185351.png)
> Esta es la respuesta en formato json de lo que enviaremos por el formulario. Como ven hay un token producido como seguridad por csrf.

- Para que los datos sean enviados a nuestra base de datos realizamos las siguientes correcciones a nuestro controlador.
```php
    public function store(Request $request)
    {
        #$datosEmpleado = request()->all();
        $datosEmpleado = request()->except('_token');
        Empleado::insert($datosEmpleado);
        return response()->json($datosEmpleado);
    }
```
> Aqui enviamos todos los datos excepto el token generado. Aplicamos el metodo insert de nuestro modelo creado. Vemos que nuestros datos fueron guardados satisfactoriamente.
![](/.readme_img/Pasted%20image%2020231124210316.png)
### Corregir el guardado de la foto:
- Como se ve en la imagene anterior, la ruta guardada de nuestra foto, es una ruta temporal(tmp). Para corregir ello , realizamos lo siguiente.
![](/.readme_img/Pasted%20image%2020231126122454.png)
- Añadimos el codigo mostrado en el recuadro, donde revisamos en nuestro request, si contiene una foto, para luego guardarla en la carpeta storage/app/public/uploads (creamos la carpeta uploads manualmente).
- Una vez que llenamos nuestro formulario desde el navegador , obtendremos el registro correcto en la base de datos y en nuestra carpeta creada.
	- ![](/.readme_img/Pasted%20image%2020231126123453.png)
## Consultar datos:
- En nuestro archivo **index.blade.php** , agregamos el siguiente codigo para consultar los datos que tenemos almacenados en nuestra tabla **empleado**s.
```php
Mostrar la lista de empleados
<table class="table table-light">
    <thead>
        <tr>
            <th>#</th>
            <th>Foto</th>
            <th>Nombre</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Correo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($empleados as $empleado)
        <tr>
            <td>{{ $empleado->id }}</td>
            <td>{{ $empleado->Foto }}</td>
            <td>{{ $empleado->Nombre }}</td>
            <td>{{ $empleado->ApellidoPaterno }}</td>
            <td>{{ $empleado->ApellidoMaterno }}</td>
            <td>{{ $empleado->Correo }}</td>
            <td>Editar | Borrar</td>
        </tr>
        @endforeach
    </tbody>

</table>
```
![](/.readme_img/Pasted%20image%2020231126132442.png)
## Borrar datos:  
- Creamos un formulario el cual tendra un boton  que al hacer clic en el, indicara la url del empleado con su id a eliminar.
- En la vista anterior index.blade.php, añadimos el siguiente codigo (que reemplazará la palabra Borrar por un boton).
- Consultamos la url que tendra nuestra accion, lo consultamos con **php artisan r:l** , con ello obtenemos el metodo **DELETE** que tiene que ser aplicado al formulario para que ejecute el metodo **destroy** del controlador, el cual hace la eliminacion del empleado correspondiente.
![](/.readme_img/Pasted%20image%2020231126141017.png)
```php
            <td>Editar | 
            <form action="{{ url('/empleado/'. $empleado->id ) }}" method="post">
                @csrf 
                {{ method_field('DELETE')}}
                <input type="submit" onclick="return confirm('Quieres borrar?')" value="Borrar">
            </form>
            </td>
```
- En el controlador **EmpleadoController.php**. Despues de pasar el id del empleado a eliminar, retornamos a nuestra ruta previa.
```php
    public function destroy($id)
    {
        Empleado::destroy($id);
        return redirect('empleado');
    }
```
## Incluir formulario:  
- Tanto en las vistas de creacion como de edicion de un empleados comparten el mismo formulario , para ello podemos crear una plantilla que luego pueda ser incluida en ambas vistas.
- creamos la vista **form.blade.php**
```php
<label for="Nombre">Nombre</label>
    <input type="text" name="Nombre" id="Nombre">
    <br>
    <label for="Nombre">Apellido Paterno</label>
    <input type="text" name="ApellidoPaterno" id="ApellidoPaterno">
    <br>
    <label for="ApellidoMaterno">Apellido Materno</label>
    <input type="text" name="ApellidoMaterno" id="ApellidoMaterno">
    <br>
    <label for="Correo">Correo</label>
    <input type="text" name="Correo" id="Correo">
    <br>
    <label for="Foto">Foto</label>
    <input type="file" name="Foto" id="Foto">
    <br>
    <input type="submit" value="Guardar" id="Enviar">
    <br>
```
- Luego en la vista de creacion incluir esta porcion de codigo con :  
	`@include('empleado.form')`
## Formulario Edicion
- Objetivo: Cargar los datos al formulario de edicion.
- En el controlador **edit.blade.php**, pasamos el empleado con su respectivo id
```php
    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);
        return view('empleado.edit', compact('empleado'));
    }
```

- Modificamos nuestro **form.blade.php** (que sera mostrado en la vista edit.blade.php) para que encuentre los datos del empleado por su id.
```php
<label for="Nombre">Nombre</label>
<input type="text" name="Nombre" id="Nombre" value="{{ $empleado->Nombre }}">
<br>
<label for="Nombre">Apellido Paterno</label>
<input type="text" name="ApellidoPaterno" id="ApellidoPaterno" value="{{ $empleado->ApellidoPaterno }}">
<br>
<label for="ApellidoMaterno">Apellido Materno</label>
<input type="text" name="ApellidoMaterno" id="ApellidoMaterno" value="{{ $empleado->ApellidoMaterno }}">
<br>
<label for="Correo">Correo</label>
<input type="text" name="Correo" id="Correo" value="{{ $empleado->Correo }}">
<br>
<label for="Foto">Foto</label>
{{ $empleado->Foto }}
<input type="file" name="Foto" id="Foto">
<br>
<input type="submit" value="Guardar" id="Enviar">
<br>
```
## Guardar Edicion:  
- Modificaremos nuestra vista de edicion. **edit.blade.php**
```php
<form action="{{ url('/empleado/'.$empleado->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    {{ method_field('PATCH')}}
    @include('empleado.form')
<form>
```
- Modificar el metodo **update** del controlador, donde usaremos el $id del empleado que enviamos desde la vista.
```php
    public function update(Request $request, $id)
    {
        //
        $datosEmpleado = request()->except(['_token','_method']);
        Empleado::where('id', '=', $id)->update($datosEmpleado);
        $empleado = Empleado::findOrFail($id);
        return view('empleado.edit', compact('empleado'));
    }
```
> En este codigo estamos guardando todos los datos del formulario, excepto "_token", que es generado por @csrf y "_method", que es generado por {{ method_field}} desde la vista.

### Mostrar foto
- En nuestra vista , agregamos en la celda de la foto la siguiente instruccion:
```blade
            <td>
                <img src="{{ asset('storage').'/'.$empleado->Foto}}" alt="" width="10%" height="10%" >
            </td>
```
> Para que este codigo funcione tenemos que realizar hacer un enlace directo de la carpeta storage a la zona publica de la aplicacion.
> `php artisan storage:link`
- Ruta Original de storage:
![](/.readme_img/Pasted%20image%2020231127104929.png)
- Enlace directo de storage a zona publica:
![](/.readme_img/Pasted%20image%2020231127104811.png)

### Borrar foto:
- Actualizamos el metodo update de nuestro controlador, donde revisaremos si la actualizacion tiene un cambio de foto, de tenerla, eliminamos la foto anterior y guardamos la actual.
```php
    public function update(Request $request, $id)
    {
        //
        $datosEmpleado = request()->except(['_token','_method']);
        if($request->hasFile('Foto')){
            $empleado = Empleado::findOrFail($id);
            Storage::delete('public/'.$empleado->Foto);
            $datosEmpleado['Foto'] = $request->file('Foto')->store('uploads','public');
        }        

        Empleado::where('id', '=', $id)->update($datosEmpleado);
        $empleado = Empleado::findOrFail($id);
        return view('empleado.edit', compact('empleado'));
    }
```
> Para ello debemos usar: `use Illuminate\Support\Facades\Storage;`

## Ajustes al Formulario
- La vista **form.blade.php** es un formulario que compartimos en para crear y editar un Empleado. Y presenta inconvenientes cuando queremos usarlo para el primer caso , eso se debe a que no reconoce la variable $empleado, ya que eso solo lo utilizamos para obtener el objeto a actualizar. Para solucionar el inconveniente, haremos la siguiente correccion
```php
<label for="Nombre">Nombre</label>
<input type="text" name="Nombre" id="Nombre" value="{{ isset($empleado->Nombre) ? $empleado->Nombre: '' }}">
<br>
<label for="Nombre">Apellido Paterno</label>
<input type="text" name="ApellidoPaterno" id="ApellidoPaterno" value="{{ isset($empleado->ApellidoPaterno) ? $empleado->ApellidoPaterno : '' }}">
<br>
<label for="ApellidoMaterno">Apellido Materno</label>
<input type="text" name="ApellidoMaterno" id="ApellidoMaterno" value="{{ isset($empleado->ApellidoMaterno) ? $empleado->ApellidoMaterno : '' }}">
<br>
<label for="Correo">Correo</label>
<input type="text" name="Correo" id="Correo" value="{{ isset($empleado->Correo) ? $empleado->Correo :'' }}">
<br>
<label for="Foto">Foto</label>
@if(isset($empleado->Foto))
<img src="{{ asset('storage').'/'.$empleado->Foto}}" alt="" width="100" >
@endif
<input type="file" name="Foto" id="Foto">
<br>
<input type="submit" value="Guardar" id="Enviar">
<br>
```
> El comando **isset** verifica si que la variable han sido defiinidas , en este caso de encontrar valor para $empleado los muestra de lo contrario lo deja vacio.

## Links
- En el archivo **index.blade.php** donde estan todos los empleados, podemos crear un link que nos lleve al formulario de creacion.
	`<a href="{{ url('empleado/create')}}">Registrar empleado</a>`
- Del mismo modo en el formulario compartido para creacion | edicion (**form.blade.php**) podemos crear un link para que una vez completado con la tarea, regresar a nuestra lista de empleados.
	`<a href="{{ url('empleado/')}}">Regresar</a>`
## Borrar empleado(con foto incluida)
- Para ello debemos de hacer la siguiente modificacion en el metodo destroy
```php
    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);
        if(Storage::delete('public/'.$empleado->Foto)){
            Empleado::destroy($id);
        }
        return redirect('empleado');
    }
```
## Mensajes de Texto
- Los mensajes de texto son utilices por que despues de realizado una accion, nos informan si la tarea realizada se realizó con exito o no.
- Para ello debemos de agregar el siguiente codigo a nuestra vista principal que revisara si hay mensajes o no.
```php
@if(Session::has('mensaje'))
{{ Session::get('mensaje')}}
@endif
```
- Desde nuestro controlador debemos de enviar el mensaje a mostrar
- En nuestro metodo store
	`return redirect('empleado')->with('mensaje','Empleado guardado con exito.');`
- En nuestro metodo destroy
	`return redirect('empleado')->with('mensaje','Empleado Borrado.');
## Mensaje entre vistas
En estas dos vistas que tienen incluida otra en comun(form.blade.php), podemos pasar parametros distintos para poder distingarlas
![](/.readme_img/Pasted%20image%2020231127181345.png)
![](/.readme_img/Pasted%20image%2020231127181418.png)
> Este parametro es consumido en la vista incluida.

![](/.readme_img/Pasted%20image%2020231127181641.png)
De esta Forma a pesar de compartir un mismo formulario para este caso. Es personalizable de acuerdo al caso de Creacion o Edicion.
![](/.readme_img/Pasted%20image%2020231127181832.png)
![](/.readme_img/Pasted%20image%2020231127181905.png)
1.55
## Bootstrap y login
`composer require laravel/ui:^3.4`
> Esto si estan usando laravel 8 (version anterior), version actual 10
- Ejecutamos los siguientes comandos:
```php
php artisan ui bootstrap --auth
npm install
npm run dev
```

- Agregamos estas lineas para poder aplicar caracteristicas de bootstrap , en las siguientes vistas
	- ../empleado/index.blade.php
	- ../empleado/create.blade.php
	- ../empleado/edit.blade.php
```php
@extends('layouts.app')
@section('content')
<div class="container">
.
.
.

</div>
@endsection
```

- En el archivo de rutas, modificamos:
```php
.
.
.
Auth::routes(['register' => false, 'reset' => false]);

// Route::get('/home', [EmpleadoController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [EmpleadoController::class, 'index'])->name('home');
    //    Route::get('empleado/create', [EmpleadoController::class, 'create']);
    Route::resource('empleado', EmpleadoController::class);
});
```
> Con esto quitamos las rutas de registro y la de resetear clave, además brindamos un capa de seguridad para que solo un usuario logeado pueda editar y modificar los empleados.

## Estilos a formularios
- Para el estilo de los formularios, solo tenemos que agregar unas caracteristicas de bootstrap para darle el estilo correspondiente a los inputs,  *class = "form-group", class="form-control"*,  para los botones: *class = "btn btn-primary"*

```html
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
    <!--    <label for="Foto">Foto</label> -->
    @if(isset($empleado->Foto))
    <img class="img-thumbnail img-fluid" src="{{ asset('storage').'/'.$empleado->Foto}}" alt="" width="100">
    @endif
    <input type="file" class="form-control" name="Foto" id="Foto">
</div>
<br>
<input type="submit" class="btn btn-success" value="{{ $modo }} datos" id="Enviar">
<a class="btn btn-primary" href="{{ url('empleado/')}}">Regresar</a>
```
![](/.readme_img/Pasted%20image%2020231222194704.png)
## Validar formulario
- Desde el controlador, *EmpleadoController.php*:
```php
$campos = [
            'Nombre' => 'required|string|max:100',
            'ApellidoPaterno' => 'required|string|max:100',
            'ApellidoMaterno' => 'required|string|max:100',
            'Correo' => 'required|email',
            'Foto' => 'required|max:10000|mimes:jpeg,png,jpg',
        ];
        $mensaje = [
            'required' => 'El :attribute es requerido',
            'Foto.required' => 'La foto requerida',
        ];
        $this->validate($request, $campos, $mensaje);
```
> Creamos dos variables donde en la primera establecemos las reglas de validacion y en la segunda los mensajes que se obtendra cuando ocurra un ($error) por incumplimiento de las mismas.

- En la vista *form.blade.php*
```php 
@if(count($errors)>0)
<div class="alert alert-danger" role="alert">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
```
> Esto nos permite obtener los errores de validación luego de llenar el formulario.

## Recuperando Información.
- Despues de agregar validacion a nuestro formulario , vemos que aparecen los errores, pero la informacion llenada previamente en el formulario se borra automaticamente, para evitar esto y completar los inputs con errores, utilizamos el siguiente codigo.
```php
<div class="form-group">
    <label for="Nombre">Nombre</label>
    <input type="text" class="form-control" name="Nombre" id="Nombre" value="{{ isset($empleado->Nombre) ? $empleado->Nombre:old('Nombre') }}">
</div>
```
> `$empleado->Nombre:old('Nombre')`, obtiene el valor del input previamente ingresado.
## Ocultar mensajes y redirrecionar:
- En nuestro controlador (EmpleadoController.php, metodo update) enviamos un mensaje al haber actualizado nuestro empleado satisfactoriamente, despues de haber pasado por las validaciones respectivas(inclusive si el usuario no actualiza su foto).
```php
    public function update(Request $request, $id)
    {
        //
        $campos = [
            'Nombre' => 'required|string|max:100',
            'ApellidoPaterno' => 'required|string|max:100',
            'ApellidoMaterno' => 'required|string|max:100',
            'Correo' => 'required|email',
        ];
        $mensaje = [
            'required' => 'El :attribute es requerido',
        ];
        
        
        $datosEmpleado = request()->except(['_token', '_method']);
        if ($request->hasFile('Foto')) {
            $empleado = Empleado::findOrFail($id);
            Storage::delete('public/' . $empleado->Foto);
            $datosEmpleado['Foto'] = $request->file('Foto')->store('uploads', 'public');

            #Validacion
            $campos = [
                'Foto' => 'required|max:10000|mimes:jpeg,png,jpg',
            ];
            $mensaje = [
                'Foto.required' => 'La foto requerida',
            ];
        }

        $this->validate($request, $campos, $mensaje);
        
        Empleado::where('id', '=', $id)->update($datosEmpleado);
        //$empleado = Empleado::findOrFail($id);
        //return view('empleado.index', $datos);
        return redirect('empleado')->with('mensaje', 'Empleado Modificado.');

    }
```
> Redireccionaremos a la pagina principal , enviando el mensaje de "Empleado Modificado").

- Desde la vista de destino **index.blade.php** obtenemos el mensaje y con codigo de bootstrap obtendremos el mensaje en forma de alerta , el cual podemos ocultarlo si deseamos.
```html
<div class="container">
    @if(Session::has('mensaje'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('mensaje')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    @endif
```
![](/.readme_img/Pasted%20image%2020231222201825.png)
## Menu navegación y Paginación:
- Con el siguiente codigo la siguiente vista **app.blade.php**, agregará un enlace que estara fijo en toda la aplicación. 

``` html
<div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('empleado.index') }}">{{ __('Empleados') }}</a>
                        </li>
                    </ul>
```
![](/.readme_img/Pasted%20image%2020240104111333.png)
- Para añadir paginacion en la pagina principal **index.blade.php** , agregamos la siguiente linea despues de la tabla.
```HTML
	...
	</table>
	{!! $empleados->links() !!}
	...
```

![](/.readme_img/Pasted%20image%2020240104112320.png)
> Esto nos agregara a la vista, las paginas enumeradas de acuerdo a como fue configurada en el controlador **EmpleadoController.php** , en su metodo **index**.

```php
    public function index()
    {
        $datos['empleados'] = Empleado::paginate(2);
        return view('empleado.index', $datos);
    }
```

---
