let returned_date = document.getElementById("returned-date");

setInterval(() => {
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = () => {
        if(xhr.readyState == 4 && xhr.status == 200){
            returned_date.innerHTML = xhr.responseText;
        }
    }
    xhr.open("GET", "returned-date.php", true);
    xhr.send();
}, 1000);