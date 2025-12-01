# Gestion Notas

## Hecho por: Maksym Derezyuk, Sergi Nebot, Gerard Rodriguez, Aaron Suarez

### Información

Esta aplicación servirá para que el administrador vea correctamente todos los alumnos y los profesores puedan ver a los alumnos que tienen asignados a sus asignaturas.

### Funcionamiento:

#### Login

Lo que se hace en esta es verificar que el usuario (email) y la contraseña que haya pasado el usuario para logearse coincida con la que hay en la base de datos y pueda ingresar en la pagina principal.

#### Registro

Esta pagina servira para registrar a un nuevo usuarios junto a sus validaciones js y php.

#### Pagina Principal

En esta pagina veremos arriba una barra de filtros y abajo a los usuarios registrados con sus datos, a parte de los botone para ver al alumno y en caso de ser administrador poder modificarlos o eliminarlos.

#### Estadisticas

En esta podremos ver las estadisticas globales, como por ejemplo: La materia con la mejor media, el total de notas registradas, rendimiento mediopor asignatura, etc...

#### Ver Alumno

Aqui podremos ver el nombre del alumno, en el caso de que tenga notas guardadas saldran en una tabla junto al profesor que la ha asignado y la asignatura de la nota ( solo si eres profesor podras añadir una nota) y en el caso de que no hayan notas saldra un mensaje diciendo que no tiene notas registradas.

#### Regitrar Alumnos

En este podremos registrar un nuevo alumno añadiendole su nombre (nombre y apellidos), luego su nombre de usuario, correo, fecha de nacimiento y grado en el que se va a matricular.

#### Editar Alumnos

En este podremos editar los datos mencionados en el registro.

#### Poner notas

Esta funcion solo la podra usar el profesor en el alumno que este vinculado a su asignatura, en el caso de que si este todo correctamente (el profesor en un alumno al que si tenga vinculado) podra seleccionar en que asignatura le quiere poner la nota, cual es la convocatoria (ordinaria o extraordinaria) y tambien la nota que se le tiene que poner al alumno.
