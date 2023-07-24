let URL = "http://localhost:8080/quejas/config/rutas/ruta.clientes.php"


let form_cliente = document.getElementById('form_cliente');

form_cliente.addEventListener('submit',()=>{
  event.preventDefault();
  let numero_cliente = document.getElementById('numero_cliente').value
  const datos = new FormData(form_cliente)
  fetch(URL,{
    method: 'POST',
    body: datos
  })
  .then(x => x.json())
  .then(res =>{
    window.location.href = res.ruta
  })
})

