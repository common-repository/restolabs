function rls_restolabs_disable_profile_info(){
  document.getElementById('restolabs-account-info').style.display = 'none';
  document.getElementById('restolabs-logout').style.display = 'none';
  document.getElementById('restolabs-show-option').style.display = 'none';
}
function rls_restolabs_enable_profile_info(){
  document.getElementById('restolabs-account-info').style.display ='block';
}
function rls_restolabs_disable_login_block(){
    document.getElementById('restolabs-login-block').style.display = 'none';
}
function rls_restolabs_disable_reg_block(){
    document.getElementById('restolabs-reg-block').style.display = 'none';
}
function rls_restolabs_show_reg_block(){
  document.getElementById('restolabs-reg-block').style.display = 'block';
  document.getElementById('restolabs-login-block').style.display = 'none';
}
function rls_restolabs_show_login_block(){
  document.getElementById('restolabs-login-block').style.display = 'block';
  document.getElementById('restolabs-reg-block').style.display = 'none';
}





