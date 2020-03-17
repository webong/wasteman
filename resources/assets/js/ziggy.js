    var Ziggy = {
        namedRoutes: {"debugbar.openhandler":{"uri":"_debugbar\/open","methods":["GET","HEAD"],"domain":null},"debugbar.clockwork":{"uri":"_debugbar\/clockwork\/{id}","methods":["GET","HEAD"],"domain":null},"debugbar.telescope":{"uri":"_debugbar\/telescope\/{id}","methods":["GET","HEAD"],"domain":null},"debugbar.assets.css":{"uri":"_debugbar\/assets\/stylesheets","methods":["GET","HEAD"],"domain":null},"debugbar.assets.js":{"uri":"_debugbar\/assets\/javascript","methods":["GET","HEAD"],"domain":null},"debugbar.cache.delete":{"uri":"_debugbar\/cache\/{key}\/{tags?}","methods":["DELETE"],"domain":null},"login":{"uri":"login","methods":["GET","HEAD"],"domain":null},"login.attempt":{"uri":"login","methods":["POST"],"domain":null},"logout":{"uri":"logout","methods":["POST"],"domain":null},"dashboard":{"uri":"\/","methods":["GET","HEAD"],"domain":null},"users":{"uri":"users","methods":["GET","HEAD"],"domain":null},"users.create":{"uri":"users\/create","methods":["GET","HEAD"],"domain":null},"users.store":{"uri":"users","methods":["POST"],"domain":null},"users.edit":{"uri":"users\/{user}\/edit","methods":["GET","HEAD"],"domain":null},"users.update":{"uri":"users\/{user}","methods":["PUT"],"domain":null},"users.destroy":{"uri":"users\/{user}","methods":["DELETE"],"domain":null},"users.restore":{"uri":"users\/{user}\/restore","methods":["PUT"],"domain":null},"customers":{"uri":"customers","methods":["GET","HEAD"],"domain":null},"customers.create":{"uri":"customers\/create","methods":["GET","HEAD"],"domain":null},"customers.store":{"uri":"customers","methods":["POST"],"domain":null},"customers.edit":{"uri":"customers\/{customer}\/edit","methods":["GET","HEAD"],"domain":null},"customers.update":{"uri":"customers\/{customer}","methods":["PUT"],"domain":null},"customers.destroy":{"uri":"customers\/{customer}","methods":["DELETE"],"domain":null},"customers.restore":{"uri":"customers\/{customer}\/restore","methods":["PUT"],"domain":null},"invoices":{"uri":"invoices","methods":["GET","HEAD"],"domain":null},"invoices.create":{"uri":"invoices\/create","methods":["GET","HEAD"],"domain":null},"invoices.store":{"uri":"invoices","methods":["POST"],"domain":null},"invoices.edit":{"uri":"invoices\/{invoice}\/edit","methods":["GET","HEAD"],"domain":null},"invoices.update":{"uri":"invoices\/{invoice}","methods":["PUT"],"domain":null},"invoices.destroy":{"uri":"invoices\/{invoice}","methods":["DELETE"],"domain":null},"invoices.restore":{"uri":"invoices\/{invoice}\/restore","methods":["PUT"],"domain":null}},
        baseUrl: 'http://localhost/',
        baseProtocol: 'http',
        baseDomain: 'localhost',
        basePort: false,
        defaultParameters: []
    };

    if (typeof window.Ziggy !== 'undefined') {
        for (var name in window.Ziggy.namedRoutes) {
            Ziggy.namedRoutes[name] = window.Ziggy.namedRoutes[name];
        }
    }

    export {
        Ziggy
    }
