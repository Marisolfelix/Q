const URL_CLIENTES = "http://localhost:8080/quejas/config/rutas/ruta.clientes.php"
const URL_FACTURAS = "http://localhost:8080/quejas/config/rutas/ruta.facturas.php"
const URL_ACLARACIONES = "http://localhost:8080/quejas/config/rutas/ruta.aclaraciones.php"
let NUMERO_DE_CLIENTE 
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

let numero_cliente = getParameterByName('id')

fetch(`${URL_CLIENTES}?id=${numero_cliente}`)
.then(x => x.json())
.then(res => {
  NUMERO_DE_CLIENTE = res.numero_de_cliente
   card_title_detalle.innerHTML = `
  Facturas ${res.numero_de_cliente}
 `
   card_title_facturas.innerHTML = `
  Facturas ${res.numero_de_cliente}
 `
  cliente_info.innerHTML = `
              <tr>
              <td id="numero_de_cliente">${res.numero_de_cliente}</td>
              <td id="nombre">${res.nombre}</td>
              <td id="telefono">${res.telefono}</td>
              <td id="correo">${res.correo}</td>
            </tr>
  `

  cliente_info_domicilio.innerHTML = `
              <tr>
              <td>${res.domicilio}</td>
            </tr>
  `
})

fetch(URL_FACTURAS)
.then(x => x.json())
.then(res => {

 let saldo = 0
 
 res.forEach(factura =>{
  saldo += parseInt(factura.cargo)
  saldo += parseInt(factura.abono)
 })

 res.forEach(factura => {
    tbody_facturas.innerHTML +=  ` 
    <tr id="${factura.numero_de_factura}">
      <td class="${factura.numero_de_factura}">
        <input type="radio" id="RB_${factura.numero_de_factura}" name="opciones" value="${factura.numero_de_factura}">
        <label for="RB_${factura.numero_de_factura}">${factura.numero_de_factura}</label>
      </td>
      <td class="${factura.numero_de_factura}">${factura.descripcion}</td>
      <td class="${factura.numero_de_factura}">${factura.tienda}</td>
      <td class="${factura.numero_de_factura}">${factura.fecha}</td>
      <td class="${factura.numero_de_factura}">${factura.cargo}</td>
      <td class="${factura.numero_de_factura}">${factura.abono}</td>
    </tr>
  `
  tbody_detalle_facturas.innerHTML +=`
    <tr id="D_${factura.numero_de_factura}" class="d-none">
      <td class="D_${factura.numero_de_factura}">
        <input type="radio" id="D_RB_${factura.numero_de_factura}" name="opciones" value="${factura.numero_de_factura}">
        <label for="D_RB_${factura.numero_de_factura}">${factura.numero_de_factura}</label>
      </td>
      <td class="D_${factura.numero_de_factura}">${factura.descripcion}</td>
      <td class="D_${factura.numero_de_factura}">${factura.tienda}</td>
      <td class="D_${factura.numero_de_factura}">${factura.fecha}</td>
      <td class="D_${factura.numero_de_factura}">${factura.tipo_de_movimiento}</td>
      <td class="D_${factura.numero_de_factura}">${factura.cargo}</td>
      <td class="D_${factura.numero_de_factura}">${factura.abono}</td>
      <td class="D_${factura.numero_de_factura}"></td>
    </tr>
  `
 });
   tbody_facturas.innerHTML += `
  <tr id="saldo">
    <td colspan="4"></td>
    <td>Total</td>
    <td>${saldo}</td>
  </tr>
  `

})

function validarPalabra(str, palabra) {
  // Utilizamos el método includes() para verificar si la palabra está presente en el string
  return str.includes(palabra);
}

const agregar_aclaracion = ()=>{
    let dataJSON = ObtenerDatos()
       if(dataJSON[0] == 0){
      alert('No se selecciono factura')
    }else{
      if(dataJSON.get('validarObservacion') == 1){
        fetch(URL_ACLARACIONES,{
        method: 'POST',
        body: dataJSON
        })
        .then(x => x.json())
        .then(res => {
          if(validarPalabra(res.mensaje,"Duplicate")){
            let confirmacion = confirm(`Ya hay una aclaracion para la factura ${dataJSON.get('factura')} ¿Desea generar nuevo folio? `)
            if(confirmacion){
              dataJSON.set('update',true)
              fetch(URL_ACLARACIONES,{
                method: 'POST',
                body: dataJSON
              })
              .then(x2 => x2.json())
              .then(res2 =>{
                alert(res2.mensaje)
                location.reload()
              })
            }
            return
          }
          if(!res.error){
            alert(res.mensaje)
            location.reload()
          }
        })
      }else{
        alert('Debe ingresar una observacion')
      }

    }

}

const enviar_aclaracion = document.getElementById('enviar_aclaracion')
enviar_aclaracion.addEventListener("click",agregar_aclaracion)

function ObtenerDatos() {
  const opciones = document.getElementsByName("opciones");
  let chek = [0,0]
  for (let i = 0; i < opciones.length; i++) {
    if (opciones[i].checked) {
      chek[0]=1;
      let factura
      factura = opciones[i].value
      let numero_de_folio = "AC"
      let numero_de_cliente 
      let descripcion
      let area
      let tienda
      let fecha_de_compra
      let importe
      let observacion
      let valores
      let validarObservacion = 0
      const formData = new FormData();

      valores = document.querySelectorAll(`.D_${factura}`)

      numero_de_cliente = document.getElementById("numero_de_cliente").textContent
      numero_de_folio += document.getElementById(`RB_${factura}`).value
      numero_de_folio += numero_de_cliente.slice(0,3)
      observacion = document.getElementById("observacion").value
      descripcion = valores[1].textContent
      tienda = valores[2].textContent
      fecha_de_compra = valores[3].textContent
      area = valores[4].textContent
      importe = valores[5].textContent

      if(observacion.length > 0){
        validarObservacion = 1
      }

      formData.append('numero_de_folio', numero_de_folio);
      formData.append('numero_de_cliente', numero_de_cliente);
      formData.append('factura', factura);
      formData.append('descripcion', descripcion);
      formData.append('observacion', observacion);
      formData.append('area', area);
      formData.append('tienda', tienda);
      formData.append('fecha_de_compra',fecha_de_compra);
      formData.append('importe',importe);
      formData.append('validarObservacion', validarObservacion)
      formData.append('update',false)

      let aclaracionOBJ = {numero_de_folio: numero_de_folio, numero_cliente: numero_de_cliente, factura: factura, descripcion: descripcion, observacion: observacion, area: area, tienda: tienda, fecha_de_compra: fecha_de_compra, importe: importe}
      let aclaracionJSON = JSON.stringify(aclaracionOBJ)

      return formData;
    }
  }
  return chek;
}

const ver_detalle = ()=>{
  event.preventDefault()
  console.log('en ver detalle')
  const opciones = document.getElementsByName("opciones");
  for (let i = 0; i < opciones.length; i++) {
    if (opciones[i].checked) {
      let factura = opciones[i].value

      let cardDetalle = document.getElementById('card_detalle_facturas')
      let cardFacturas = document.getElementById('card_facturas')
      let trFactura = document.getElementById(`D_${factura}`)
      let bntFacturas = document.getElementById('btnFacturas')
      let btnEmviarAclaracion = document.getElementById('enviar_aclaracion')
      let btnDetalles = document.getElementById('detalles')


      cardFacturas.classList.add('d-none')
      cardDetalle.classList.remove("d-none")
      trFactura.classList.remove('d-none')
      bntFacturas.classList.remove('d-none')
      btnEmviarAclaracion.classList.add('d-none')
      btnDetalles.classList.add('d-none')

    }

  }
}

let btnDetalles = document.getElementById('detalles')

btnDetalles.addEventListener('click',ver_detalle)

const ver_Facturas = () => {

  event.preventDefault()

  let btnEnviarAclaracion = document.getElementById('enviar_aclaracion')
  let btnFacturas = document.getElementById('btnFacturas')
  let btnDetalles = document.getElementById('detalles')
  let card_facturas = document.getElementById('card_facturas')
  let cardDetalle = document.getElementById('card_detalle_facturas')
  

  btnFacturas.classList.add('d-none')
  btnEnviarAclaracion.classList.remove('d-none')
  btnDetalles.classList.remove('d-none')
  card_facturas.classList.remove('d-none')
  cardDetalle.classList.add('d-none')
} 

let btnFacturas = document.getElementById('btnFacturas')

btnFacturas.addEventListener('click',ver_Facturas)

