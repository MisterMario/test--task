// Тут будут все ajax запросы к серверу

function ajax(handler, params, callback, isAsync = true) {
  $.ajax({
    url: handler,
    type: 'POST',
    data: JSON.stringify(params),
    contentType: 'application/json; charset=utf-8',
    //dataType: 'json',
    success: callback,
    async: isAsync,
  });
}

function sendRegistrationData() {
  var data = {
    login: $('#registration input[name=login]').val(),
    password: $('#registration input[name=pass]').val(),
    repassword: $('#registration input[name=repass]').val(),
    email: $('#registration input[name=email]').val(),
    name: $('#registration input[name=name]').val(),
  },
    error_code = -1, message = 'Ошибка: ';

  if (data.login.length == 0)
    error_code = 0;
  else if (data.password.length == 0)
    error_code = 0;
  else if (data.repassword.length == 0)
    error_code = 0;
  else if (data.email.length == 0)
    error_code = false;
  else if (data.name.length == 0)
    error_code = 0;
  else if (data.password != data.repassword)
    error_code = 1;
  else if (data.login.length < 3 && data.login.length > 32)
    error_code = 2;
  else if (data.password.length < 4 && data.password.length > 32)
    error_code = 3;

  if (error_code == -1) {
    ajax('ajax_register.php', data, function(data) {
      if (data.status) {
        $('#registration').addClass('hidden');
        $('#authorization').removeClass('hidden');
        message = 'Вы успешно зарегистрированы!';
      } else message += data.message;
      showMessageBox(message);
    });
  }

  switch (error_code) {
    case 0:
      message += 'не все поля заполнены!';
      break;
    case 1:
      message += 'пароли не совпадают!';
      break;
    case 2:
      message += 'логин не может быть меньше 3 символов или больше 32!';
      break;
    case 3:
      message += 'пароль должен содержать от 4 до 32-х символов!';
      break;
  }

  if (error_code != -1 ) showMessageBox(message);
}

function sendAuthorizationData() {
  var data = {
    login: $('#authorization input[name=login]').val(),
    password: $('#authorization input[name=pass]').val(),
  };

  if (data.login.length == 0 || data.password.length == 0)
    showMessageBox('Ошибка: не все поля заполнены!');
  else
    ajax('ajax_login.php', data, function(data) {
      $('body').html(data);
      /*if (data.status) {
        $('#authorization').addClass('hidden');
        $('#personal-room').removeClass('hidden');
        $('#personal-room p span').html(data.message);
      } else showMessageBox("Ошибка: " + data.message);*/

    });
}

function logout() {
  ajax('ajax_logout.php', {logout:true}, function(data) {

    if (data.status) {
      $('#personal-room').addClass('hidden');
      $('#authorization').removeClass('hidden');
    } else showMessageBox(data.message);

  });
}
