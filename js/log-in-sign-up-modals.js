
const logInSignUpModals = () => {

    const logInModal = document.getElementById('log-in-modal');
    const signUpModal = document.getElementById('sign-up-modal');

    // show log in modal
    const showLogInModal = () => {

        // when click the account icon on footer or next button on detail page (when not logged in yet)
        const linkToAccount = document.getElementsByClassName('link-to-account');

        if (linkToAccount) {

            for (let i = 0; i < linkToAccount.length; i++) {

                linkToAccount[i].onclick = function () {

                    // show Login modal
                    logInModal.classList.toggle('modal-active');

                    // check the clicked element to determine the URL to be redirected
                    const nextBtn = document.querySelector('.calendar .go-to-next button');
                    const inputRedirectPage = document.querySelectorAll('input[name="redirect-to"]');
                    let redirectPage = '';

                    if (this == nextBtn) {

                        // a redirect page (an event detail page)
                        redirectPage = document.URL.substring(document.URL.lastIndexOf('/') + 1) + '&redirect=true';

                        // the selected event info object
                        const selectedEventInfo = {
                            dateMysql: document.getElementById('selected-date-hyphened-hidden').value,
                            dateDisplay: document.getElementById('selected-date-normal-hidden').value,
                            numberOfGuests: document.querySelector('select[name="number-of-guests"]').value
                        }

                        // put the selected event info object to Local storage
                        localStorage.setItem('selected_event_info', JSON.stringify(selectedEventInfo));
                    }
                    else {
                        redirectPage = 'account.php';
                    }

                    // put a redirect page info to 2 hidden elements 
                    for (let i = 0; i < inputRedirectPage.length; i++) {
                        inputRedirectPage[i].value = redirectPage;
                    }
                }
            }
        }

        // when click the Login string on Sign up modal
        document.getElementById('link-to-log-in').onclick = () => {
            // hide Sign up modal
            signUpModal.classList.toggle('modal-active');
            // show Login modal
            logInModal.classList.toggle('modal-active');
        }
    }

    // show sign up modal
    const showSignUpModal = () => {

        // when click the Sign up string on Login modal
        document.getElementById('link-to-sign-up').onclick = () => {
            // hide Login modal
            logInModal.classList.toggle('modal-active');
            // show Sign up modal
            signUpModal.classList.toggle('modal-active');
        }
    }

    // close modals
    const closeModals = () => {

        // when click the close mark on login modal
        document.querySelector('#log-in-modal .close').onclick = () => {
            logInModal.classList.toggle('modal-active');
        }

        // when click the close mark on signup modal
        document.querySelector('#sign-up-modal .close').onclick = () => {
            signUpModal.classList.toggle('modal-active');
        }
    }

    showLogInModal();
    showSignUpModal();
    closeModals();
}

logInSignUpModals();