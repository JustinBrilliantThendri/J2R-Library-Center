let code = document.getElementById("code");
let show_toast = document.getElementById("show-toast");
let toast = document.getElementById("toast");

if(show_toast){
    let toast_bootstrap = bootstrap.Toast.getOrCreateInstance(toast);
    show_toast.addEventListener("click", () => {
        navigator.clipboard.writeText(code.innerHTML);
        toast_bootstrap.show();
    });
};