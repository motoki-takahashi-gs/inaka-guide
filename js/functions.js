class HeaderParts {

    constructor() {
        // properties
        this.header = document.getElementsByTagName('header')[0];
        this.title = document.getElementById('title');
        this.navBar = document.getElementById('nav-bar');

        // event handler
        window.addEventListener('scroll', () => this.toggleHeader());
    }

    showTitle() {
        this.title.style.display = 'block';
    }

    hideTitle() {
        this.title.style.display = 'none';
    }

    showNavBar() {
        this.navBar.style.visibility = 'visible';
    }

    hideNavBar() {
        this.navBar.style.visibility = 'hidden';
    }

    toggleHeader() {
        // get excuted only when it's smartphone size
        if (screen.width < 700) {
            // only when scrolling down header height
            if (this.header.offsetTop > 80) {
                // not to execute all the time
                if (this.navBar.style.visibility != 'visible') {
                    // change to navi bar
                    this.hideTitle();
                    this.showNavBar();
                    // console.log('nav bar shown');
                }
            } else {
                // not to execute all the time
                if (this.title.style.display != 'block') {
                    // change to title
                    this.hideNavBar();
                    this.showTitle();
                    // console.log('title shown');
                }
            }
        }
    }
}

const headerParts = new HeaderParts();