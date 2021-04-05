let Update = require('./Update')

class Edit {

    constructor() {
    }

    static change(event){
        let $this = event.this ? $(event.this) : $(this);
    }

}