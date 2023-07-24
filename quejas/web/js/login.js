//se declara la constante que guardara la url para hacer las peticiones al servidor
const URL = 'http://localhost:8080/quejas/config/rutas/ruta.login.php'

//Se crea variable para acceder al elemento formulario de inicio de sesion
const form_inicio_sesion = document.getElementById("form-inicio-sesion")
//Se agrega un evento submit al formulario para enviar los datos al servidor y validarlos
form_inicio_sesion.addEventListener("submit", (event)=>{
  event.preventDefault();

    let datos = new FormData(form_inicio_sesion)

    fetch(URL,{
      method: "post",
      body: datos
    })
    //Se resive la respuesta del servidor
    .then(x =>x.json())
    .then(res =>{

      if(res.autenticacion == '1'){
        window.location.href = 'index.html'
      }else{
        alert(res.mensaje)
      }
    })
})