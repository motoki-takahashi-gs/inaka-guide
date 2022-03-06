
// show login part by default only in login page
const showLoginPart = () => {

    // remove modal class so it can show up normally if in login page
    document.getElementById('log-in-modal').classList.remove('modal');

    // remove close mark if in login page
    const closeBtn = document.querySelector('.log-in .close');
    closeBtn.parentNode.removeChild(closeBtn);
}

showLoginPart();