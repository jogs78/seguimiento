<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Empresa;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void 
    {
        $empresa = new Empresa();
        $empresa->id = 1;
        $empresa->nombre = "Tecnologico de Tuxtla Gutierrez";
        $empresa->giro = "publico";
        $empresa->rfc = "TNM140723GFA";
        $empresa->direccion = "KM 29020, Carr. Panamericana 1080, Boulevares, 29050 Tuxtla Gutiérrez, Chis.";
        $empresa->telefono = "96161500461";
        $empresa->correo = "contacto@ittg.edu.mx";
        $empresa->titular = "José Manuel Rosado Pérez";
        $empresa->puesto_titular = "Director del ITTG";
        $empresa->asesor_externo = "Gabriela Alejandra Ríos Zúñiga ";
        $empresa->puesto_asesor = "Jefa del Departamento de Departamento de Ingenierías ";
        $empresa->informacion = "Ser una Institución de Excelencia en la Educación Superior Tecnológica del Sureste, comprometida con el desarrollo socioeconómico sustentable de la región.";
        $empresa->save(); 

        $empresa = new Empresa();
        $empresa->id = 2;
        $empresa->nombre = "Instituto de ciencia, tecnologia e innovacion del estado de chiapas";
        $empresa->giro = "publico";
        $empresa->rfc = "ICT456321IEC";
        $empresa->direccion = "Calz. Cerro Hueco 3000, Rivera Cerro Hueco, FSTSE, 29094 Tuxtla Gutiérrez, Chis.";
        $empresa->telefono = "9617621043";
        $empresa->correo = "InstitutoDeCiencia@gmail.com";
        $empresa->titular = "Manuel Miguel Mortinez Mora";
        $empresa->puesto_titular = "Presidente del Instituto";
        $empresa->asesor_externo = "Alberto Gomez Gonzales";
        $empresa->puesto_asesor = "Secretario Administrativo";
        $empresa->informacion = "Llevar la ciencia y tecnologia al alcanze de los chiapanecos";
        $empresa->save(); 

        $empresa = new Empresa();
        $empresa->id = 3;
        $empresa->nombre = "CFE Tranmision";
        $empresa->giro = "servicios";
        $empresa->rfc = "CFE908567TRA";
        $empresa->direccion = "Carr. Panamericana 5675, Plan de Ayala Ampliación Sur, 29020 Tuxtla Gutiérrez, Chis.";
        $empresa->telefono = "9685395322";
        $empresa->correo = "CfeTransmision@gmail.com";
        $empresa->titular = "Karla Perez Estrada";
        $empresa->puesto_titular = "Jefa de operaciones";
        $empresa->asesor_externo = "Mauruicio Montes Becerra";
        $empresa->puesto_asesor = "Asistente de operaciones";
        $empresa->informacion = "Monitorear y regular la transmision de energia";
        $empresa->save(); 

        $empresa = new Empresa();
        $empresa->id = 4;
        $empresa->nombre = "Secretaria de Infraestructura, Comunicaciones y transporte";
        $empresa->giro = "servicios";
        $empresa->rfc = "SIC870652CIS";
        $empresa->direccion = "Av. Universidad Esq. Xola, S/N, Centro Nacional SCT, Cuerpo C, PISO 1, Sección: Oficinas, Narvarte, Delegación Benito Juárez C.P. 03020 Ciudad de México.";
        $empresa->telefono = "55197456";
        $empresa->correo = "SecDeInfraestrutura@gmail.com";
        $empresa->titular = "Juan Carlos Pérez Martínez";
        $empresa->puesto_titular = "Director general";
        $empresa->asesor_externo = "María Fernanda López Díaz";
        $empresa->puesto_asesor = "Secretaria asistente";
        $empresa->informacion = "Movilidad, seguridad y eficiencia es el lema";
        $empresa->save(); 

        $empresa = new Empresa();
        $empresa->id = 5;
        $empresa->nombre = "COSMOS71";
        $empresa->giro = "privado";
        $empresa->rfc = "COS870652MOS";
        $empresa->direccion = "4ta sur oriente, colonia naranjos #45";
        $empresa->telefono = "9675519745";
        $empresa->correo = "Cosmos71@gmail.com";
        $empresa->titular = "Luis Miguel Torres García";
        $empresa->puesto_titular = "Dueño";
        $empresa->asesor_externo = "Ana Paula Ramírez Morales";
        $empresa->puesto_asesor = "Asistente de Desarrollo";
        $empresa->informacion = "Investigacion del espacio y cuerpos celestes";
        $empresa->save(); 

        $empresa = new Empresa();
        $empresa->id = 6;
        $empresa->nombre = "Comision Federal de Electricidad";
        $empresa->giro = "publico";
        $empresa->rfc = "CFE870652EFC";
        $empresa->direccion = "Paseo De La Reforma #164 - Piso 16, Deleg. Cuauhtémoc C.P. 06600 Ciudad de México.";
        $empresa->telefono = "9605884423";
        $empresa->correo = "CFE@hotmail.com";
        $empresa->titular = "Sofía Alejandra Gómez Ríos";
        $empresa->puesto_titular = "Presidenta General";
        $empresa->asesor_externo = "Javier Eduardo Salazar Pérez";
        $empresa->puesto_asesor = "Tesorero";
        $empresa->informacion = "Consolidarnos como la empresa de energía líder en México, con solvencia técnica y financiera, que procura el fortalecimiento de nuestro capital humano y garantiza el servicio de energía eléctrica con calidad y sentido social";
        $empresa->save(); 

        $empresa = new Empresa();
        $empresa->id = 7;
        $empresa->nombre = "Instituto De Elecciones y participacion Ciudadana (IEPC)";
        $empresa->giro = "publico";
        $empresa->rfc = "IEP870652CPE";
        $empresa->direccion = "Perif. Sur Pte. #2185, Penipak, 29060 Tuxtla Gutiérrez, Chis.";
        $empresa->telefono = "9618576489";
        $empresa->correo = "IEPC@hotmail.com";
        $empresa->titular = "Valeria Cristina Castro López";
        $empresa->puesto_titular = "Directora Ejecutiva";
        $empresa->asesor_externo = "Nicolás Alejandro Fernández Ruiz";
        $empresa->puesto_asesor = "Asistente Admistrativo";
        $empresa->informacion = "Ser un referente a nivel nacional en la gestión de procesos electorales e implementación de mecanismos de participación ciudadana a nivel local, garantizando inclusión, igualdad y participación informada de la ciudadanía";
        $empresa->save(); 

        $empresa = new Empresa();
        $empresa->id = 8;
        $empresa->nombre = "Consejo de la Judicatura del poder Judicial del estado";
        $empresa->giro = "publico";
        $empresa->rfc = "CJP870652EJP";
        $empresa->direccion = "libramiento norte No. 2100, Fraccionamiento El Bosque C.P. 29049 Tuxtla Gutiérrez, Chiapas.";
        $empresa->telefono = " 9616178700";
        $empresa->correo = "ConsejoJudicatura@hotmail.com";
        $empresa->titular = "Guillermo Ramos Pérez";
        $empresa->puesto_titular = "Presidente del Consejo";
        $empresa->asesor_externo = "José Francisco Trujillo Ochoa";
        $empresa->puesto_asesor = "Consejero Asistente";
        $empresa->informacion = "Ser un referente a nivel nacional en la gestión de procesos electorales e implementación de mecanismos de participación ciudadana a nivel local, garantizando inclusión, igualdad y participación informada de la ciudadanía";
        $empresa->save(); 

        $empresa = new Empresa();
        $empresa->id = 9;
        $empresa->nombre = "Almacenes y graneros Torre de roca S.A";
        $empresa->giro = "servicios";
        $empresa->rfc = "HNG184936OUY";
        $empresa->direccion = "5ta norte y 6ta poniente 120 - C";
        $empresa->telefono = "9614829432";
        $empresa->correo = "Torre_Roca_SA@gmail.com";
        $empresa->titular = "Marcos Alcantara Solis";
        $empresa->puesto_titular = "Representante legal de la Sociedad";
        $empresa->asesor_externo = "Daniela Robles Cruz";
        $empresa->puesto_asesor = "Asesora financiera";
        $empresa->informacion = "Somos una empresa encargada de almecenar productos alimienticios";
        $empresa->save(); 

        $empresa = new Empresa();
        $empresa->id = 10;
        $empresa->nombre = "Frituras y Botanas El rancherito";
        $empresa->giro = "industrial";
        $empresa->rfc = "IYO969745PMU";
        $empresa->direccion = "9na sur y 3ra oriente 76 - A";
        $empresa->telefono = "9679865312";
        $empresa->correo = "ElRancherito@gmail.com";
        $empresa->titular = "Fernando Vazques Sol";
        $empresa->puesto_titular = "Dueño de la marca";
        $empresa->asesor_externo = "Alfonso Mena Becerra";
        $empresa->puesto_asesor = "Asesor de Ventas e imagen";
        $empresa->informacion = "Somos una empresa productora y distribuidora de productos fritos";
        $empresa->save(); 
    }

}
