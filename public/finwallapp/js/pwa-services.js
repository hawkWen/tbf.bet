'use strict'
/* PWA services worker register */
if ("serviceWorker" in navigator) {
    window.addEventListener("load", function (event) {
        navigator.serviceWorker
            .register("/website/finwallapp/HTML/serviceWorker.js", {
            //.register("./serviceWorker.js", {
                scope: './'
            })
            .then(reg => console.log("service worker registered"))
            .catch(err => console.log("service worker not registered"));
    });
}

/* PWA add to home button */
var btnAdd = document.getElementById('addtohome')
var defferedPrompt;
window.addEventListener("beforeinstallprompt", function (event) {
    event.preventDefault();
    defferedPrompt = event;

    btnAdd.addEventListener("click", function (event) {
        defferedPrompt.prompt();
        
        
        defferedPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
                console.log('User accepted the A2HS prompt');
            } else {
                console.log('User dismissed the A2HS prompt');
            }
            defferedPrompt = null;
        });
    });


});

window.addEventListener("appinstalled", function (event) {
    //app.logEvent("a2hs", "Installed");
    document.getElementById('addtodevice').style.display = 'none';
});


if (window.matchMedia('(display-mode: fullscreen)').matches) {
    $('#addtodevice').fadeOut()
} else {
    $('#addtodevice').fadeIn()
}
