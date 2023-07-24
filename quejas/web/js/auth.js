const URLauth = 'http://localhost:8080/quejas/config/auth.php'

const LOGOUT = 'http://localhost:8080/quejas/config/logout.php'

function getCookie(value) {
  let token = value + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(token) == 0) {
      return c.substring(token.length, c.length);
    }
  }
  return "";
}

let cok = 'token'
let result = {token: getCookie(cok)}
let resultJSON = JSON.stringify(result) 
fetch(URLauth,{
  method: 'post',
  body: resultJSON
})
.then(x => x.json())
.then(res => {
  if(!res.auth){
    window.location.href = 'login.html'
  }
})

const $logout = document.getElementById('logout')
$logout.addEventListener('click', ()=>{
  event.preventDefault();
  fetch(LOGOUT)
  .then(x => x.json())
  .then(res =>{
   document.cookie = "PHPSESSID=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
  document.cookie = "token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"; 
    window.location.href = res.ruta
  })


})

