function goTo(num) {
  if (num == 0) {
    $('#registration').addClass('hidden');
    $('#authorization').removeClass('hidden');
  } else {
    $('#authorization').addClass('hidden');
    $('#registration').removeClass('hidden');
  }
}
