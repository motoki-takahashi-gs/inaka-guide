class ShowThumbnail {
    constructor() {
        // properties
        this.customBtn = document.getElementById('custom_button');
        this.realBtn = document.getElementById('real_button');
        this.img = document.getElementById('image_thumbnail');
        this.reader = new FileReader();

        // event handlers
        this.customBtn.addEventListener('click', () => this.clickRealBtn());
        this.realBtn.addEventListener('change', () => this.getFileName());
        this.reader.addEventListener('load', () => this.setImgSrc());
    }

    // click the real button
    clickRealBtn() {
        this.realBtn.click();
    }

    // read the selected image file
    getFileName() {
        this.reader.readAsDataURL(this.realBtn.files[0]);
    }

    // set the image file with base64 string to image source
    setImgSrc() {
        this.img.src = this.reader.result;
    }

}

const showThumbnail = new ShowThumbnail();