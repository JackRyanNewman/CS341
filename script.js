document.addEventListener("DOMContentLoaded", function() {
  document.getElementById("SignOut").addEventListener("click", ()=>{
      fetch("p01_logOut.php").then(() => {
      window.location.href = "p02_home.php";
      });
  });

});

