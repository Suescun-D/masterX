<?php
class Persona
{
    public $nombre;
    public $fechaDeNacimiento;
    public $edad;

    protected function __construct($nombre, $fechaDeNacimiento)
    {
        $this->nombre = $nombre;
        $this->fechaDeNacimiento = $fechaDeNacimiento;
        $this->edad = $this->obtenerEdad($this->fechaDeNacimiento);
    }

    public function obtenerEdad($año)
    {
        $tiempo = new DateTime($año);
        $hoy = new DateTime();
        return $año = $hoy->diff($tiempo)->format('%y');
    }

}

class Empleado extends Persona
{
    public $salario;

    public function __construct($nombre, $edad, $salario)
    {
        parent::__construct($nombre, $edad);
        $this->salario = $salario;
    }
}
class Estudiante extends Persona
{
    public $calificacion;
    public function __construct($nombre, $edad, $calificacion)
    {
        parent::__construct($nombre, $edad);
        $this->calificacion = $calificacion;
    }
}

$estudiantes = [];
$empleados = [];

function sacarPromedio($dat1, $dat2)
{
    if ($dat2 != 0) {
        return $dat1 / $dat2;
    }
}
if (file_exists('./Estudiantes.txt') && file_exists('./Empleados.txt')) {
    $cadenaE = file_get_contents('./Empleados.txt');
    $empleados = unserialize($cadenaE);

    $cadenaEs = file_get_contents('./Estudiantes.txt');
    $estudiantes = unserialize($cadenaEs);

    calcularTodo($empleados, $estudiantes);
} else {
    recibirArchivos();
}
function recibirArchivos()
{
    $rm = -1;
    $rs = -1;
    $registros = [];
    if (($fichero = fopen("Problema1.csv", "r")) !== false) {
        // Lee los nombres de los campos
        $nombres_campos = fgetcsv($fichero, 0, ",");
        $num_campos = count($nombres_campos);
        // Lee los registros
        while (($datos = fgetcsv($fichero, 0, ",")) !== false) {
            // Crea un array asociativo con los nombres y valores de los campos
            for ($icampo = 0; $icampo < $num_campos; $icampo++) {
                $registro[$nombres_campos[$icampo]] = $datos[$icampo];
            }
            // Añade el registro leido al array de registros
            $registros[] = $registro;
        }
        fclose($fichero);
        //echo "Leidos " . count($registros) . " registros\n";
        for ($i = 0; $i < count($registros); $i++) {

            if ($registros[$i][$nombres_campos[2]] == "Sí") {
                $rm++;
                $empleados[$rm] = new Empleado($registros[$i][$nombres_campos[0]], $registros[$i][$nombres_campos[1]], $registros[$i][$nombres_campos[3]]);

            } else {
                $rs++;
                $estudiantes[$rs] = new Estudiante($registros[$i][$nombres_campos[0]], $registros[$i][$nombres_campos[1]], $registros[$i][$nombres_campos[4]]);

            }
        }
    }
    $cadena1 = serialize($estudiantes);
    file_put_contents("Estudiantes.txt", $cadena1);

    $cadena2 = serialize($empleados);
    file_put_contents("Empleados.txt", $cadena2);
    calcularTodo($empleados, $estudiantes);
}

function calcularTodo($empleados, $estudiantes)
{
    $contadorE = [0, 0, 0];
    $sumaE = [0, 0, 0];
    $mediaE = [0, 0, 0];

    $ResultadoEmpleados = [0, 0, 0, 0, 0, 0];
    for ($i = 0; $i < count($empleados); $i++) {
        if ($empleados[$i]->edad <= 25) {
            $contadorE[0]++;
            $sumaE[0] += $empleados[$i]->salario;
        }
        if ($empleados[$i]->edad >= 25 && $empleados[$i]->edad <= 34) {
            $contadorE[1]++;
            $sumaE[1] += $empleados[$i]->salario;
        }
        if ($empleados[$i]->edad <= 34) {
            $contadorE[2]++;
            $sumaE[2] += $empleados[$i]->salario;
        }

    }
    for ($i = 0; $i < count($mediaE); $i++) {
        $mediaE[$i] = sacarPromedio($sumaE[$i], $contadorE[$i]);
    }

    echo
    "Cantidad de empleados: " . count($empleados) . "\n" ."</br>".
    "Cantidad de empleados menores de 25 años: " . $contadorE[  0] . "\n" ."</br>".
    "Salario promedio de empleados menores de 25 años: " . intval($mediaE[0]) . "\n" ."</br>".
    "Cantidad de empleados con edades entre 25 y 34 años: " . $contadorE[1] . "\n" ."</br>".
    "Salario promedio de empleados con edades entre 25 y 34 años: " . intval($mediaE[1]) . "\n" ."</br>".
    "Cantidad de empleados mayores de 34 años: " . $contadorE[2] . "\n" ."</br>".
    "Salario promedio de empleados mayores de 34 años: " . intval($mediaE[2]) . "\n \n"."</br>"
    ;

//________________________________________Estudiante________________________________________________________________________________
    $contadorEs = [0, 0, 0];
    $sumaEs = [0, 0, 0];
    $mediaEs = [0, 0, 0];

    for ($i = 0; $i < count($estudiantes); $i++) {
        if ($estudiantes[$i]->edad <= 25) {
            $contadorEs[0]++;
            $sumaEs[0] += $estudiantes[$i]->calificacion;
        }
        if ($estudiantes[$i]->edad >= 25 && $estudiantes[$i]->edad <= 34) {
            $contadorEs[1]++;
            $sumaEs[1] += $estudiantes[$i]->calificacion;
        }
        if ($estudiantes[$i]->edad <= 34) {
            $contadorEs[2]++;
            $sumaEs[2] += $estudiantes[$i]->calificacion;
        }

    }
    for ($i = 0; $i < count($mediaEs); $i++) {
        $mediaEs[$i] = sacarPromedio($sumaEs[$i], $contadorEs[$i]);
    }

    echo
    "Cantidad de estudiantes: " . count($estudiantes) . "\n" ."</br>".
    "Cantidad de estudiantes menores de 25 años: " . $contadorEs[0] . "\n" ."</br>".
    "Calificación promedio de estudiantes menores de 25 años: " . intval($mediaEs[0]) . "\n" ."</br>".
    "Cantidad de estudiantes con edades entre 25 y 34 años: " . $contadorEs[1] . "\n" ."</br>".
    "Calificación promedio de estudiantes con edades entre 25 y 34 años: " . intval($mediaEs[1]) . "\n" ."</br>".
    "Cantidad de estudiantes mayores de 34 años: " . $contadorEs[2] . "\n" ."</br>".
    "Calificación promedio de estudiantes mayores de 34 años: " . intval($mediaEs[2]) . "\n"
    ;
}
