let search = document.getElementById("search");
let container = document.getElementById("container");

search.addEventListener("input", () => {
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = () => {
        if(xhr.readyState == 4 && xhr.status == 200){
            container.innerHTML = xhr.responseText;
        }
    }
    xhr.open("GET", "search-results.php?q=" + search.value, true);
    xhr.send();
});