var avatarElement = document.querySelector('.nav_pc_item__avt');
var accountSetting = document.querySelector('.account_setting_container');
// Open and close account setting on avatar
avatarElement.onclick = function() {
    if (accountSetting.classList.contains('hide')) {
        accountSetting.classList.remove('hide');
    } else {
        accountSetting.classList.add('hide');
    }
}