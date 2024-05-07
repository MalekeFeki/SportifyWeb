// Function to show profile update notification
function showProfileUpdateNotification(username) {
  document.getElementById('username').innerText = username;
  document.getElementById('profile-update-notification').style.display = 'block';
}

// Function to show new user added notification
function showNewUserNotification() {
  document.getElementById('new-user-notification').style.display = 'block';
}