let container = document.getElementById("container");

setInterval(() => {
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = () => {
        if(xhr.readyState == 4 && xhr.status == 200){
            container.innerHTML = xhr.responseText;
        }
    }
    xhr.open("GET", "expired-date.php", true);
    xhr.send();
}, 1000);