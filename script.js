
document.addEventListener("DOMContentLoaded", function() {
    
    const AddSession = document.getElementById("AddSession");
    document.getElementById("CreateSession").addEventListener("click", ()=>{
        if(AddSession.style.display === "block") {
            AddSession.style.display = "none";
        } else{
            AddSession.style.display = "block";
        }   
    });

    const signup = document.getElementById("signup");
    signup.addEventListener("click", ()=>{
        signup.style.display = "none";
    });
  });
  
  
