
// Smooth Scroll
document.querySelectorAll('.menu-left a').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href').substring(1);
        document.getElementById(targetId).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Get the modal
var loginModal = document.getElementById('loginModal');
var registerModal = document.getElementById('registerModal');

// Get the button that opens the modal
var userIcon = document.getElementById('userIcon');

// Get the <span> element that closes the modal
var spans = document.getElementsByClassName("close");

// When the user clicks the button, open the modal
userIcon.onclick = function() {
  loginModal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
for (let span of spans) {
  span.onclick = function() {
    loginModal.style.display = "none";
    registerModal.style.display = "none";
  }
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == loginModal || event.target == registerModal) {
    loginModal.style.display = "none";
    registerModal.style.display = "none";
  }
}

document.addEventListener("DOMContentLoaded", function() {
  const video = document.getElementById("hero-video");
  if (video) {
      video.play(); 
  }
});