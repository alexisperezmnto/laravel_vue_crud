<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Customer::orderBy('id', 'DESC')->with('services')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'nombre' => 'required|string|min:2|max:50',
            'imagen' => 'file|mimes:jpeg,jpg,png,gif|max:2048',
            'cedula' => 'required|string|min:5|max:15',
            'email' => 'required|string|unique:users,email',
            'telefono' => 'required|string|min:5|max:20',
            'observaciones' => 'required|min:10|max:500',
            'servicios' => 'required'
        ],
        [
            'imagen.file' => 'Sólo se aceptan imágenes jpeg,jpg,png,gif',
            'imagen.mimes' => 'Sólo se aceptan imágenes jpeg,jpg,png,gif',
            'imagen.max' => 'La imagen debe pesar máximo 2 MB',

            'nombre.required' => 'El campo nombre es obligatorio',
            'nombre.min' => 'El campo nombre debe tener mínimo 2 caracteres',
            'nombre.max' => 'El campo nombre debe tener máximo 50 caracteres',

            'cedula.required' => 'El campo cédula es obligatorio',
            'cedula.min' => 'El campo cédula debe tener mínimo 5 dígitos',
            'cedula.max' => 'El campo cédula debe tener máximo 15 dígitos',

            'email.required' => 'La dirección de correo electrónico es obligatoria',
            'email.email' => 'El correo electrónico no tiene un formato válido',
            'email.unique' => 'La dirección de correo electrónico se encuentra registrada',

            'telefono.required' => 'El campo teléfono es obligatorio',
            'telefono.min' => 'El campo teléfono debe tener mínimo 5 dígitos',
            'telefono.max' => 'El campo teléfono debe tener máximo 20 dígitos',

            'observaciones.required' => 'El campo observaciones es obligatorio',
            'observaciones.min' => 'El campo observaciones debe tener mínimo 10 caracteres',
            'observaciones.max' => 'El campo observaciones debe tener máximo 500 caracteres',
            
            'servicios.required' => 'El campo servicios es obligatorio'
        ]);


        //Imagen

        $fileName = '';

        if($request->file('imagen')) {
            $file = $request->file('imagen') ;
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path().'/images/customers';
            $file->move($destinationPath, $fileName);
        } 

        $cliente = Customer::create([
            'nombre' => $request['nombre'],
            'cedula' => $request['cedula'],
            'email' => $request['email'],
            'telefono' => $request['telefono'],
            'observaciones' => $request['observaciones'],
            'imagen' => $fileName
        ]);

        $cliente->services()->attach(explode(',',$request['servicios']));

        return Customer::with('services')->find($cliente->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        return Customer::with('services')->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|min:2|max:50',
            'cedula' => 'required|string|min:5|max:15',
            'email' => 'required|string|unique:users,email',
            'telefono' => 'required|string|min:5|max:20',
            'observaciones' => 'required|min:10|max:500',
            'servicios' => 'required'
        ],
        [
            'nombre.required' => 'El campo nombre es obligatorio',
            'nombre.min' => 'El campo nombre debe tener mínimo 2 caracteres',
            'nombre.max' => 'El campo nombre debe tener máximo 50 caracteres',

            'cedula.required' => 'El campo cédula es obligatorio',
            'cedula.min' => 'El campo cédula debe tener mínimo 5 dígitos',
            'cedula.max' => 'El campo cédula debe tener máximo 15 dígitos',

            'email.required' => 'La dirección de correo electrónico es obligatoria',
            'email.email' => 'El correo electrónico no tiene un formato válido',
            'email.unique' => 'La dirección de correo electrónico se encuentra registrada',

            'telefono.required' => 'El campo teléfono es obligatorio',
            'telefono.min' => 'El campo teléfono debe tener mínimo 5 dígitos',
            'telefono.max' => 'El campo teléfono debe tener máximo 20 dígitos',

            'observaciones.required' => 'El campo observaciones es obligatorio',
            'observaciones.min' => 'El campo observaciones debe tener mínimo 10 caracteres',
            'observaciones.max' => 'El campo observaciones debe tener máximo 500 caracteres',
            
            'servicios.required' => 'El campo servicios es obligatorio'
        ]);
        
        $cliente = Customer::find($id);

        //Image
        $fileName = '';

        if($request->file('imagen')) {

            //Eliminar imagen
            $imagePath = public_path().'/images/customers/'.$cliente->imagen;
            if(File::exists($imagePath)) {
                File::delete($imagePath);
            }
            
            //Subir nueva imagen
            $file = $request->file('imagen') ;
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path().'/images/customers';
            $file->move($destinationPath, $fileName);


            $cliente->update([
                'nombre' => $request['nombre'],
                'cedula' => $request['cedula'],
                'email' => $request['email'],
                'telefono' => $request['telefono'],
                'observaciones' => $request['observaciones'],
                'imagen' => $fileName
            ]);

        } else {
            $cliente->update($request->only(['nombre', 'cedula', 'email', 'telefono', 'observaciones']));
        }
        

        $cliente->services()->detach();
        $cliente->services()->attach(explode(',',$request['servicios']));

        return Customer::with('services')->find($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cliente = Customer::find($id);
        
        
        //Eliminar imagen
        $imagePath = public_path().'/images/customers/'.$cliente->imagen;
        if(File::exists($imagePath)) {
            File::delete($imagePath);
        }
        
        //Quitar relaciones
        $cliente->services()->detach();

        //Eliminar
        $result = Customer::destroy($id);

        if(!$result) {
            return [
                'error' => 'Hubo un error'
            ];
        } 

        return [
            'message' => 'Cliente eliminado correctamente'
        ];
    }

}
