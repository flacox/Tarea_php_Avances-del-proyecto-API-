
// BUSCAR ESTUDIANTES
// function buscarEstudiantes(){
//                 fetch("/universidad_estudiante.php")
//                 .then( res => res.json())
//                 .then( res => {
//                     var listaId = document.getElementById('list_Estudiantes')
//                     var temp = '';

//                     var optionEstudiante = document.getElementById('lista_carrera');
//                     var index = 0;
//                     res.forEach(m => {

//                         temp = temp + '<tr>' + 
//                         // '<td>' + m.id + '</td>' +
//                         '<td>' + m.nombre +'</td>' + 
//                         '<td>' + m.matricula + '</td>' + 
//                         '<td>' + m.edad + '</td>' + 
//                         '<td>' + m.carrera_id + '</td>' + 
//                         '<td>' + '<button class="btn btn-warning">Actualizar</button>' + '</td>' +
//                         '<td>' + '<button class="btn btn-danger">Eliminar</button>' + '</td>' +
//                         '</tr>' 

//                         // optionEstudiante.options[index]= new Option(m.nombre, m/id);
//                         // index++;

//                         //listaId.innerHTML = temp;
//                         //console.log(temp);
//                     });
//                     listaId.innerHTML = temp;
                   
//                 })
//                 .catch( err => {
//                     console.log(err);
//                 });
//             }


            var estudianteTemplate = `
            <tr id="row-etudiante-{{ID}}">
            <td>{{NOMBRE}}</td>
            <td>{{MATRICULA}}</td>
            <td>{{EDAD}}</td>
            <td>{{CARRERA}}</td>
            <td><button id="editar-{{ID}}" onclick='editar({{ID}})' data-estudiante='{{DATA}}' class="btn btn-warning">Editar</button></td>
            <td><button onclick="eliminar({{ID}})" class="btn btn-danger">Eliminar</button></td>
            </tr>`

function buscarEstudiantes() {
    fetch("/universidad_estudiante.php")
        .then( res => res.json())
        .then( res => {
            var listaM = document.getElementById('list_Estudiantes');
            var temp = '';

            res.forEach(m => {
                temp = temp + estudianteTemplate.replace(/{{NOMBRE}}/, m.nombre)
                    .replace(/{{MATRICULA}}/, m.matricula)
                    .replace(/{{EDAD}}/, m.edad)
                    .replace(/{{CARRERA}}/, m.carrera)
                    .replace(/{{ID}}/g, m.id)
                    .replace(/{{DATA}}/, JSON.stringify(m));


            });
            listaM.innerHTML = temp;
        })
        .catch( err => {
            console.log(err);
        });
}

function buscarOptionCarreras() {
    fetch("/universidad_carrera.php")
        .then( res => res.json())
        .then( res => {
            var sc = document.getElementById('lista_carrera');
            var index = 0;
            
            res.forEach(m => {
                    sc.options[index]= new Option(m.nombre, m.id);
                    index++;

            });

        })
        .catch( err => {
            console.log(err);
        });
}

function eliminar(id){
    fetch(`/universidad_estudiante.php`, {
        method: 'DELETE',
        headers: {'content-type': 'application/json'},
        body: JSON.stringify({id:id})
    })
    
    .then( res => res.json())
    .then( res => {console.log(res);
    })

    .catch( err => {
        console.log(err);
        //console.log(id);
    });

}

