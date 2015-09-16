function check_name() {
  username = document.post.username.value;
  $('#check_name').fadeIn(); ajax.check_name();
}
ajax.check_name = function() {
  ajax.exec({
    action   : 'check_name',
    username :  username
  });
};
ajax.callback.check_name = function(data){
  $('#check_name span').html(data.html);
};
function check_email() {
  email = document.post.email.value;
  $('#check_email').fadeIn(); ajax.check_email();
}
ajax.check_email = function() {
  ajax.exec({
    action   : 'check_email',
    email    :  email
  });
};
ajax.callback.check_email = function(data){
  $('#check_email span').html(data.html);
};
function check_pass() {
  pass_confirm = document.post.password_confirm.value;
  pass = document.post.new_password.value;
  $('#check_pass').fadeIn(); ajax.check_pass();
}
ajax.check_pass = function() {
  ajax.exec({
    action   : 'check_pass',
    pass     :  pass,
    pass_confirm : pass_confirm
  });
};
ajax.callback.check_pass = function(data){
  $('#check_pass span').html(data.html);
};
function check_invite() {
  invite_code = document.post.invite_code.value;
  $('#check_invite').fadeIn(); ajax.check_invite();
}
ajax.check_invite = function() {
  ajax.exec({
    action   : 'check_invite',
    invite_code :  invite_code
  });
};
ajax.callback.check_invite = function(data){
  $('#check_invite span').html(data.html);
};