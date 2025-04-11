var typedWelcome = new Typed('.Welcome', {
  strings: ["WELCOME TO ENTRIX SYSTEM"],
  typeSpeed: 150,
  backSpeed: 150,
  loop: true
});



const studentprofile = document.getElementById('studentProfile');
const profileError = document.getElementById('studentProfileError');

const ProfileFade = () => {
 if(studentprofile){
  studentprofile.style.display = 'none';
 }
 if(profileError){
  profileError.style.display = 'none';
 }
};

setTimeout(ProfileFade, 3000);
