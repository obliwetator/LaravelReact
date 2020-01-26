// function toggleDarkMode()
// {
//     "dark"===localStorage.getItem("theme")?setThemeLight():setThemeDark()
// }
// function setThemeDark()
// {
//     bsThemeLink.href="/static/css/bootstrap-dark.min.css?t=1495008187",
//     localStorage.setItem("theme","dark"),
//     document.body!==null&&document.body.classList.add('dark')
// }
// function setThemeLight()
// {
//     bsThemeLink.href="/static/css/bootstrap.min.css?t=1494621267",
//     localStorage.setItem("theme","light"),
//     document.body!==null&&document.body.classList.remove('dark')
// }    
// if("undefined"!=typeof Storage)
// {
//     var bsThemeLink=document.getElementById("bsThemeLink");
//     "dark"===localStorage.getItem("theme")&&setThemeDark()
// }



var conn = new WebSocket('ws://localhost:8080');
conn.onopen = function(e) {
    console.log("Connection established!");
};
conn.onmessage = function(e) {
    console.log(e.data);
};
