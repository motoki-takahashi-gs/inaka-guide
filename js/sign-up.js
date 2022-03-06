
// show sign up part by default only in sign up page
const showSignUpPart = () => {

    // remove modal class so it can show up normally if in sign up page
    document.getElementById('sign-up-modal').classList.remove('modal');

    // remove close mark if in sign up page
    const closeBtn = document.querySelector('.sign-up .close');
    closeBtn.parentNode.removeChild(closeBtn);
}

showSignUpPart();