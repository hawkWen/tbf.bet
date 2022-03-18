function runApp() {
    liff.getProfile().then(profile => {
        console.log('register page ' + profile);
    }).catch(err => console.error(err));
}
liff.init({ liffId: $('#liff_id').val() }, () => {
    if (liff.isLoggedIn()) {
        runApp();
    } else {
        liff.login();
    }
}, err => console.error(err.code, error.message));