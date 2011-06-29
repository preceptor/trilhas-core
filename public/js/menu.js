var Menu = {
    itens: [],
    
    has: function(name) {
        if (this.itens.toString().indexOf(name) > -1) {
            return true;
        }
        return false;
    },
    
    add: function(name) {
        this.itens.push(name);
    },
    
    remove: function(name) {
        for (var i = 0; i < this.itens.length; i++) {
            if (this.itens[i] == name) {
                this.itens.splice(i, 1);
            }
        }
    }
}