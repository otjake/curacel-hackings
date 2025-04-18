const __CuracelPay = function(options) {
    this.options = options;
    this.validateOptions();
    this.setEnvironment();
    this.scripts = [
        // Append all required scripts here
    ];
    this.styles = [
        "https://res.cloudinary.com/ddble5id6/raw/upload/v1/pay/sdk-collection.css"
        //Append all required css here
    ];
}

__CuracelPay.prototype.setEnvironment = function() {
    if(this.options.apiKey.includes("local")) {
        this.API_BASE_URL = "http://pay.curacel.test/api/pay";
        this.FE_URL = "http://pay.curacel.test";
    } else if(this.options.apiKey.includes("dev")) {
        this.API_BASE_URL = "https://dev.pay.curacel.co/api/pay";
        this.FE_URL = "https://dev.pay.curacel.co";
    } else if(this.options.apiKey.includes("sandbox")) {
        this.API_BASE_URL = "https://sandbox.pay.curacel.co/api/pay";
        this.FE_URL = "https://sandbox.pay.curacel.co";
    } else if(this.options.apiKey.includes("live")) {
        this.API_BASE_URL = "https://pay.curacel.co/api/pay";
        this.FE_URL = "https://pay.curacel.co"
    }

    if (this.API_BASE_URL === undefined || this.FE_URL === undefined) {
        throw new Error("Invalid API Key");
    }
}

__CuracelPay.prototype.loadScript = function(url) {
    return new Promise((res, rej) => {
        if(document.querySelector(`script[src='${url}']`)) {
            return res();
        }
        const script = document.createElement("script");
        script.setAttribute("src", url);
        script.setAttribute("type", "text/javascript");
        document.head.appendChild(script);
        script.onload = () => res();
        script.onerror = () => rej();
    });
}

__CuracelPay.prototype.loadStyle = function(url) {
    return new Promise((res, rej) => {
        if(document.querySelector(`link[href='${url}']`)) {
            return res();
        }
        const style = document.createElement("link");
        style.setAttribute("rel", "stylesheet");
        style.setAttribute("type", "text/css");
        style.setAttribute("href", url);
        document.head.appendChild(style);
        style.onload = () => res();
        style.onerror = () => rej();
    });
}

__CuracelPay.prototype.loadAssets = function() {
    return Promise.all(
        this.styles.map(this.loadStyle)
        .concat(this.scripts.map(this.loadScript))
    )
}

__CuracelPay.prototype.validateOptions = function() {
    if(![
        "apiKey",
        "amount",
        "email",
        "currency",
    ].every(option => !!this.options[option])) {
        throw new Error("Invalid parameters")
    }
}

__CuracelPay.prototype.sendRequest = function({ path = '', method = 'GET', data = {} }) {
    return new Promise((resolve, reject) => {
        fetch(`${this.API_BASE_URL}${path}`, {
            method,
            headers: {
                "Accept": "application/json",
                "Content-type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "payer-api-key": this.options.apiKey,
            },
            body: JSON.stringify(data)
        })
            .then(response => response.ok ? response.json() : reject(response.statusText))
            .then((response) => resolve(response))
            .catch(e => reject(e))
    })
}

__CuracelPay.prototype.initiatePayment = function() {
    return this.sendRequest({
        path: '/',
        method: "POST",
        data: {
            amount: this.options.amount,
            email: this.options.email,
            currency: this.options.currency,
            description: this.options.description,
            metadata: this.options.metadata,
            reference: this.options.reference
        }
    })
}

__CuracelPay.prototype.pay = function() {
    this.loadAssets()
    .then(() => {
        this.createContainer();
        document.body.append(this.container);
        return this.initiatePayment()
    })
    .then(response => {
        this.loadFrame(response.url);
    }).catch(e => {
        this.close();
        throw e;
    })
}

__CuracelPay.prototype.init = function() {
    this.setEnvironment();
    this.listenToWindowEvent();
    return this;
}

__CuracelPay.prototype.unloadFrame = function() {
    if(this.container && this.container.querySelector('.frame-container')) {
        this.container.removeChild(this.container.querySelector('.frame-container'))
    }
}

__CuracelPay.prototype.loadFrame = function(url) {
    this.unloadFrame();
    this.container.append(this.createFrame(url));
}

__CuracelPay.prototype.listenToCloseEvent = function() {
    if(!this.container) return
    const closer = this.container.querySelector(".curacel-pay-container .closer-container");
    closer.addEventListener("click", () => {
        this.close();
    })
}

__CuracelPay.prototype.listenToWindowEvent = function() {
    window.onmessage =  (e) => {
        const { type, payment } = e.data;
        if(e.origin === this.FE_URL && type === 'payment-status') {
            if(typeof this.options.callback === 'function') {
                this.options.callback(payment)
            }
            this.close()
        }
    }
}

__CuracelPay.prototype.close = function() {
    if(this.container && document.body.querySelector(`.${this.container.getAttribute('class')}`)) {
        this.unloadFrame();
        document.body.removeChild(this.container);
    }
}


__CuracelPay.prototype.createContainer = function() {
    this.container = document.createElement("div");
    this.container.setAttribute("class", "curacel-pay-container");
    this.container.innerHTML = `
        <div class="closer-container">
            <button>Cancel</button>
        </div>
    `
    this.listenToCloseEvent()
}

__CuracelPay.prototype.createFrame = function(src) {
    const container = document.createElement("div");
    container.setAttribute("class", "frame-container");
    const frame = document.createElement("iframe");
    frame.setAttribute("class", "curacel-pay-frame");
    frame.setAttribute("src", src);
    container.append(frame);
    return container;
}

window.CuracelPay = {
    setup: (options) => (new __CuracelPay(options)).init()
}
