
          http1: {
            name: "http1",
            data: {},
            headers: {},
            method: "GET",
            timeout: 0,
            userName: "",
            password: "",
            cache: false,
            contentType: "application/json",
            responseType: "",
            url: "https://localhost/www/get_json.php",
            event: null,
            response: null,
            statusCode: null,
            textStatus: null,
            request: null,
            errorThrown: null,
            execute() {
              $.ajax({
                url: this.url,
                processData: false,
                cache: this.cache,
                method: this.method,
                headers: this.headers,
                timeout: this.timeout,
                username: this.userName,
                password: this.password,
                contentType: this.contentType,
                xhrFields: {
                  responseType: this.responseType
                },
                data: app._transformHttpRequest(this)
              })
              .fail(this.fail_handler)
              .done(this.done_handler);
            },
            setHeader(name, value) {
              this.headers[name] = value;
            },
            done_handler(response, textStatus, request) {
              let
                view = app._getCurrentView(),
                views = app._getLoadedViews(),
                frames = app._getLoadedFrames(),
                dialogs = app._getLoadedDialogs(),
                self = views["view1"].http1;
              self.response = response;
              self.textStatus = textStatus;
              self.statusCode = request.status;
              self.request = request;


// The server's response, as you can see in the "get_json.php" script
// included in this script, offer to us a JSON object, which we can
// directly access using the response property of the HTTP control.

app.showAlert(
  'O primeiro nome é: ' +
  views.view1.http1.response.firstName +
  ' o sobrenome é: ' +
  views.view1.http1.response.lastName
);
            },
            fail_handler(request, textStatus, errorThrown) {
              let
                view = app._getCurrentView(),
                views = app._getLoadedViews(),
                frames = app._getLoadedFrames(),
                dialogs = app._getLoadedDialogs(),
                self = views["view1"].http1;
              self.request = request;
              self.textStatus = textStatus;
              self.errorThrown = errorThrown;
              self.statusCode = request.status;


            }
          },
