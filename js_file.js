
function submitPost() {
    window.location.href = "Feed.html"
}

function cancelAddPost() {
    window.location.href = "Feed.html"
}

function cancelEdiPost(){
  window.location.href = "Profile.html"
}

function showPosts() {
    document.getElementById('searching').style.display = 'block';
}

function hidePosts() {
    document.getElementById('searching').style.display = 'none';
}

function openNav(){
    document.getElementById("my_sidebar").style.width = "250px";
    document.getElementById("main").style.marginLeft = "250px";
}
function closeNav(){
    document.getElementById("my_sidebar").style.width = "0";
    document.getElementById("main").style.marginLeft = "0";
}

function logout_alert(){
    alert("Are you sure you want to logout?");
}

// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("openForm");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

